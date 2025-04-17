<?php

use App\Http\Controllers\EpaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Login;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\EpaymentControllerController;
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
        ]);
    }

    return redirect()->back();
})->name('set.location');

Route::get('/income', [IncomeController::class, 'incomePage'])->name('income');
Route::get('/transaction', [TransactionController::class, 'dailyTransaction'])->name('dailyTransaction');
Route::get('/weekly-transaction', [TransactionController::class, 'WeeklyTransaction'])->name('weeklyTransaction');
Route::get('/monthly-transaction', [TransactionController::class, 'MonthlyTransaction'])->name('monthlyTransaction');
Route::get('/weekly-income', [IncomeController::class, 'weeklyIncome'])->name('weeklyIncome');
Route::get('/monthly-income', [IncomeController::class, 'monthlyIncome'])->name('monthlyIncome');
Route::get('/weekly-epayment', [EpaymentController::class, 'weeklyEpayment'])->name('weeklyEpayment');
Route::get('/monthly-epayment', [EpaymentController::class, 'monthlyEpayment'])->name('monthlyEpayment');

Route::get('/testable', function () {
    return view('pages.test');
});
