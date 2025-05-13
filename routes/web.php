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
