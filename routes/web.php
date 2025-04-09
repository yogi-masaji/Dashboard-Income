<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Login;
use App\Models\lokasi;
use App\Models\grupMenu;
use App\Models\grupLokasi;
use App\Models\Menu;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('main');
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
        ]);
    }

    return redirect()->back();
})->name('set.location');
