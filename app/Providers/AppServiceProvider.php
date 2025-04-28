<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\lokasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('cachedInclude', function ($expression) {
            // Misal dipanggil kayak gini: @cachedInclude('tab-content.transaction', 'transaction-tab')
            [$view, $key] = explode(',', $expression);

            return "<?php echo Cache::remember(trim($key), 60, function() {
                return view(trim($view))->render();
            }); ?>";
        });



        View::composer('*', function ($view) {
            // Ambil semua lokasi
            $locations = lokasi::orderBy('nama_lokasi', 'asc')->get();
            $view->with('locations', $locations);

            $navbarMenus = [];
            $tabMenus = [];

            if (session()->has('selected_location_id_grup')) {
                $idGroup = session('selected_location_id_grup');

                // Ambil semua menu hanya sekali
                $menus = DB::table('ms_menu')
                    ->join('group_menu', 'ms_menu.id_Menu', '=', 'group_menu.id_Menu')
                    ->where('group_menu.id_Group', $idGroup)
                    ->orderBy('ms_menu.id_Menu')
                    ->select('ms_menu.*')
                    ->get();

                // Menu untuk navbar (sidebar)
                if ($menus->whereIn('id_Menu', [10, 4])->isNotEmpty()) {
                    $navbarMenus['All'] = '/all';
                }
                if ($menus->whereIn('id_Menu', [1, 2, 3, 4, 5, 6, 7, 10, 8, 9, 11])->isNotEmpty()) {
                    $navbarMenus['Transaction'] = '/transaction';
                }

                $searchMenus = $menus->whereIn('id_Menu', [
                    12,
                    13,
                    15,
                    16,
                    // 17,
                    // 18,
                    19,
                    20,
                    // 21,
                    26,
                    30,
                    136,
                    137,
                    138,
                    139,
                    140,
                    141,
                    142,
                    143,
                    144,
                    145,
                    147,
                    148,
                    149,
                    150,
                    151,
                    152,
                    153
                ]);
                if ($searchMenus->isNotEmpty()) {
                    $navbarMenus['Search'] = $searchMenus;
                }

                $myAccount = $menus->whereIn('id_Menu', [27]);
                if ($myAccount->isNotEmpty()) {
                    $navbarMenus['My Account'] = $myAccount;
                }

                // Menu untuk nav pills (tab horizontal)
                $singleLinks = [
                    'Transaction' => [1, 2, 3, 4, 5, 6, 7, 10, 8, 9, 11],
                    'Income' => [5, 6, 7, 10],
                    'E-Payment' => [8, 9, 11],
                ];

                foreach ($singleLinks as $name => $ids) {
                    if ($menus->whereIn('id_Menu', $ids)->isNotEmpty()) {
                        $tabMenus[$name] = match ($name) {
                            'Transaction' => '/transaction',
                            'Income' => '/income',
                            'E-Payment' => '/e-payment',
                        };
                    }
                }

                $trafficMenus = $menus->whereIn('id_Menu', [22, 23, 24, 25]);
                if ($trafficMenus->isNotEmpty()) {
                    $tabMenus['Traffic Management'] = $trafficMenus;
                }
            }

            // Share ke semua view
            View::share('navbarMenus', $navbarMenus);
            View::share('tabMenus', $tabMenus);
        });
    }
}
