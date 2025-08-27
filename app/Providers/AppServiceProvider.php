<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\lokasi;
use App\Models\TrStaff;
use App\Models\grupLokasi; // <-- TAMBAHKAN: Import model grupLokasi
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

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
        Blade::directive('cachedInclude', function ($expression) {
            // Misal dipanggil kayak gini: @cachedInclude('tab-content.transaction', 'transaction-tab')
            [$view, $key] = explode(',', $expression);

            return "<?php echo Cache::remember(trim($key), 60, function() {
                return view(trim($view))->render();
            }); ?>";
        });

        View::composer('*', function ($view) {
            $locations = collect(); // Inisialisasi sebagai koleksi kosong
            $navbarMenus = [];
            $tabMenus = [];

            // Cek apakah ada user yang sedang login menggunakan session
            if (Session::has('id_Staff')) {
                // Dapatkan id_Staff dari session.
                $idStaff = Session::get('id_Staff');

                // Ambil data staff dari tr_staff berdasarkan id_Staff
                $staff = TrStaff::where('id_Staff', $idStaff)->first();

                // Pastikan data staff ditemukan
                if ($staff) {
                    // Ambil id_Group dari data staff
                    $idGroup = $staff->id_Group;

                    // --- LOGIKA DROPDOWN LOKASI YANG DIPERBARUI ---
                    // 1. Dapatkan semua id_Lokasi dari group_lokasi berdasarkan id_Group staff
                    $locationIds = grupLokasi::where('id_Group', $idGroup)->pluck('id_Lokasi');

                    // 2. Cek jika group_lokasi tidak kosong
                    if ($locationIds->isNotEmpty()) {
                        // Jika ada, ambil data lokasi berdasarkan daftar ID yang didapat
                        $locations = lokasi::whereIn('id_Lokasi', $locationIds)
                            ->orderBy('nama_lokasi', 'asc')
                            ->get();
                    } else {
                        // Jika kosong, ambil lokasi default milik staff itu sendiri dari tabel tr_staff
                        $defaultLocation = lokasi::find($staff->id_Lokasi);
                        // Pastikan lokasi default ditemukan, lalu masukkan ke dalam collection
                        if ($defaultLocation) {
                            $locations = collect([$defaultLocation]);
                        }
                    }
                    // --- AKHIR LOGIKA YANG DIPERBARUI ---


                    // Ambil semua menu hanya sekali dari database
                    $menus = DB::table('ms_new_menu')
                        ->leftJoin('group_menu', 'ms_new_menu.id_Menu', '=', 'group_menu.id_Menu')
                        ->where('group_menu.id_Group', $idGroup) // Gunakan id_Group dari staff
                        ->orWhere('ms_new_menu.id_Menu', 154)
                        ->orderBy('ms_new_menu.id_Menu')
                        ->select('ms_new_menu.*')
                        ->get();

                    // --- Logika Menu Navbar Terpusat dengan Bootstrap Icons ---

                    // All
                    if ($menus->whereIn('id_Menu', [10, 4])->isNotEmpty()) {
                        $navbarMenus[] = [
                            'name' => 'All',
                            'url' => '/all',
                            'icon' => 'bi bi-house-door', // Bootstrap Icon
                            'children' => collect()
                        ];
                    }

                    // Transaction
                    if ($menus->whereIn('id_Menu', [1, 2, 3, 4, 5, 6, 7, 10, 8, 9, 11])->isNotEmpty()) {
                        $navbarMenus[] = [
                            'name' => 'Transaction',
                            'url' => '/transaction',
                            'icon' => 'bi bi-receipt', // Bootstrap Icon
                            'children' => collect()
                        ];
                    }

                    // Search
                    $searchMenus = $menus->whereIn('id_Menu', [12, 13, 15, 19, 20, 26, 30, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 147, 148, 149, 150, 151, 152, 153]);
                    if ($searchMenus->isNotEmpty()) {
                        $navbarMenus[] = [
                            'name' => 'Search',
                            'url' => 'javascript:void(0);',
                            'icon' => 'bi bi-search', // Bootstrap Icon
                            'children' => $searchMenus
                        ];
                    }

                    $reportOperational = $menus->whereIn('id_Menu', [14]);
                    if ($reportOperational->isNotEmpty()) {
                        $navbarMenus[] = [
                            'name' => 'Report Operational',
                            'url' => 'javascript:void(0);',
                            'icon' => 'bi bi-bar-chart', // Bootstrap Icon
                            'children' => $reportOperational
                        ];
                    }

                    // Configuration
                    $configMenus = collect();
                    if (session('user_type') === 'IT') {
                        $configMenus = $menus->whereIn('id_Menu', [133, 134, 135, 146]);
                    }

                    if ($configMenus->isNotEmpty()) {
                        $navbarMenus[] = [
                            'name' => 'Configuration',
                            'url' => 'javascript:void(0);',
                            'icon' => 'bi bi-gear', // Bootstrap Icon
                            'children' => $configMenus
                        ];
                    }


                    // My Account
                    $myAccount = $menus->whereIn('id_Menu', [27, 154]);
                    if ($myAccount->isNotEmpty()) {
                        $navbarMenus[] = [
                            'name' => 'My Account',
                            'url' => 'javascript:void(0);',
                            'icon' => 'bi bi-person-circle', // Bootstrap Icon
                            'children' => $myAccount
                        ];
                    }


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
            }

            // Kirim data yang sudah difilter ke semua view
            $view->with('locations', $locations);
            View::share('navbarMenus', $navbarMenus);
            View::share('tabMenus', $tabMenus);
        });
    }
}
