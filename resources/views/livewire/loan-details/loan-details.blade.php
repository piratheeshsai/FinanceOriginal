

<!-- resources/views/livewire/loanDetails/loan-details.blade.php -->
<div>
    <div class="card card-body p-3">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-2 col-sm-6 col-12 mt-lg-0 mt-2">
                <button class="btn bg-gradient-danger w-100 mb-0 toast-btn" type="button" wire:click="$set('currentComponent', 'collect-due')">Collect Due</button>
            </div>
            <div class="col-lg-2 col-sm-6 col-12 mt-sm-0 mt-2">
                <button class="btn bg-gradient-info w-100 mb-0 toast-btn" type="button" wire:click="$set('currentComponent', 'schedule')">Schedule</button>
            </div>
            <div class="col-lg-2 col-sm-6 col-12 mt-lg-0 mt-2">
                <button class="btn bg-gradient-warning w-100 mb-0 toast-btn" type="button" wire:click="$set('currentComponent', 'loan-term')">Loan Term</button>
            </div>
            <div class="col-lg-2 col-sm-6 col-12">
                <button class="btn bg-gradient-success w-100 mb-0 toast-btn" type="button" wire:click="$set('currentComponent', 'collections')">Collections</button>
            </div>
        </div>
    </div>



    <div>
       
        <div class="mt-4">
            @if ($currentComponent === 'collect-due')
                @livewire('loanDetails.collect-due', ['loanId' => $loanId], key('collect-due-' . $loanId))
            @elseif ($currentComponent === 'schedule')
                @livewire('loanDetails.schedule', ['loanId' => $loanId], key('schedule-' . $loanId))
            @elseif ($currentComponent === 'loan-term')
                @livewire('loanDetails.loan-term', ['loanId' => $loanId], key('loan-term-' . $loanId))
            @elseif ($currentComponent === 'collections')
                @livewire('loanDetails.collections', ['loanId' => $loanId], key('collections-' . $loanId))
            @endif
        </div>

</div>
