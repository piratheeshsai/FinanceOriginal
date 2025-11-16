<?php

use App\Http\Controllers\Account\Accounts;
use App\Http\Controllers\Account\GeneralLedgerController;
use App\Http\Controllers\Account\Transaction;
use App\Http\Controllers\admin\AssignRoleController;
use App\Http\Controllers\admin\BranchesController;
use App\Http\Controllers\admin\CenterController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\Collection\CollectionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Loan\LoanController;

use App\Http\Controllers\LoanSchemeController;
use App\Http\Controllers\Log\ActivityLogController;
use App\Http\Controllers\reports\reportController;
use App\Http\Controllers\Settings\SettingController;
use App\Http\Controllers\Users\UsersController;
use App\Livewire\Loan\LoanApproval;
use App\Models\Voucher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Documents\DocumentController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/', function () {
    return view('auth.login');
});

// Protect routes that require the user to be logged in
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Define other routes that require authentication here
    Route::post('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');

    Route::post('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
    Route::resource('users', UsersController::class);
    Route::resource('branches', BranchesController::class);
    Route::get('settings/cities', [SettingController::class, 'cities'])->name('settings.cities');
    Route::resource('settings', SettingController::class);


    Route::get('/branches/{branchId}/centers', [BranchesController::class, 'getCenters'])
        ->name('branches.centers');


    Route::resource('centers', CenterController::class);

    Route::resource('role', RoleController::class);
    Route::resource('assign', AssignRoleController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('loan-schemes', LoanSchemeController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('loan', LoanController::class);
    Route::get('api/customers', [LoanController::class, 'fetchCustomers'])->name('loan.fetch-customers');

    // Route::get('/loan-approvals', LoanApproval::class)->name('loan.approval');

    Route::get('/loan-approval', [LoanController::class, 'approval'])->name('loan.approval');
    Route::get('/loan-loanDetails/{id}', [LoanController::class, 'loanDetails'])->name('loan.loanDetails');

    Broadcast::channel('my-channel', function ($user) {
        return true; // Allow all authenticated users to subscribe
    });

    Broadcast::channel('approver-channel.{userId}', function ($user, $userId) {
        return (int) $user->id === (int) $userId && $user->can('approve-loan');
    });

    Route::get('/api/notifications/{userId}', [NotificationController::class, 'getNotifications']);
    Route::post('/api/notifications/read', [NotificationController::class, 'markAsRead']);






    Route::get('collection/loanProgress', [CollectionController::class, 'loanProgress'])->name('collection.loanProgress');
    Route::resource('collection', CollectionController::class);


    Route::resource('transaction', Transaction::class);



    Route::get('accounts/Denomination', [Accounts::class, 'cashDenomination'])->name('accounts.Denomination');
    Route::get('accounts/ManageTypes', [Accounts::class, 'ManageTypes'])->name('accounts.ManageTypes');
    Route::get('accounts/PettyCash', [Accounts::class, 'PettyCash'])->name('accounts.PettyCash');
    Route::get('accounts/payments', [Accounts::class, 'payments'])->name('accounts.payments');
    Route::get('accounts/PaymentSupplier', [Accounts::class, 'PaymentSupplier'])->name('accounts.PaymentSupplier');
    Route::get('accounts/PaymentCategory', [Accounts::class, 'PaymentCategory'])->name('accounts.PaymentCategory');
    Route::get('accounts/ProfitLoss', [Accounts::class, 'ProfitLoss'])->name('accounts.ProfitLoss');
    Route::resource('accounts', Accounts::class);

    // routes/web.php
    Route::get('/vouchers/{voucher}', function (Voucher $voucher) {
        return view('vouchers.show', compact('voucher'));
    })->name('vouchers.show');

    Route::get('/invoice/print/{id}', [InvoiceController::class, 'printPOS'])->name('invoice.print');
    Route::get('/invoice/download/{id}', [InvoiceController::class, 'downloadA4'])->name('invoice.download');





    Route::get('reports/customerList', [reportController::class, 'customerList'])->name('reports.customerList');


    Route::get('reports/loanReport', [reportController::class, 'loanReport'])->name('reports.loanReport');

    Route::get('reports/collectionReport', [reportController::class, 'collectionReport'])->name('reports.collectionReport');

    Route::get('reports/balanceSheet', [reportController::class, 'balanceSheet'])->name('reports.balanceSheet');

    Route::get('reports/branchFinancialReport', [ReportController::class, 'branchFinancialReport'])->name('reports.branchFinancialReport');

    Route::get('reports/pendingCollection', [ReportController::class, 'pendingCollection'])->name('reports.pendingCollection');


    Route::get('reports/trialBalance', [ReportController::class, 'TrialBalance'])->name('reports.trialBalance');
    Route::get('collections/transfer', [CollectionController::class, 'collectionTransfer'])->name('collections.transfer');
    Route::get('collections/all', [CollectionController::class, 'allCollections'])->name('collections.all');
    Route::get('collections/bulk', [CollectionController::class, 'bulkCollection'])->name('collections.bulk');

    Route::get('collections/collectionTrApproval', [CollectionController::class, 'collectionTransferApproval'])->name('collections.collectionTrApproval');


    Route::get('log.ActivityLog', [ActivityLogController::class, 'index'])->name('log.ActivityLog');

    Route::get('agreement', [DocumentController::class, 'agreement'])->name('agreement');

    Route::get('/loan-agreement/{loan}/pdf', [DocumentController::class, 'exportSingle'])
    ->name('loan.agreement.export');

    Route::post('/loan-agreements/bulk-pdf', [DocumentController::class, 'exportFiltered'])
    ->name('loan.agreements.bulk-export');


    Route::get('mortgage', [DocumentController::class, 'mortgage'])->name('mortgage');
    Route::get('/loan-mortgage/{loan}/pdf', [DocumentController::class, 'exportSingleMortgage'])
    ->name('loan.mortgage.export');

    Route::post('/loan-mortgage/bulk-pdf', [DocumentController::class, 'exportFilteredMortgage'])
    ->name('loan.mortgage.bulk-export');



    Route::get('promissory', [DocumentController::class, 'promissory'])->name('promissory');

    Route::get('/loan-AgreementLending/{loan}/pdf', [DocumentController::class, 'exportSingleAgreementLending'])
    ->name('loan.AgreementLending.export');








    Route::post('/loan-AgreementLending/bulk-pdf', [DocumentController::class, 'exportFilteredAgreementLending'])
    ->name('loan.AgreementLending.bulk-export');


    Route::get('promissoryOrigin', [DocumentController::class, 'promissoryOrigin'])->name('promissoryOrigin');

    Route::get('/loan-promissoryOrigin/{loan}/pdf', [DocumentController::class, 'exportSinglePromissoryOrigin'])
    ->name('loan.promissoryOrigin.export');


    Route::post('/loan-promissoryOrigin/bulk-pdf', [DocumentController::class, 'exportFilteredPromissoryOrigin'])
    ->name('loan.promissoryOrigin.bulk-export');


    Route::get('voucherTamil', [DocumentController::class, 'VoucherTamil'])->name('voucherTamil');

    Route::get('/loan-Receipt/{loan}/pdf', [DocumentController::class, 'exportSingleReceipt'])
    ->name('loan.Receipt.export');


    Route::post('/loan-Receipt/bulk-pdf', [DocumentController::class, 'VoucherTamilBulk'])
    ->name('loan.Receipt.bulk-export');



    Route::get('general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');

});
