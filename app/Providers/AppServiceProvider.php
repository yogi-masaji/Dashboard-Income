<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\lokasi;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Ambil semua lokasi untuk dropdown atau tampilan
            $locations = lokasi::orderBy('nama_lokasi', 'asc')->get();
            $view->with('locations', $locations);

            $groupedMenus = [];

            if (session()->has('selected_location_id_grup')) {
                $idGroup = session('selected_location_id_grup');

                // Ambil seluruh menu sekali query
                $menus = DB::table('ms_menu')
                    ->join('group_menu', 'ms_menu.id_Menu', '=', 'group_menu.id_Menu')
                    ->where('group_menu.id_Group', $idGroup)
                    ->orderBy('ms_menu.id_Menu')
                    ->select('ms_menu.*')
                    ->get();

                // Definisi grup tanpa submenu dan link khusus
                $singleLinks = [
                    'Transaction' => [1, 2, 3, 4],
                    'Income' => [5, 6, 7, 10],
                    'E-Payment' => [8, 9, 11],
                ];

                foreach ($singleLinks as $name => $ids) {
                    if ($menus->whereIn('id_Menu', $ids)->isNotEmpty()) {
                        // Kamu bisa ganti link-nya di sini sesuai kebutuhan
                        $groupedMenus[$name] = match ($name) {
                            'Transaction' => '/transaction',
                            'Income' => '/income-url',
                            'E-Payment' => '/e-payment-url',
                        };
                    }
                }

                // Definisi grup dengan submenu
                $subGroups = [
                    'Traffic Management' => [22, 23, 24, 25],
                    'Search' => [
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
                    ],
                    'My Account' => [27],
                ];

                foreach ($subGroups as $name => $ids) {
                    $filtered = $menus->whereIn('id_Menu', $ids);
                    if ($filtered->isNotEmpty()) {
                        $groupedMenus[$name] = $filtered;
                    }
                }
            }

            View::share('groupedMenus', $groupedMenus);
        });
    }
}
