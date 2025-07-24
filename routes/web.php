<?php

use App\Http\Controllers\EpaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Login;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\EpaymentControllerController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\IncomePaymentController;
use App\Models\lokasi;
use App\Models\grupMenu;
use App\Models\grupLokasi;
use App\Models\Menu;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('main');
});

route::get('/tester', function () {
    return view('tester');
});
Route::get('/all', function () {
    return view('pages.allchart');
});
Route::get('/login', [Login::class, 'LoginView'])->name('login');
// Rute untuk memproses data login dari form
Route::post('/login', [Login::class, 'authenticate']);

// Rute untuk proses logout
Route::post('/logout', [Login::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('main');
    })->name('mainpage');
});
Route::get('/dailytransaction', function () {
    return view('pages.dailytransaction');
});

Route::get('/lokasi', function () {
    return grupMenu::all();
});

Route::post('/set-location', function (Illuminate\Http\Request $request) {
    $lokasi = lokasi::find($request->location_id);
    if ($lokasi) {
        session([
            'selected_location_id' => $lokasi->id_Lokasi,
            'selected_location_id_grup' => $lokasi->id_Group,
            'selected_location_name' => $lokasi->nama_Lokasi,
            'selected_location_ip_lokasi' => $lokasi->ip_Lokasi,
            'selected_location_kode_lokasi' => $lokasi->kode_Lokasi,
            'selected_location_chisel_Version' => $lokasi->chisel_Version,
        ]);
    }

    return redirect()->back();
})->name('set.location');
Route::get('/tab', [TransactionController::class, 'tab'])->name('tab');
Route::get('/income', [IncomeController::class, 'incomePage'])->name('income');
Route::get('/transaction', [TransactionController::class, 'dailyTransaction'])->name('dailyTransaction');
Route::get('/daily-transaction', [TransactionController::class, 'getDailyTransaction'])->name('getDailyTransaction');
Route::get('/weekly-transaction', [TransactionController::class, 'WeeklyTransaction'])->name('weeklyTransaction');
Route::get('/monthly-transaction', [TransactionController::class, 'MonthlyTransaction'])->name('monthlyTransaction');
Route::get('/daily-income', [IncomeController::class, 'getDailyIncome'])->name('getDailyIncome');
Route::get('/weekly-income', [IncomeController::class, 'weeklyIncome'])->name('weeklyIncome');
Route::get('/monthly-income', [IncomeController::class, 'monthlyIncome'])->name('monthlyIncome');
Route::get('/daily-epayment', [EpaymentController::class, 'dailyEpayment'])->name('dailyEpayment');
Route::get('/daily-epayment-chart', [EpaymentController::class, 'weeklyEpaymentThisWeekOnly'])->name('dailyEpaymentChart');
Route::get('/weekly-epayment', [EpaymentController::class, 'weeklyEpayment'])->name('weeklyEpayment');
Route::get('/monthly-epayment', [EpaymentController::class, 'monthlyEpayment'])->name('monthlyEpayment');
Route::get('/daily-traffic', [TrafficController::class, 'dailyTraffic'])->name('dailyTraffic');
Route::get('/weekly-traffic', [TrafficController::class, 'weeklyTraffic'])->name('weeklyTraffic');
Route::get('/monthly-traffic', [TrafficController::class, 'monthlyTraffic'])->name('monthlyTraffic');
Route::get('/dailyincomefetch', [TransactionController::class, 'dailyFetch'])->name('dailyFetch');
Route::get('/testable', function () {
    return view('pages.test');
});


Route::get('/data-compare', [SearchController::class, 'dataCompareView'])->name('dataCompareView');
Route::post('/data-compare-api', [SearchController::class, 'dataCompareAPI'])->name('dataCompareAPI');
Route::post('/quantity-pergate-api', [SearchController::class, 'quantitypergateAPI'])->name('quantitypergateAPI');
Route::get('/quantity-pergate', [SearchController::class, 'quantitypergateView'])->name('quantitypergateView');
Route::post('/income-reguler-search-api', [SearchController::class, 'prodpendapatansearchAPI'])->name('prodpendapatansearchAPI');
Route::get('/income-reguler-search', [SearchController::class, 'prodpendapatansearchView'])->name('prodpendapatansearchView');
Route::post('/income-produksi-pelindo-api', [SearchController::class, 'incomePelindoSearchAPI'])->name('incomePelindoSearchAPI');
Route::get('/income-produksi-pelindo', [SearchController::class, 'incomePelindoSearchView'])->name('incomePelindoSearchView');
Route::get('/ritase-traffic-gate', [SearchController::class, 'ritaseTrafficGateView'])->name('ritaseTrafficGateView');
Route::post('/ritase-traffic-gate-api', [SearchController::class, 'ritaseTrafficGateSearchAPI'])->name('ritaseTrafficGateSearchAPI');
Route::post('/ritase-duration-api', [SearchController::class, 'ritaseDurationSearchAPI'])->name('ritaseDurationSearchAPI');
Route::get('/ritase-duration', [SearchController::class, 'ritaseDurationSearchView'])->name('ritaseDurationSearchView');
Route::post('/occupancy-rate-api', [SearchController::class, 'occupancyRateSearchAPI'])->name('occupancyRateSearchAPI');
Route::get('/occupancy-rate-search', [SearchController::class, 'occupancyRateSearchView'])->name('occupancyRateSearchView');
Route::post('/parking-member-api', [SearchController::class, 'parkingMemberSearchApi'])->name('parkingMemberSearch');
Route::get('/parking-member', [SearchController::class, 'parkingMemberView'])->name('parkingMemberView');
Route::get('/custom-search', [SearchController::class, 'customSearchView'])->name('customSearchView');
Route::post('/custom-search-api', [SearchController::class, 'customSearch'])->name('customSearch');
Route::get('/peak-search', [SearchController::class, 'peakSearchView'])->name('peakSearchView');
Route::post('/peak-search-api', [SearchController::class, 'peakSearch'])->name('peakSearch');
Route::get('/bca-membership', [SearchController::class, 'membershipSearchView'])->name('membershipSearchView');
Route::post('/membership-api', [SearchController::class, 'membershipSearch'])->name('membershipApi');
Route::get('/detail-parkir', [SearchController::class, 'parkingDetailView'])->name('parkingDetailView');
Route::post('/detail-parkir-api', [SearchController::class, 'parkingDetailSearch'])->name('parkingDetailSearch');
Route::get('/custom-report', [SearchController::class, 'SummaryReportView'])->name('summaryReportView');
Route::post('/custom-report-api', [SearchController::class, 'SummaryReportSearch'])->name('summaryReportSearch');
Route::get('/income-gate', [SearchController::class, 'incomeGateSearchView'])->name('incomeGateSearchView');
Route::post('/income-gate-api', [SearchController::class, 'incomeGateSearchApi'])->name('incomeGateSearchApi');
Route::get('/ritase-search', [SearchController::class, 'ritaseSearchView'])->name('ritaseSearchView');
Route::post('/ritase-search-api', [SearchController::class, 'ritaseSerachAPI'])->name('ritaseSearchApi');
Route::get('/occupancy', [SearchController::class, 'occupancySearchView'])->name('occupancySearchView');
Route::post('/occupancy-api', [SearchController::class, 'occupancySearchAPI'])->name('occupancySearchAPI');
Route::get('/tab-content/transaction', fn() => view('tab-content.transaction'))->name('tab.transaction');
Route::get('/tab-content/income', fn() => view('tab-content.income'))->name('tab.income');
Route::get('/tab-content/epayment', fn() => view('tab-content.epayment'))->name('tab.epayment');
Route::get('/tab-content/traffic', fn() => view('tab-content.traffic'))->name('tab.traffic');

// Route Configuration
Route::get('/form-location-tools', [ConfigController::class, 'locationView'])->name('config.locationView');
Route::get('/group-location', [ConfigController::class, 'grupLokasiView'])->name('config.grupLokasiView');
Route::get('/form-user', [Login::class, 'formUserView'])->name('config.formUserView');

Route::prefix('config')->name('config.')->group(function () {

    Route::get('/form-location-tools', [ConfigController::class, 'locationView'])->name('locationView');

    Route::get('/locations/data', [ConfigController::class, 'getLocations'])->name('locations.data');

    Route::post('/locations', [ConfigController::class, 'storeLocation'])->name('locations.store');

    Route::get('/locations/{lokasi}', [ConfigController::class, 'showLocation'])->name('locations.show');

    Route::put('/locations/{lokasi}', [ConfigController::class, 'updateLocation'])->name('locations.update');

    Route::delete('/locations/{lokasi}', [ConfigController::class, 'destroyLocation'])->name('locations.destroy');
    // --- API Routes untuk AJAX ---
    // Mengambil daftar grup untuk dropdown
    Route::get('/get-groups', [ConfigController::class, 'getGroups'])->name('getGroups');

    // Mengambil lokasi yang ada di dalam grup
    Route::post('/get-group-locations', [ConfigController::class, 'getGroupLocations'])->name('getGroupLocations');

    // Menghapus relasi lokasi dari grup
    Route::delete('/group-locations/{id}', [ConfigController::class, 'deleteGroupLocation'])->name('deleteGroupLocation');

    // Mengambil lokasi yang tersedia untuk ditambahkan
    Route::post('/get-available-locations', [ConfigController::class, 'getAvailableLocations'])->name('getAvailableLocations');

    // Menyimpan banyak lokasi ke grup
    Route::post('/store-multiple-locations', [ConfigController::class, 'storeMultipleLocations'])->name('storeMultipleLocations');

    // Route untuk Manajemen Grup (CRUD)
    Route::get('/get-all-groups', [ConfigController::class, 'getAllGroups'])->name('getAllGroups');
    Route::get('/get-all-menus', [ConfigController::class, 'getAllMenus'])->name('getAllMenus');
    Route::post('/groups', [ConfigController::class, 'storeGroup'])->name('storeGroup');
    Route::get('/groups/{group}/edit', [ConfigController::class, 'editGroup'])->name('editGroup');
    Route::put('/groups/{group}', [ConfigController::class, 'updateGroup'])->name('updateGroup');
    Route::delete('/groups/{group}', [ConfigController::class, 'destroyGroup'])->name('destroyGroup');
});


Route::prefix('form-user-api')->group(function () {
    Route::get('/data', [Login::class, 'showDataUser'])->name('form.user.data');
    Route::post('/id', [Login::class, 'showId'])->name('form.user.id');
    Route::post('/insert', [Login::class, 'insertUser'])->name('form.user.insert');
    Route::post('/show', [Login::class, 'showUser'])->name('form.user.show');
    Route::post('/default', [Login::class, 'showDefault'])->name('form.user.default');
    Route::post('/change', [Login::class, 'changeUser'])->name('form.user.change');
    Route::post('/edit', [Login::class, 'editDefault'])->name('form.user.edit');
    Route::post('/delete', [Login::class, 'deleteUser'])->name('form.user.delete');
    Route::post('/group', [Login::class, 'showGroup'])->name('form.user.group');
    Route::post('/role-user', [Login::class, 'roleUser'])->name('form.user.role');
    Route::post('/find-ip', [Login::class, 'findIp'])->name('form.user.ip');
});


Route::get('/user-login.php', [Login::class, 'userLoginHistoryView'])->name('user.login');
// Rute untuk menangani pembaruan grup pengguna dari modal
Route::post('/user-login-history/update-group', [Login::class, 'updateUserGroup'])->name('user.login.updateGroup');

// Rute BARU untuk mengambil riwayat login bulanan via AJAX
Route::get('/user-login-history/get-history/{id}', [Login::class, 'getUserMonthlyLogins'])->name('user.login.getHistory');

Route::get('/rekap-report', [IncomePaymentController::class, 'index'])->name('income.index');
Route::post('/get-income-payment', [IncomePaymentController::class, 'getIncomePayment'])->name('income.get');
Route::post('/get-lost-income', [IncomePaymentController::class, 'getLostIncomePayment'])->name('income.lost.get');
Route::post('/get-rekap-income', [IncomePaymentController::class, 'getRekapIncome'])->name('income.recap.get');
