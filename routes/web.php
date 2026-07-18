<?php

use App\Models\Customer;
use App\Models\BalanceTansfer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RoadController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\SellsReturnController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\BalanceTansferController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\TransliterationController;

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

Route::resource('/', DashboardController::class)->middleware(['auth', 'verified'])->names('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('product')->group(function () {
        Route::resource('category', CategoryController::class)->names('category');
        Route::resource('unit', UnitController::class)->names('unit');
        Route::resource('product', ProductController::class)->names('product');
        Route::resource('company', CompanyController::class)->names('company');
        Route::get('productListTable', [ProductController::class, 'productListTable'])->name('productListTable');
    });


    Route::prefix('purchase')->group(function () {
        Route::resource('purchase', PurchaseController::class)->names('purchase');
        Route::get('getPurchaseDataList', [PurchaseController::class, 'getPurchaseDataList'])->name('getPurchaseDataList');
    });

    Route::prefix('sell')->group(function () {
        Route::resource('sell', SellController::class)->names('sell');
        Route::get('getSellDataList', [SellController::class, 'getSellDataList'])->name('getSellDataList');
    });

    Route::prefix('return')->group(function () {
        Route::resource('sellsReturn', SellsReturnController::class)->names('sellsReturn');
        Route::resource('purchaseReturn', PurchaseReturnController::class)->names('purchaseReturn');
        // Route::get('sellReturnShow/{id}',[SellsReturnController::class,'sellReturnShow'])->name('sellReturnShow');
    });


    Route::prefix('people')->group(function () {
        Route::resource('supplier', SupplierController::class)->names('supplier');
        Route::resource('customer', CustomerController::class)->names('customer');

        Route::get('customerDataList', [CustomerController::class, 'customerDataList'])->name('customerDataList');
        Route::get('supplierDataList', [SupplierController::class, 'supplierDataList'])->name('supplierDataList');

        Route::get('supplierPayment/{id}', [SupplierController::class, 'supplierPayment'])->name('supplierPayment');
        Route::post('make-supplier-payment', [SupplierController::class, 'makeSupplierPayment'])->name('makeSupplierPayment');

        Route::get('customerPayment/{id}', [CustomerController::class, 'customerPayment'])->name('customerPayment');
        Route::post('make-customer-payment', [CustomerController::class, 'makeCustomerPayment'])->name('makeCustomerPayment');

        // Edit payment form
        Route::get('/supplier/payment/{payment}/edit', [SupplierController::class, 'editPayment'])->name('supplier.payment.edit');
        // Update payment
        Route::put('/supplier/payment/{payment}', [SupplierController::class, 'updatePayment'])->name('supplier.payment.update');
        // Delete payment
        Route::delete('/supplier/payment/{payment}', [SupplierController::class, 'destroyPayment'])->name('supplier.payment.destroy');

        // Customer payment routes
        Route::get('/customer/payment/{payment}/edit', [CustomerController::class, 'editPayment'])->name('customer.payment.edit');
        Route::put('/customer/payment/{payment}', [CustomerController::class, 'updatePayment'])->name('customer.payment.update');
        Route::delete('/customer/payment/{payment}', [CustomerController::class, 'destroyPayment'])->name('customer.payment.destroy');
    });

    //api sell data route
    Route::get('sellsData/{id}', [SellController::class, 'sellsData'])->name('sellsData');
    Route::get('sellsDataBarcode/{barcode}', [SellController::class, 'sellsDataBarcode'])->name('sellsDataBarcode');

    //purchase in barcode
    Route::get('getProductDataBarcode/{barcode}', [PurchaseController::class, 'getProductDataBarcode'])->name('getProductDataBarcode');

    ///api sell return data
    Route::get('sellsReturnData/{id}', [SellsReturnController::class, 'sellsReturnData'])->name('sellsReturnData');
    Route::get('purchaseReturnData/{id}', [PurchaseReturnController::class, 'purchaseReturnData'])->name('purchaseReturnData');

    ///barcode generator
    Route::get('generateBarcode/{id}', [ProductController::class, 'generateBarcode'])->name('generateBarcode');


    Route::prefix('stock')->group(function () {
        Route::resource('stock-report', StockController::class)->names('stockReport');
    });
    Route::prefix('payment')->group(function () {
        Route::resource('payment-method', PaymentMethodController::class)->names('paymentMethod');
        Route::get('paymentMethodEdit/{id}', [PaymentMethodController::class, 'payment_method_edit'])->name('payment_method_edit');
    });


    Route::prefix('expense')->group(function () {
        Route::resource('expense', ExpenseController::class)->names('expense');
        Route::resource('expenseCategory', ExpenseCategoryController::class)->names('expenseCategory');
    });


    // Route::prefix('report')->group(function () {
    Route::get('profitCalculation', [ProfitController::class, 'profitCalculation'])->name('profitCalculation');
    Route::post('dateFilter', [ProfitController::class, 'dateFilter'])->name('dateFilter');
    Route::get('sellsReportList', [ProfitController::class, 'sellsReportList'])->name('sellsReportList');
    Route::get('purchaseReportList', [ProfitController::class, 'purchaseReportList'])->name('purchaseReportList');
    Route::get('sellsReport', [ProfitController::class, 'sellsReport'])->name('sellsReport');
    // });
    //loan
    Route::prefix('loan')->group(function () {
        Route::resource('loan', LoanController::class)->names('loan');
        Route::get('loanPayment/{id}', [LoanController::class, 'loanPayment'])->name('loanPayment');
        Route::post('makeLoanPayment', [LoanController::class, 'makeLoanPayment'])->name('makeLoanPayment');
    });

    //Location
    Route::resource('location', LocationController::class)->names('location');
    Route::get('getLocationList', [LocationController::class, 'getLocationList'])->name('getLocationList');

    //Road
    Route::resource('road', RoadController::class)->names('road');
    Route::get('getRoadList', [RoadController::class, 'getRoadList'])->name('getRoadList');

    //Batch
    Route::resource('batch', BatchController::class)->names('batch');
    Route::get('getBatchList', [BatchController::class, 'getBatchList'])->name('getBatchList');

    //Product name transliteration
    Route::get('/transliterate-form', [TransliterationController::class, 'showForm']);
    Route::post('/transliterate', [TransliterationController::class, 'transliterate']);

    //accounting sheet
    Route::get('balance-sheet-view', [AccountingController::class, 'blanceSheetView'])->name('balance_sheet_view');
    Route::get('balance-sheet-table', [AccountingController::class, 'blanceSheetTable'])->name('blanceSheetTable');
    Route::get('collectionList', [AccountingController::class, 'collectionList'])->name('collectionList');
    Route::get('collectionListData', [AccountingController::class, 'collectionListData'])->name('collectionListData');

    //Cash flow show 
    Route::resource('cash_flow', CashFlowController::class)->names('cash_flow');

    Route::get('/get-sales-data', [DashboardController::class, 'getSalesData'])->name('getSalesData');
    Route::get('showAttendence', [AttendenceController::class, 'showAttendence'])->name('showAttendence');

    Route::resource('user', UserController::class)->names('user');

    Route::get('/get-total-balance', function () {
        return response()->json(['total_balance' => totalBalance()]);
    });



    //Accounts
    Route::prefix('accounts')->group(function () {
        Route::resource('accounts', AccountsController::class)->names('accounts');
        Route::get('balanceAdjustmentView', [AccountsController::class, 'balanceAdjustmentView'])->name('balanceAdjustmentView');
        Route::post('balanceAdjustment', [AccountsController::class, 'balanceAdjustment'])->name('balanceAdjustment');

        Route::get('balanceTransferView', [BalanceTansferController::class, 'balanceTransferView'])->name('balanceTransferView');
        Route::post('balanceTransfer', [BalanceTansferController::class, 'balanceTransfer'])->name('balanceTransfer');
    });
});

require __DIR__ . '/auth.php';
