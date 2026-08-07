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
use App\Http\Controllers\ReportController;
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
use App\Http\Controllers\StoreController;
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

    // Related product settings (register before product resource to avoid {product} collisions)
    Route::prefix('product')->group(function () {
        Route::resource('category', CategoryController::class)->names('category');
        Route::resource('unit', UnitController::class)->names('unit');
        Route::resource('company', CompanyController::class)->names('company');
        Route::get('list-table', [ProductController::class, 'productListTable'])->name('productListTable');
    });
    Route::resource('product', ProductController::class)->names('product');

    // Purchase (data-list before resource)
    Route::get('purchase/data-list', [PurchaseController::class, 'getPurchaseDataList'])->name('getPurchaseDataList');
    Route::resource('purchase', PurchaseController::class)->names('purchase');

    // Sell (data-list before resource)
    Route::get('sell/data-list', [SellController::class, 'getSellDataList'])->name('getSellDataList');
    Route::resource('sell', SellController::class)->names('sell');

    Route::prefix('return')->group(function () {
        Route::resource('sells-return', SellsReturnController::class)->names('sellsReturn');
        Route::resource('purchase-return', PurchaseReturnController::class)->names('purchaseReturn');
    });


    Route::prefix('people')->group(function () {
        Route::resource('supplier', SupplierController::class)->names('supplier');

        // Customer ledger PDF before resource to avoid route collisions
        Route::get('customer/{customer}/ledger/pdf', [CustomerController::class, 'downloadLedgerPdf'])
            ->name('customer.ledger.pdf');

        Route::resource('customer', CustomerController::class)->names('customer');

        Route::get('customer-data-list', [CustomerController::class, 'customerDataList'])->name('customerDataList');
        Route::get('supplier-data-list', [SupplierController::class, 'supplierDataList'])->name('supplierDataList');

        Route::get('supplier-payment/{id}', [SupplierController::class, 'supplierPayment'])->name('supplierPayment');
        Route::post('make-supplier-payment', [SupplierController::class, 'makeSupplierPayment'])->name('makeSupplierPayment');

        Route::get('customer-payment/{id}', [CustomerController::class, 'customerPayment'])->name('customerPayment');
        Route::post('make-customer-payment', [CustomerController::class, 'makeCustomerPayment'])->name('makeCustomerPayment');

        // Supplier payment routes
        Route::get('/supplier/payment/{payment}/edit', [SupplierController::class, 'editPayment'])->name('supplier.payment.edit');
        Route::put('/supplier/payment/{payment}', [SupplierController::class, 'updatePayment'])->name('supplier.payment.update');
        Route::delete('/supplier/payment/{payment}', [SupplierController::class, 'destroyPayment'])->name('supplier.payment.destroy');

        // Customer payment routes
        Route::get('/customer/payment/{payment}/edit', [CustomerController::class, 'editPayment'])->name('customer.payment.edit');
        Route::put('/customer/payment/{payment}', [CustomerController::class, 'updatePayment'])->name('customer.payment.update');
        Route::delete('/customer/payment/{payment}', [CustomerController::class, 'destroyPayment'])->name('customer.payment.destroy');
    });

    // API sell data routes
    Route::get('sells-data/{id}', [SellController::class, 'sellsData'])->name('sellsData');
    Route::get('sells-data-barcode/{barcode}', [SellController::class, 'sellsDataBarcode'])->name('sellsDataBarcode');

    // Purchase barcode lookup
    Route::get('product-data-barcode/{barcode}', [PurchaseController::class, 'getProductDataBarcode'])->name('getProductDataBarcode');

    // API return data
    Route::get('sells-return-data/{id}', [SellsReturnController::class, 'sellsReturnData'])->name('sellsReturnData');
    Route::get('purchase-return-data/{id}', [PurchaseReturnController::class, 'purchaseReturnData'])->name('purchaseReturnData');

    // Barcode generator
    Route::get('generate-barcode/{id}', [ProductController::class, 'generateBarcode'])->name('generateBarcode');

    Route::resource('stock-report', StockController::class)->names('stockReport');

    Route::resource('payment-method', PaymentMethodController::class)->names('paymentMethod');
    Route::get('payment-method-edit/{id}', [PaymentMethodController::class, 'payment_method_edit'])->name('payment_method_edit');

    Route::resource('expense-category', ExpenseCategoryController::class)->names('expenseCategory');
    Route::resource('expense', ExpenseController::class)->names('expense');

    // Reports
    Route::get('profit-calculation', [ProfitController::class, 'profitCalculation'])->name('profitCalculation');
    Route::post('date-filter', [ProfitController::class, 'dateFilter'])->name('dateFilter');
    Route::get('sells-report-list', [ProfitController::class, 'sellsReportList'])->name('sellsReportList');
    Route::get('purchase-report-list', [ProfitController::class, 'purchaseReportList'])->name('purchaseReportList');
    Route::get('sells-report', [ProfitController::class, 'sellsReport'])->name('sellsReport');
    Route::get('purchases-report', [ProfitController::class, 'purchasesReport'])->name('purchasesReport');

    Route::get('customer-due-report-list', [ReportController::class, 'customerDueList'])->name('customerDueList');
    Route::get('customer-due-report', [ReportController::class, 'customerDueReport'])->name('customerDueReport');
    Route::get('supplier-due-report-list', [ReportController::class, 'supplierDueList'])->name('supplierDueList');
    Route::get('supplier-due-report', [ReportController::class, 'supplierDueReport'])->name('supplierDueReport');
    Route::get('expense-report-list', [ReportController::class, 'expenseReportList'])->name('expenseReportList');
    Route::get('expense-report', [ReportController::class, 'expenseReport'])->name('expenseReport');
    Route::get('profit-loss-report-list', [ReportController::class, 'profitLossList'])->name('profitLossList');
    Route::get('profit-loss-report', [ReportController::class, 'profitLossReport'])->name('profitLossReport');

    // Loan (payment routes before resource)
    Route::get('loan/payment/{id}', [LoanController::class, 'loanPayment'])->name('loanPayment');
    Route::post('loan/make-payment', [LoanController::class, 'makeLoanPayment'])->name('makeLoanPayment');
    Route::resource('loan', LoanController::class)->names('loan');

    // Store settings (singleton profile)
    Route::get('store-settings', [StoreController::class, 'edit'])->name('store.edit');
    Route::put('store-settings', [StoreController::class, 'update'])->name('store.update');

    // Location
    Route::resource('location', LocationController::class)->names('location');
    Route::get('location-list', [LocationController::class, 'getLocationList'])->name('getLocationList');

    // Road
    Route::resource('road', RoadController::class)->names('road');
    Route::get('road-list', [RoadController::class, 'getRoadList'])->name('getRoadList');

    // Batch
    Route::resource('batch', BatchController::class)->names('batch');
    Route::get('batch-list', [BatchController::class, 'getBatchList'])->name('getBatchList');

    // Product name transliteration
    Route::get('/transliterate-form', [TransliterationController::class, 'showForm']);
    Route::post('/transliterate', [TransliterationController::class, 'transliterate']);

    // Accounting sheet
    Route::get('balance-sheet-view', [AccountingController::class, 'blanceSheetView'])->name('balance_sheet_view');
    Route::get('balance-sheet-table', [AccountingController::class, 'blanceSheetTable'])->name('blanceSheetTable');
    Route::get('collection-list', [AccountingController::class, 'collectionList'])->name('collectionList');
    Route::get('collection-list-data', [AccountingController::class, 'collectionListData'])->name('collectionListData');

    // Cash flow
    Route::resource('cash-flow', CashFlowController::class)->names('cash_flow');

    Route::get('/get-sales-data', [DashboardController::class, 'getSalesData'])->name('getSalesData');
    Route::get('/dashboard/collections-chart', [DashboardController::class, 'collectionsChart'])->name('dashboard.collectionsChart');
    Route::get('/dashboard/payment-status-chart', [DashboardController::class, 'paymentStatusChart'])->name('dashboard.paymentStatusChart');
    Route::get('/dashboard/expense-trend-chart', [DashboardController::class, 'expenseTrendChart'])->name('dashboard.expenseTrendChart');
    Route::get('/dashboard/road-sales-chart', [DashboardController::class, 'roadSalesChart'])->name('dashboard.roadSalesChart');
    Route::get('show-attendance', [AttendenceController::class, 'showAttendence'])->name('showAttendence');

    Route::resource('user', UserController::class)->names('user');

    Route::get('/get-total-balance', function () {
        return response()->json(['total_balance' => totalBalance()]);
    });

    // Accounts (specific routes before resource)
    Route::get('accounts/balance-adjustment', [AccountsController::class, 'balanceAdjustmentView'])->name('balanceAdjustmentView');
    Route::post('accounts/balance-adjustment', [AccountsController::class, 'balanceAdjustment'])->name('balanceAdjustment');
    Route::get('accounts/balance-transfer', [BalanceTansferController::class, 'balanceTransferView'])->name('balanceTransferView');
    Route::post('accounts/balance-transfer', [BalanceTansferController::class, 'balanceTransfer'])->name('balanceTransfer');
    Route::resource('accounts', AccountsController::class)->names('accounts');
});

require __DIR__ . '/auth.php';
