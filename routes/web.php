<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArchiveExportController;
use App\Http\Controllers\ArchiveNotController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CensorshipController;
use App\Http\Controllers\CensorshipExportController;
use App\Http\Controllers\CensorshipNotController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\ComputerExportController;
use App\Http\Controllers\ComputerNotController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustodyController;
use App\Http\Controllers\CustodyItemController;
use App\Http\Controllers\DieselController;
use App\Http\Controllers\DieselExportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ExportReportController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\HistoricalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceExportController;
use App\Http\Controllers\InvoiceReportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\JibayaController;
use App\Http\Controllers\JibayaexportController;
use App\Http\Controllers\JibayaNotController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LegalexportController;
use App\Http\Controllers\LegalNotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SubCensorshipController;
use App\Http\Controllers\SubSectionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierItemController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\TypesFuelController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// *********************************************dashboard****************************************************************
Route::get('/dashboard', [HeadController::class, 'nots'])->middleware(['auth', 'verified', 'prevent.caching'])->name('dashboard');

// *********************************************PWA Offline Page****************************************************************
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::prefix('/')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::prefix('')->middleware('auth')->group(function () {
        Route::get('/dashboard', [HeadController::class, 'nots'])->middleware('prevent.caching')->name('dashboard');
        Route::get('/', [HeadController::class, 'nots1'])->middleware('prevent.caching');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// ************************************************الأرشيف المركزي*************************************************************
Route::middleware('auth')->group(function () {
    Route::resource('diesel', DieselController::class);
    Route::resource('dieselexport', DieselExportController::class);
    Route::resource('user', UserController::class);
    Route::resource('section', SectionController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('subSection', SubSectionController::class);
    Route::resource('home', HomeController::class);
    Route::resource('legal', LegalController::class);
    Route::resource('legalexport', LegalexportController::class);
    Route::resource('jibaya', JibayaController::class);
    Route::resource('jibayaexport', JibayaexportController::class);
    Route::resource('archive', ArchiveController::class);
    Route::resource('archiveExport', ArchiveExportController::class);
    Route::resource('computer', ComputerController::class);
    Route::resource('computerExport', ComputerExportController::class);
    Route::resource('censorship', CensorshipController::class);
    Route::resource('censorshipExport', CensorshipExportController::class);
    Route::resource('subcensorship', SubCensorshipController::class);
    Route::resource('import', ImportController::class);
    Route::resource('export', ExportController::class);
    Route::resource('type', TypeController::class);
    Route::resource('user', UserController::class);
    Route::resource('employee', EmployeeController::class);

    // ************************** Vehicles / Drivers / Operations **************************
    Route::resource('vehicles', App\Http\Controllers\VehicleController::class);
    Route::resource('drivers', App\Http\Controllers\DriverController::class);
    Route::resource('vehicle-operations', App\Http\Controllers\VehicleOperationController::class);
    Route::prefix('vehicle-reports')->name('vehicle-reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\VehicleReportController::class, 'index'])->name('index');
        Route::get('/diesel', [App\Http\Controllers\VehicleReportController::class, 'diesel'])->name('diesel');
        Route::get('/oil', [App\Http\Controllers\VehicleReportController::class, 'oil'])->name('oil');
        Route::get('/monthly', [App\Http\Controllers\VehicleReportController::class, 'monthly'])->name('monthly');
        Route::get('/maintenance', [App\Http\Controllers\VehicleReportController::class, 'maintenance'])->name('maintenance');
        Route::get('/driver', [App\Http\Controllers\VehicleReportController::class, 'driver'])->name('driver');
        Route::get('/summary', [App\Http\Controllers\VehicleReportController::class, 'summary'])->name('summary');
    });

    // Role Management Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleManagementController::class, 'index'])->name('index');
        Route::get('/create', [RoleManagementController::class, 'create'])->name('create');
        Route::post('/', [RoleManagementController::class, 'store'])->name('store');
        Route::get('/{id}', [RoleManagementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RoleManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RoleManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/permissions', [RoleManagementController::class, 'assignPermissions'])->name('assignPermissions');
    });

    // Permissions Management Routes (using PermissionsUI package)
    // Route::prefix('permissions-manage')->name('permissions-manage.')->group(function () {
    //     Route::get('/', [PermissionController::class, 'index'])->name('index');
    //     Route::get('/create', [PermissionController::class, 'create'])->name('create');
    //     Route::post('/', [PermissionController::class, 'store'])->name('store');
    //     Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
    //     Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    // });

    Route::resource('purchase', PurchaseController::class);

    Route::post('purchase/search', [PurchaseController::class, 'search']);
    Route::post('purchase/sign-president/{id}', [PurchaseController::class, 'signPresident']);
    Route::post('purchase/unsign-president/{id}', [PurchaseController::class, 'unsignPresident']);
    Route::post('purchase/sign-requester/{id}', [PurchaseController::class, 'signRequester']);
    Route::post('purchase/sign-finance/{id}', [PurchaseController::class, 'signFinance']);
    Route::post('purchase/unsign-finance/{id}', [PurchaseController::class, 'unsignFinance']);
    Route::post('purchase/update-status/{id}', [PurchaseController::class, 'updateStatus']);
    Route::post('purchase/update-stock-entry/{id}', [PurchaseController::class, 'updateStockEntry']);
    Route::post('purchase/update-rent-repair/{id}', [PurchaseController::class, 'updateRentRepair']);
    Route::get('purchase/council/{id}', [PurchaseController::class, 'councilDecision'])->name('purchase.council');
    Route::post('purchase/council/{id}', [PurchaseController::class, 'storeCouncilDecision'])->name('purchase.store-council');
    Route::post('purchase/withdraw/{id}', [PurchaseController::class, 'withdraw'])->name('purchase.withdraw');
    Route::post('purchase/reopen/{id}', [PurchaseController::class, 'reopen'])->name('purchase.reopen');

    // Purchase Reports
    Route::get('purchase-report', [\App\Http\Controllers\PurchaseReportController::class, 'index'])->name('purchase-report.index');
    Route::get('purchase-report/dashboard', [\App\Http\Controllers\PurchaseReportController::class, 'dashboard'])->name('purchase-report.dashboard');
    Route::get('purchase-report/export-excel', [\App\Http\Controllers\PurchaseReportController::class, 'exportPurchasesExcel'])->name('purchase-report.export-excel');
    Route::get('purchase-report/export-pdf', [\App\Http\Controllers\PurchaseReportController::class, 'exportPurchasesPDF'])->name('purchase-report.export-pdf');
    Route::get('purchase-report/print/{id}', [\App\Http\Controllers\PurchaseReportController::class, 'printPurchase'])->name('purchase-report.print');
    Route::get('purchase-report/export-orders-excel', [\App\Http\Controllers\PurchaseReportController::class, 'exportPurchaseOrdersExcel'])->name('purchase-report.export-orders-excel');
    Route::get('purchase-report/export-orders-pdf', [\App\Http\Controllers\PurchaseReportController::class, 'exportPurchaseOrdersPDF'])->name('purchase-report.export-orders-pdf');
    Route::get('purchase-report/print-order/{id}', [\App\Http\Controllers\PurchaseReportController::class, 'printPurchaseOrder'])->name('purchase-report.print-order');

    Route::get('purchase-order/create/{purchaseId}', [PurchaseOrderController::class, 'create'])->name('purchase-order.create');
    Route::resource('purchase-order', PurchaseOrderController::class)->except(['create']);
    Route::post('purchase-order/search', [PurchaseOrderController::class, 'search']);
    Route::post('purchase-order/sign-purchases/{id}', [PurchaseOrderController::class, 'signPurchases']);
    Route::post('purchase-order/sign-finance/{id}', [PurchaseOrderController::class, 'signFinance']);
    Route::post('purchase-order/sign-requester/{id}', [PurchaseOrderController::class, 'signRequester']);
    Route::post('purchase-order/update-status/{id}', [PurchaseOrderController::class, 'updateStatus']);

    // Route::get('archive/indexedit', [ArchiveController::class,'indexedit']);

    Route::post('archiveExport/searchdate', [ArchiveExportController::class, 'searchdate']);
    Route::post('archiveExport/searchnumber', [ArchiveExportController::class, 'searchnumber']);

    Route::post('archive/searchdate', [ArchiveController::class, 'searchdate']);
    Route::post('archive/searchnumber', [ArchiveController::class, 'searchnumber']);

    Route::post('censorshipExport/search', [CensorshipExportController::class, 'search']);
    Route::post('censorship/search', [CensorshipController::class, 'search']);

    Route::post('computerExport/search', [ComputerExportController::class, 'search']);
    Route::post('computer/search', [ComputerController::class, 'search']);

    Route::post('jibayaexport/search', [JibayaexportController::class, 'search']);
    Route::post('jibaya/search', [JibayaController::class, 'search']);

    Route::post('legalexport/search', [LegalexportController::class, 'search']);
    Route::post('legal/search', [LegalController::class, 'search']);
});

// ********************************************inventory*****************************************************************
Route::prefix('inventory/')->middleware('auth')->group(function () {
    Route::resource('TypesFuel', TypesFuelController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('item', ItemController::class);
    Route::resource('store', StoreController::class);
    Route::resource('supplier_item', SupplierItemController::class);
    Route::get('invoice/skipped-vouchers', [InvoiceController::class, 'skippedVouchers'])->name('invoice.skipped-vouchers');
    Route::resource('invoice', InvoiceController::class);
    Route::get('invoice_export/skipped-vouchers', [InvoiceExportController::class, 'skippedVouchers'])->name('invoice_export.skipped-vouchers');
    Route::resource('invoice_export', InvoiceExportController::class);
    Route::resource('custody', CustodyController::class);
    Route::resource('currency', CurrencyController::class);
    Route::resource('custody_Item', CustodyItemController::class);
    // **********************************************Repooooooooooooooort*************************************************
    Route::get('totalreport', [ReportController::class, 'totalreport'])->name('inventory.totalreport');
    Route::post('searchbalance', [ReportController::class, 'searchbalance']);
    Route::get('reports/low-stock', [\App\Http\Controllers\InventoryReportController::class, 'lowStock'])->name('inventory.reports.low-stock');
    Route::get('reports/historical-stock', [\App\Http\Controllers\InventoryReportController::class, 'historicalStock'])->name('inventory.reports.historical-stock');
    Route::get('reports/historical-fuel', [\App\Http\Controllers\InventoryReportController::class, 'historicalFuel'])->name('inventory.reports.historical-fuel');
    Route::get('reports/fuel-outgoing', [\App\Http\Controllers\InventoryReportController::class, 'fuelOutgoing'])->name('inventory.reports.fuel-outgoing');
    Route::get('reports/monthly-stock', [\App\Http\Controllers\OperationalReportController::class, 'monthlyStock'])->name('inventory.reports.monthly-stock');
    Route::get('reports/top-issued-items', [\App\Http\Controllers\OperationalReportController::class, 'topIssuedItems'])->name('inventory.reports.top-issued-items');
    Route::get('reports/fuel-average', [\App\Http\Controllers\OperationalReportController::class, 'fuelAverage'])->name('inventory.reports.fuel-average');
    Route::get('reports/inventory-cost', [\App\Http\Controllers\CostReportController::class, 'inventoryCost'])->name('inventory.reports.inventory-cost');
    Route::get('reports/supplier-comparison', [\App\Http\Controllers\CostReportController::class, 'supplierComparison'])->name('inventory.reports.supplier-comparison');

    Route::get('/transactions_report', [ReportController::class, 'transactions_report'])->name('transactions_report');
    Route::match(['get', 'post'], '/transactions/{itemId}', [ReportController::class, 'transactions'])->name('transactions');
    Route::post('transactions_report/searchdate', [ReportController::class, 'searchdate']);

    Route::get('/balance', [ReportController::class, 'balance'])->name('balance');
// Route::get('/last-item/{categoryId}', [ItemController::class, 'getLastItem'])->name('last_item');

    // ************************* Export (صرف) Reports ***********************************
    Route::get('export-report', [ExportReportController::class, 'index'])->name('export-report.index');
    Route::match(['get', 'post'], 'export-report/items', [ExportReportController::class, 'byItem'])->name('export-report.items');
    Route::match(['get', 'post'], 'export-report/section', [ExportReportController::class, 'bySection'])->name('export-report.section');
    Route::match(['get', 'post'], 'export-report/invoice-values', [ExportReportController::class, 'invoiceValues'])->name('export-report.invoice-values');
    Route::match(['get', 'post'], 'export-report/section-totals', [ExportReportController::class, 'sectionTotals'])->name('export-report.section-totals');

    // *********************** Purchase (شراء) Invoices Reports *************************
    Route::get('invoice-report', [InvoiceReportController::class, 'index'])->name('invoice-report.index');
    Route::match(['get', 'post'], 'invoice-report/items', [InvoiceReportController::class, 'byItem'])->name('invoice-report.items');
    Route::match(['get', 'post'], 'invoice-report/supplier', [InvoiceReportController::class, 'bySupplier'])->name('invoice-report.supplier');
    Route::match(['get', 'post'], 'invoice-report/invoice-values', [InvoiceReportController::class, 'invoiceValues'])->name('invoice-report.invoice-values');
    Route::match(['get', 'post'], 'invoice-report/supplier-totals', [InvoiceReportController::class, 'supplierTotals'])->name('invoice-report.supplier-totals');

    // *****************************************Inside_Search******************************************************
    Route::post('invoice/searchdate', [InvoiceController::class, 'searchdate']);
    Route::post('invoice_export/searchdate', [InvoiceExportController::class, 'searchdate']);
    Route::post('custody/searchdate', [CustodyController::class, 'searchdate']);
});

// *******************************************get_ajax******************************************************************

Route::get('/getUsers/{id}', [CustodyController::class, 'getUsers']);
Route::get('/getsub_section/{id}', [UserController::class, 'getsub_section']);

// **********************************************sendNotification***************************************************************
Route::prefix('sendNotification')->middleware('auth')->group(function () {
    Route::resource('archiveNot', ArchiveNotController::class);
    Route::resource('legalNot', LegalNotController::class);
    Route::resource('censorshipNot', CensorshipNotController::class);
    Route::resource('computerNot', ComputerNotController::class);
    Route::resource('jibayaNot', JibayaNotController::class);
    Route::get('archiveNotshow/{id}', [ArchiveNotController::class, 'archiveshow']);

    Route::post('archiveNot/searchdate', [ArchiveNotController::class, 'searchdate']);
});

// ********************************************Report*****************************************************************
Route::prefix('/')->group(function () {
    // **************************DieselExport********************************************************************
    Route::get('dieselexportfilter', [DieselExportController::class, 'report'])->name('dieselexport.report');
    Route::get('totalreport', [DieselExportController::class, 'totalreport'])->name('dieselexport.totalreport');
    Route::post('dieselexport/searchdate', [DieselExportController::class, 'searchdate'])->name('dieselexport.searchdate');
    Route::post('dieselexport/searchvou', [DieselExportController::class, 'searchvou'])->name('dieselexport.searchvou');
    Route::post('dieselexport/searchsec', [DieselExportController::class, 'searchsec'])->name('dieselexport.searchsec');
    Route::post('dieselexport/searchname', [DieselExportController::class, 'searchname'])->name('dieselexport.searchname');
    Route::post('dieselexport/searchtotal', [DieselExportController::class, 'searchtotal'])->name('dieselexport.searchtotal');
    // **************************Diesel********************************************************************

    Route::match(['get', 'post'], '/dieselreport', [DieselController::class, 'report'])->name('diesel.report');
    Route::match(['get', 'post'], '/diesel/transactions/{id}', [DieselController::class, 'transactions'])->name('diesel.transactions');

    // **************************legal********************************************************************
    Route::get('legalreport', [LegalController::class, 'report']);
});

// ***********************************************historical**************************************************************
Route::prefix('/historical')->group(function () {
    Route::get('exportindex', [HistoricalController::class, 'exportindex'])->name('historical.exportindex');
    // Route::get('exportreport', [HistoricalController::class, 'exportreport'])->name('historical.exportreport');
    Route::get('totalreport', [HistoricalController::class, 'totalreport'])->name('historical.totalreport');
    // Route::post('searchdate', [HistoricalController::class, 'searchdate'])->name('historical.searchdate');
    // Route::post('searchvou', [HistoricalController::class, 'searchvou'])->name('historical.searchvou');
    // Route::post('searchsec', [HistoricalController::class, 'searchsec'])->name('historical.searchsec');
    // Route::post('searchname', [HistoricalController::class, 'searchname'])->name('historical.searchname');

    Route::post('searchtotal', [HistoricalController::class, 'searchtotal'])->name('historical.searchtotal');

    Route::get('waredindex', [HistoricalController::class, 'waredindex'])->name('historical.waredindex');
    // Route::get('waredreport', [HistoricalController::class, 'waredreport'])->name('historical.waredreport');
    // Route::post('datewared', [HistoricalController::class, 'datewared'])->name('historical.datewared');
});

// ***********************************************court**************************************************************

Route::prefix('/')->group(function () {
    // ***********************************طلب تنفيذي أمر المحكمة 2 *************************************
    Route::get('court/court_order', [CourtController::class, 'court_order']);

    // ************************قبل عمل الاخطار ******************************
    Route::get('court/B_all_amount', [CourtController::class, 'B_all_amount']);
    Route::get('court/B_part_amount', [CourtController::class, 'B_part_amount']);

    // *****************************************أوامر المنع*************************************
    Route::get('court/travel_ban', [CourtController::class, 'travel_ban']);
    Route::get('court/salary_reservation', [CourtController::class, 'salary_reservation']);
    Route::get('court/detention_order', [CourtController::class, 'detention_order']);

    // ************************بعد عمل الاخطار ******************************
    Route::get('court/A_all_amount', [CourtController::class, 'A_all_amount']);
    Route::get('court/A_part_amount', [CourtController::class, 'A_part_amount']);
    Route::get('court/end_detention', [CourtController::class, 'end_detention']);
});

// *********************************اضافة اخطار 1 ********************************************
Route::resource('court', CourtController::class);

// *********************************last item in category ********************************************
// Route::get('/category/{id}/last-item', [ItemController::class, 'getLastItem'])->name('last_item');
Route::get('/category/{id}/last-item', [ItemController::class, 'getLastItem'])
    ->name('category.last_item');

Route::get('/last-item/{categoryId}', [ItemController::class, 'getLastItem'])
    ->name('item.last_by_category');

require __DIR__ . '/auth.php';
