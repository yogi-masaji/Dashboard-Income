<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\CssSelector\Node\FunctionNode;

class TransactionController extends Controller
{
    public function dailyTransaction()
    {
        $locationCode = session('selected_location_kode_lokasi');

        $response = Http::get('http://110.0.100.70:8080/v3/api/daily-quantity', [
            'location_code' => $locationCode,
        ]);

        $data = $response->json();

        // Cek apakah data ada
        if (empty($data['data']) || empty($data['data'][0]['today']) || empty($data['data'][0]['yesterday'])) {
            // Jika data kosong, langsung kembalikan data kosong atau beri pesan
            return view('tester', ['processedData' => []]);
        }

        // Kalau ada data, lanjutkan proses seperti biasa
        $todayData = $data['data'][0]['today'][0];
        $yesterdayData = $data['data'][0]['yesterday'][0];

        // Lanjutkan dengan proses perhitungan persen perubahan
        $categories = [
            'grandcasual' => 'Total Casual',
            'grandpass' => 'Total Pass',
            'grandtotal' => 'All Vehicle',
        ];

        $processedData = [];

        foreach ($categories as $key => $label) {
            $todayValue = $todayData[$key] ?? 0;  // Gunakan 0 jika nilai tidak ada
            $yesterdayValue = $yesterdayData[$key] ?? 0;  // Gunakan 0 jika nilai tidak ada

            // Hitung perubahan persentase
            $change = $todayValue - $yesterdayValue;
            $percentChange = $yesterdayValue != 0 ? (($change / $yesterdayValue) * 100) : 0;
            $direction = $percentChange >= 0 ? '↑' : '↓';
            $color = $percentChange >= 0 ? 'green' : 'red';

            $processedData[] = [
                'label' => $label,
                'today' => $todayValue,
                'yesterday' => $yesterdayValue,
                'percent_change' => number_format($percentChange, 1),
                'direction' => $direction,
                'color' => $color,
            ];
        }

        return view('tester', ['processedData' => $processedData]);
    }



    public function WeeklyTransaction()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            // This week: 7 hari terakhir sampai hari ini
            $thisWeekStart = $today->copy()->subDays(6)->format('Y-m-d'); // 8 April
            $thisWeekEnd = $today->format('Y-m-d'); // 14 April

            // Last week: 7 hari sebelumnya
            $lastWeekStart = $today->copy()->subDays(13)->format('Y-m-d'); // 1 April
            $lastWeekEnd = $today->copy()->subDays(7)->format('Y-m-d'); // 7 April
            $locationCode = session('selected_location_kode_lokasi');

            // Request data
            $response = Http::post('http://110.0.100.70:8080/v3/api/getquantity', [
                'effective_start_date' => $lastWeekStart,
                'effective_end_date' => $thisWeekEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'][0];

                $casualData = $data['casual'];
                $passData = $data['pass'];

                $thisWeekCasual = array_values(collect($casualData)->filter(
                    fn($item) =>
                    Carbon::parse($item['tanggal'])->between($thisWeekStart, $thisWeekEnd)
                )->toArray());

                $thisWeekPass = array_values(collect($passData)->filter(
                    fn($item) =>
                    Carbon::parse($item['tanggal'])->between($thisWeekStart, $thisWeekEnd)
                )->toArray());

                $lastWeekCasual = array_values(collect($casualData)->filter(
                    fn($item) =>
                    Carbon::parse($item['tanggal'])->between($lastWeekStart, $lastWeekEnd)
                )->toArray());

                $lastWeekPass = array_values(collect($passData)->filter(
                    fn($item) =>
                    Carbon::parse($item['tanggal'])->between($lastWeekStart, $lastWeekEnd)
                )->toArray());

                // Calculate totals for this week
                $thisWeekCasualTotals = $this->calculateTotals($thisWeekCasual, 'casual');
                $thisWeekPassTotals = $this->calculateTotals($thisWeekPass, 'pass');

                // Calculate totals for last week
                $lastWeekCasualTotals = $this->calculateTotals($lastWeekCasual, 'casual');
                $lastWeekPassTotals = $this->calculateTotals($lastWeekPass, 'pass');

                // Return the response with totals
                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Quantity Data for Period ' . $locationCode . ' - ' . $thisWeekEnd . ' (This Week) and ' . $lastWeekStart . ' - ' . $lastWeekEnd . ' (Last Week)',
                    'status_code' => 200,
                    'location_code' => 'GI2',
                    'this_week' => [
                        'casual' => $thisWeekCasual,
                        'pass' => $thisWeekPass,
                        'totals' => [
                            'casual' => $thisWeekCasualTotals,
                            'pass' => $thisWeekPassTotals,
                            // 'total' => array_merge($thisWeekCasualTotals, $thisWeekPassTotals),
                        ],
                    ],
                    'last_week' => [
                        'casual' => $lastWeekCasual,
                        'pass' => $lastWeekPass,
                        'totals' => [
                            'casual' => $lastWeekCasualTotals,
                            'pass' => $lastWeekPassTotals,
                            // 'total' => array_merge($lastWeekCasualTotals, $lastWeekPassTotals),
                        ],
                    ],
                ]);
            }

            return response()->json(['message' => 'Failed to fetch data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function MonthlyTransaction()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            // Bulan ini: dari tanggal 1 sampai hari ini
            $thisMonthStart = $today->copy()->startOfMonth()->format('Y-m-d');
            $thisMonthEnd = $today->format('Y-m-d');

            // Bulan lalu: dari tanggal 1 sampai akhir bulan lalu
            $lastMonthStart = $today->copy()->subMonth()->startOfMonth()->format('Y-m-d');
            $lastMonthEnd = $today->copy()->subMonth()->endOfMonth()->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            // Request data
            $response = Http::post('http://110.0.100.70:8080/v3/api/getquantity', [
                'effective_start_date' => $lastMonthStart,
                'effective_end_date' => $thisMonthEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'][0];

                $casualData = $data['casual'];
                $passData = $data['pass'];

                // Filter data untuk bulan ini
                $thisMonthCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($thisMonthStart, $thisMonthEnd)
                )->toArray());

                $thisMonthPass = array_values(collect($passData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($thisMonthStart, $thisMonthEnd)
                )->toArray());

                // Filter data untuk bulan lalu
                $lastMonthCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($lastMonthStart, $lastMonthEnd)
                )->toArray());

                $lastMonthPass = array_values(collect($passData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($lastMonthStart, $lastMonthEnd)
                )->toArray());

                // Hitung total bulanan
                $thisMonthCasualTotals = $this->calculateTotals($thisMonthCasual, 'casual');
                $thisMonthPassTotals = $this->calculateTotals($thisMonthPass, 'pass');

                $lastMonthCasualTotals = $this->calculateTotals($lastMonthCasual, 'casual');
                $lastMonthPassTotals = $this->calculateTotals($lastMonthPass, 'pass');

                // Group mingguan
                $thisMonthCasualByWeek = $this->groupByWeek($thisMonthCasual);
                $thisMonthPassByWeek = $this->groupByWeek($thisMonthPass);

                // Hitung total mingguan
                $thisMonthCasualWeekTotals = [
                    'week_1' => $this->calculateTotals($thisMonthCasualByWeek['week_1'], 'casual'),
                    'week_2' => $this->calculateTotals($thisMonthCasualByWeek['week_2'], 'casual'),
                    'week_3' => $this->calculateTotals($thisMonthCasualByWeek['week_3'], 'casual'),
                    'week_4' => $this->calculateTotals($thisMonthCasualByWeek['week_4'], 'casual'),
                    'week_5' => $this->calculateTotals($thisMonthCasualByWeek['week_5'], 'casual'),
                ];

                $thisMonthPassWeekTotals = [
                    'week_1' => $this->calculateTotals($thisMonthPassByWeek['week_1'], 'pass'),
                    'week_2' => $this->calculateTotals($thisMonthPassByWeek['week_2'], 'pass'),
                    'week_3' => $this->calculateTotals($thisMonthPassByWeek['week_3'], 'pass'),
                    'week_4' => $this->calculateTotals($thisMonthPassByWeek['week_4'], 'pass'),
                    'week_5' => $this->calculateTotals($thisMonthPassByWeek['week_5'], 'pass'),
                ];

                return response()->json([
                    'response' => 'Success Get Monthly Data',
                    'message' => 'Get Quantity Data for ' . $locationCode . ' - This Month: ' . $thisMonthStart . ' to ' . $thisMonthEnd . ' | Last Month: ' . $lastMonthStart . ' to ' . $lastMonthEnd,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_month' => [
                        // 'pass' => $thisMonthPass, // Data casual disembunyikan
                        'totals' => [
                            'casual' => $thisMonthCasualTotals,
                            'pass' => $thisMonthPassTotals,
                        ],
                        'weekly_totals' => [
                            'casual' => $thisMonthCasualWeekTotals,
                            'pass' => $thisMonthPassWeekTotals,
                        ],
                    ],
                    'last_month' => [
                        // 'pass' => $lastMonthPass, // Data casual disembunyikan
                        'totals' => [
                            'casual' => $lastMonthCasualTotals,
                            'pass' => $lastMonthPassTotals,
                        ],
                    ],
                ]);
            }

            return response()->json(['message' => 'Failed to fetch monthly data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }




    // Helper function to calculate totals for casual or pass data
    private function calculateTotals($data, $type = 'casual')
    {
        $totalVehicle = 0;
        $totalCar = 0;
        $totalMotorbike = 0;
        $totalTruck = 0;
        $totalTaxi = 0;

        foreach ($data as $item) {
            $vehicleKey = $type === 'casual' ? 'vehiclecasual' : 'vehiclepass';
            $carKey = $type === 'casual' ? 'carcasual' : 'carpass';
            $motorbikeKey = $type === 'casual' ? 'motorbikecasual' : 'motorbikepass';
            $truckKey = $type === 'casual' ? 'truckcasual' : 'truckpass';
            $taxiKey = $type === 'casual' ? 'taxicasual' : 'taxipass';

            $totalVehicle += $item[$vehicleKey] ?? 0;
            $totalCar += $item[$carKey] ?? 0;
            $totalMotorbike += $item[$motorbikeKey] ?? 0;
            $totalTruck += $item[$truckKey] ?? 0;
            $totalTaxi += $item[$taxiKey] ?? 0;
        }

        return [
            'total_vehicle' => $totalVehicle,
            'total_car' => $totalCar,
            'total_motorbike' => $totalMotorbike,
            'total_truck' => $totalTruck,
            'total_taxi' => $totalTaxi,
            'total_all' => $totalVehicle,
        ];
    }

    private function groupByWeek($data)
    {
        $weeks = [
            'week_1' => [],
            'week_2' => [],
            'week_3' => [],
            'week_4' => [],
            'week_5' => [],
        ];

        foreach ($data as $item) {
            $day = Carbon::parse($item['tanggal'])->day;

            if ($day >= 1 && $day <= 7) {
                $weeks['week_1'][] = $item;
            } elseif ($day >= 8 && $day <= 14) {
                $weeks['week_2'][] = $item;
            } elseif ($day >= 15 && $day <= 21) {
                $weeks['week_3'][] = $item;
            } elseif ($day >= 22 && $day <= 28) {
                $weeks['week_4'][] = $item;
            } else {
                $weeks['week_5'][] = $item;
            }
        }

        return $weeks;
    }
}
