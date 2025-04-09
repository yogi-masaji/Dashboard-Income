<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\lokasi;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Ambil semua lokasi untuk dropdown atau tampilan
            $locations = lokasi::orderBy('nama_lokasi', 'asc')->get();
            $view->with('locations', $locations);

            // Default kosong
            $groupedMenus = [];

            // Cek apakah user sudah pilih lokasi
            if (session()->has('selected_location_id_grup')) {
                $idGroup = session('selected_location_id_grup');

                // Ambil semua id_menu yang tersedia di group_menu untuk group ini
                $menuIds = DB::table('group_menu')
                    ->where('id_Group', $idGroup)
                    ->pluck('id_Menu');

                // Ambil hanya menu yang masuk dalam grup tersebut
                $menus = DB::table('ms_menu')
                    ->whereIn('id_Menu', $menuIds)
                    ->orderBy('id_Menu')
                    ->get();

                // Kelompokkan berdasarkan id_Menu
                $groupedMenus = [
                    'Transaction' => $menus->whereIn('id_Menu', [1, 2, 3, 4]),
                    'Income' => $menus->whereIn('id_Menu', [5, 6, 7, 10]),
                    'E-Payment' => $menus->whereIn('id_Menu', [8, 9, 11]),
                    'Traffic Management' => $menus->whereIn('id_Menu', [22, 23, 24, 25]),
                    'Search' => $menus->whereIn('id_Menu', [
                        12,
                        13,
                        15,
                        16,
                        17,
                        18,
                        19,
                        20,
                        21,
                        26,
                        30,
                        136,
                        137,
                        138,
                        139,
                        140,
                        141,
                        143,
                        145,
                        148,
                        149,
                        150,
                        151,
                        152,
                        153
                    ]),
                    'My Account' => $menus->whereIn('id_Menu', [27]),
                ];
            }

            // Share hasil groupedMenus ke semua view
            View::share('groupedMenus', $groupedMenus);
        });
    }
}
