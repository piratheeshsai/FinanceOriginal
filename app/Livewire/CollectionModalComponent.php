<?php

namespace App\Livewire;

use App\Models\Collection;
use App\Models\CollectionInvoice;
use App\Models\LoanCollectionSchedule;
use App\Services\LoanCollectionService;
use Livewire\Attributes\On;
use Livewire\Component;

class CollectionModalComponent extends Component
{
    public $scheduleId;
    public $selectedCollection = null;
    public $showModal = false;
    public $isLoading = false;
    public $loanId;
    // Form properties
    public $repaymentAmount;
    public $repaymentMethod;
    public $description;

    // A public property to store the last collection ID for printing
    public $lastCollectionId = null;

    // Define listeners
    protected $listeners = [
        'openCollectionModal' => 'openModal',
        'submitPayment',
        'printReceipt' => 'handlePrintReceipt'
    ];

    protected $rules = [
        'repaymentAmount' => 'required|numeric|min:1',
        'repaymentMethod' => 'required|string',
        'description' => 'nullable|string',
    ];

    public function openModal($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $this->loadCollection();
        $this->showModal = true;
        $this->loanId = $this->selectedCollection->loan->id;

        // Reset form fields
        $this->repaymentAmount = '';
        $this->repaymentMethod = '';
        $this->description = '';


    }

    public function loadCollection()
    {
        // Eager load all necessary relationships

        $this->resetValidation();


        $this->selectedCollection = LoanCollectionSchedule::with([
            'loan.customer',
            'loan.loanProgress',
            'loan.center'
        ])->find($this->scheduleId);

        // Pre-fill amount with pending due
        $this->repaymentAmount = $this->selectedCollection->pending_due;
    }

    public function closeModal()
    {
        // Do not reset the lastCollectionId when closing the modal
        $this->showModal = false;
        $this->selectedCollection = null;
        $this->dispatch('collectionModalClosed');
    }

    private $collectionService;

    public function boot(LoanCollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    #[On('submitPayment')]
    public function submitPayment()
    {
        \Log::info('Validation started', [
            'repaymentAmount' => $this->repaymentAmount,
            'repaymentMethod' => $this->repaymentMethod,
            'selectedCollectionId' => $this->selectedCollection->id ?? null,
            'loanId' => $this->loanId ?? null
        ]);

        $this->validate();

        \Log::info('Validation passed');

        try {
            // Process the payment
            $result = $this->collectionService->processRepayment(
                $this->loanId,
                $this->repaymentAmount,
                $this->repaymentMethod,
                now()->toDateString(),
                $this->description
            );

            \Log::info('Payment process result', ['result' => $result]);

            // *** IMPORTANT FIX *** Store the NEW collection ID from the result, not the schedule ID
            $newCollectionId = $result['collection_id'];
            $this->lastCollectionId = $newCollectionId;
            session()->put('last_collection_id', $newCollectionId);

            \Log::info('New Collection ID captured', [
                'newCollectionId' => $newCollectionId,
                'lastCollectionId_property' => $this->lastCollectionId,
                'session_collection_id' => session('last_collection_id')
            ]);

            $this->reset(['repaymentAmount', 'repaymentMethod', 'description']);

            // Close the modal after successful payment
            $this->showModal = false;

            // Show success message with print options using SweetAlert
            $this->dispatch('paymentSuccessWithPrintOptions', [
                'collectionId' => $newCollectionId,
                'message' => 'Payment collected successfully!'
            ]);

            \Log::info('Success event dispatched with ID', [
                'collectionId' => $newCollectionId
            ]);
        } catch (\Exception $e) {
            \Log::error('Transaction error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch(
                'show-error-alert',
                title: 'Payment Error',
                message: $e->getMessage(),
                icon: 'error'
            );
            return;
        }
    }

    /**
     * Generate invoice and redirect to print view
     */

     public function printPOSReceipt($collectionId)
     {
         \Log::info('Attempting to generate POS receipt', ['collectionId' => $collectionId]);

         try {
             // 1. Verify collection exists
             $collection = Collection::find($collectionId);

             if (!$collection) {
                 throw new \Exception("Collection not found with ID: {$collectionId}");
             }

             // 2. Validate required fields
             if (!$collection->loan_id) {
                 throw new \Exception("Associated loan missing for collection ID: {$collectionId}");
             }

             if (!$collection->collected_amount) {
                 throw new \Exception("Collected amount not set for collection ID: {$collectionId}");
             }

             // 3. Create or retrieve invoice
             $invoice = CollectionInvoice::firstOrCreate(
                 ['collection_id' => $collectionId, 'type' => 'pos'],
                 [
                     'invoice_number' => 'INV-' . now()->format('Y') . '-' . str_pad(CollectionInvoice::count() + 1, 6, '0', STR_PAD_LEFT),
                     'loan_id' => $collection->loan_id,
                     'collected_amount' => $collection->collected_amount,
                 ]
             );

             \Log::info('POS invoice processed successfully', [
                 'invoice_id' => $invoice->id,
                 'collection_id' => $collection->id
             ]);
             
             return redirect()->route('invoice.print', ['id' => $invoice->id]);

             // Dispatch an event to open the print page in a new window
            //  $printUrl = route('invoice.print', ['id' => $invoice->id]);
            //  $this->dispatch('openInNewWindow', ['url' => $printUrl]);

         } catch (\Exception $e) {
             \Log::error('POS Receipt Generation Failed: ' . $e->getMessage(), [
                 'collectionId' => $collectionId,
                 'error' => $e->getTraceAsString()
             ]);

             $this->dispatch('show-error-alert', [
                 'title' => 'Receipt Generation Failed',
                 'message' => $e->getMessage(),
                 'icon' => 'error'
             ]);
         }
     }
    /**
     * Generate PDF invoice
     */
    public function printA4Receipt($collectionId)
    {
        \Log::info('Generating A4 PDF', ['collectionId' => $collectionId]);

        try {
            // Make sure we're using the right Collection model - adjust the namespace if needed
            $collectionClass = class_exists('\App\Models\Collection')
                ? '\App\Models\Collection'
                : '\App\Models\LoanCollection';

            $invoice = CollectionInvoice::where('collection_id', $collectionId)
                ->where('type', 'a4')
                ->first();

            if (!$invoice) {
                // Get the collection model
                $collection = $collectionClass::findOrFail($collectionId);

                \Log::info('Creating new A4 invoice', [
                    'collection' => $collection->id,
                    'loan_id' => $collection->loan_id ?? null,
                    'amount' => $collection->collected_amount ?? null
                ]);

                // Create a new invoice
                $invoice = CollectionInvoice::create([
                    'invoice_number' => 'INV-' . now()->year . str_pad(CollectionInvoice::count() + 1, 4, '0', STR_PAD_LEFT),
                    'loan_id' => $collection->loan_id,
                    'collection_id' => $collection->id,
                    'collected_amount' => $collection->collected_amount,
                    'type' => 'a4'
                ]);
            }

            \Log::info('Generated A4 invoice', ['invoice_id' => $invoice->id]);

            // Redirect to download page using JavaScript for Livewire compatibility
            // $this->dispatch('redirectToPrint', ['url' => route('invoice.download', ['id' => $invoice->id])]);

            $this->dispatch('download-triggered');
            return redirect()->route('invoice.download', ['id' => $invoice->id]);

        } catch (\Exception $e) {
            \Log::error('PDF error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch(
                'show-error-alert',
                title: 'PDF Error',
                message: $e->getMessage(),
                icon: 'error'
            );
        }
    }

    #[On('printReceipt')]
    public function handlePrintReceipt($collectionId = null, $type = null)
    {
        // Log what we received
        \Log::info('Print receipt triggered', [
            'collectionId' => $collectionId,
            'type' => $type
        ]);

        // If parameters are nested in arrays (from Livewire dispatch)
        if (is_array($collectionId) && isset($collectionId['collectionId'])) {
            $type = $collectionId['type'];
            $collectionId = $collectionId['collectionId'];
        }

        if ($type === 'pos') {
            return $this->printPOSReceipt($collectionId);
        } elseif ($type === 'a4') {
            return $this->printA4Receipt($collectionId);
        } else {
            \Log::error('Invalid receipt type or missing parameters', [
                'collectionId' => $collectionId,
                'type' => $type
            ]);
        }
    }

    public function render()
    {
        return view('livewire.collection-modal-component');
    }
}
