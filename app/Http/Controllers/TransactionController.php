<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function tab()
    {
        return view('pages.test');
    }
    public function dailyTransaction()
    {
        return view('pages.test');
    }

    public function getDailyTransaction(Request $request)
    {
        try {
            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::get("http://110.0.100.70:8080/v3/api/daily-quantity", [
                'location_code' => $locationCode
            ]);

            if ($response->successful()) {
                $originalData = $response->json();
                $data = $originalData['data'][0] ?? null;

                if ($data && isset($data['today'][0]) && isset($data['yesterday'][0])) {
                    $today = $data['today'][0];
                    $yesterday = $data['yesterday'][0];

                    $comparisonItems = [
                        'grandcasual' => 'Total Casual',
                        'grandpass' => 'Total Pass',
                        'grandtotal' => 'All Vehicle'
                    ];

                    $comparison = [];

                    foreach ($comparisonItems as $key => $label) {
                        $todayValue = isset($today[$key]) ? (int) $today[$key] : 0;
                        $yesterdayValue = isset($yesterday[$key]) ? (int) $yesterday[$key] : 0;

                        $percentChange = $yesterdayValue != 0
                            ? (($todayValue - $yesterdayValue) / $yesterdayValue) * 100
                            : 0;

                        $direction = $percentChange >= 0 ? '↑' : '↓';
                        $color = $percentChange >= 0 ? 'green' : 'red';

                        $comparison[] = [
                            'type' => $label,
                            'yesterday' => $yesterdayValue,
                            'today' => $todayValue,
                            'percent_change' => number_format($percentChange, 1) . '%',
                            'direction' => $direction,
                            'color' => $color,
                        ];
                    }

                    // Tambahkan hasil compare ke response
                    $originalData['vehicle_comparison'] = $comparison;
                }

                return response()->json($originalData, $response->status());
            } else {
                return response()->json([
                    'message' => 'Failed to fetch data from API',
                    'status_code' => $response->status(),
                    'error' => $response->body(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error fetching daily quantity: ' . $e->getMessage());

            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function WeeklyTransaction()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            // This week: 7 hari terakhir sampai hari ini
            $thisWeekStart = $today->copy()->subDays(6)->format('Y-m-d');
            $thisWeekEnd = $today->format('Y-m-d');

            // Last week: 7 hari sebelumnya
            $lastWeekStart = $today->copy()->subDays(13)->format('Y-m-d');
            $lastWeekEnd = $today->copy()->subDays(7)->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

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
                    fn($item) => Carbon::parse($item['tanggal'])->between($thisWeekStart, $thisWeekEnd)
                )->toArray());

                $thisWeekPass = array_values(collect($passData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($thisWeekStart, $thisWeekEnd)
                )->toArray());

                $lastWeekCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($lastWeekStart, $lastWeekEnd)
                )->toArray());

                $lastWeekPass = array_values(collect($passData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($lastWeekStart, $lastWeekEnd)
                )->toArray());

                // Calculate totals (only casual)
                $thisWeekCasualTotals = $this->calculateTotals($thisWeekCasual, 'casual');
                $lastWeekCasualTotals = $this->calculateTotals($lastWeekCasual, 'casual');

                $thisWeekPassTotals = $this->calculateTotals($thisWeekPass, 'pass');
                $lastWeekPassTotals = $this->calculateTotals($lastWeekPass, 'pass');
                // Vehicle comparison (only casual)
                $vehicleTypes = ['car', 'motorbike', 'truck', 'taxi'];
                $vehicleData = [];
                $totalLastWeek = 0;
                $totalThisWeek = 0;

                foreach ($vehicleTypes as $type) {
                    $lastWeekValue = $lastWeekCasualTotals['total_' . $type];
                    $thisWeekValue = $thisWeekCasualTotals['total_' . $type];

                    $totalLastWeek += $lastWeekValue;
                    $totalThisWeek += $thisWeekValue;

                    $percentChange = $lastWeekValue != 0 ? (($thisWeekValue - $lastWeekValue) / $lastWeekValue) * 100 : 0;
                    $direction = $percentChange >= 0 ? '↑' : '↓';
                    $color = $percentChange >= 0 ? 'green' : 'red';

                    $vehicleData[] = [
                        'vehicle' => ucfirst($type),
                        'last_week' => $lastWeekValue,
                        'this_week' => $thisWeekValue,
                        'percent_change' => number_format($percentChange, 1) . '%',
                        'direction' => $direction,
                        'color' => $color,
                    ];
                }

                // All Vehicle Total
                $percentChangeTotal = $totalLastWeek != 0 ? (($totalThisWeek - $totalLastWeek) / $totalLastWeek) * 100 : 0;
                $directionTotal = $percentChangeTotal >= 0 ? '↑' : '↓';
                $colorTotal = $percentChangeTotal >= 0 ? 'green' : 'red';

                $vehicleData[] = [
                    'vehicle' => 'All Vehicle',
                    'last_week' => $totalLastWeek,
                    'this_week' => $totalThisWeek,
                    'percent_change' => number_format($percentChangeTotal, 1) . '%',
                    'direction' => $directionTotal,
                    'color' => $colorTotal,
                ];

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Quantity Data for Period ' . $locationCode . ' - ' . $thisWeekEnd . ' (This Week) and ' . $lastWeekStart . ' - ' . $lastWeekEnd . ' (Last Week)',
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_week' => [
                        'casual' => $thisWeekCasual,
                        'pass' => $thisWeekPass,
                        'totals' => [
                            'casual' => $thisWeekCasualTotals,
                            'pass' => $thisWeekPassTotals,
                        ],
                    ],
                    'last_week' => [
                        'casual' => $lastWeekCasual,
                        'pass' => $lastWeekPass,
                        'totals' => [
                            'casual' => $lastWeekCasualTotals,
                            'pass' => $lastWeekPassTotals,
                        ],
                    ],
                    'vehicle_comparison' => $vehicleData,
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

            $response = Http::post('http://110.0.100.70:8080/v3/api/getquantity', [
                'effective_start_date' => $lastMonthStart,
                'effective_end_date' => $thisMonthEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'][0];

                $casualData = $data['casual'];
                $passData = $data['pass'];

                $thisMonthCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($thisMonthStart, $thisMonthEnd)
                )->toArray());

                $thisMonthPass = array_values(collect($passData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($thisMonthStart, $thisMonthEnd)
                )->toArray());

                $lastMonthCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($lastMonthStart, $lastMonthEnd)
                )->toArray());

                $lastMonthPass = array_values(collect($passData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($lastMonthStart, $lastMonthEnd)
                )->toArray());

                $thisMonthCasualTotals = $this->calculateTotals($thisMonthCasual, 'casual');
                $thisMonthPassTotals = $this->calculateTotals($thisMonthPass, 'pass');

                $lastMonthCasualTotals = $this->calculateTotals($lastMonthCasual, 'casual');
                $lastMonthPassTotals = $this->calculateTotals($lastMonthPass, 'pass');

                // VEHICLE COMPARISON (like weekly)
                $vehicleTypes = ['car', 'motorbike', 'truck', 'taxi'];
                $vehicleData = [];
                $totalLastMonth = 0;
                $totalThisMonth = 0;

                foreach ($vehicleTypes as $type) {
                    $lastMonthValue = $lastMonthCasualTotals['total_' . $type];
                    $thisMonthValue = $thisMonthCasualTotals['total_' . $type];

                    $totalLastMonth += $lastMonthValue;
                    $totalThisMonth += $thisMonthValue;

                    $percentChange = $lastMonthValue != 0 ? (($thisMonthValue - $lastMonthValue) / $lastMonthValue) * 100 : 0;
                    $direction = $percentChange >= 0 ? '↑' : '↓';
                    $color = $percentChange >= 0 ? 'green' : 'red';

                    $vehicleData[] = [
                        'vehicle' => ucfirst($type),
                        'last_month' => $lastMonthValue,
                        'this_month' => $thisMonthValue,
                        'percent_change' => number_format($percentChange, 1) . '%',
                        'direction' => $direction,
                        'color' => $color,
                    ];
                }

                $percentChangeTotal = $totalLastMonth != 0 ? (($totalThisMonth - $totalLastMonth) / $totalLastMonth) * 100 : 0;
                $directionTotal = $percentChangeTotal >= 0 ? '↑' : '↓';
                $colorTotal = $percentChangeTotal >= 0 ? 'green' : 'red';

                $vehicleData[] = [
                    'vehicle' => 'All Vehicle',
                    'last_month' => $totalLastMonth,
                    'this_month' => $totalThisMonth,
                    'percent_change' => number_format($percentChangeTotal, 1) . '%',
                    'direction' => $directionTotal,
                    'color' => $colorTotal,
                ];

                // Optional: Grouping per minggu
                $thisMonthCasualByWeek = $this->groupByWeek($thisMonthCasual);
                $thisMonthPassByWeek = $this->groupByWeek($thisMonthPass);

                $thisMonthCasualWeekTotals = [
                    'week 1' => $this->calculateTotals($thisMonthCasualByWeek['week 1'], 'casual'),
                    'week 2' => $this->calculateTotals($thisMonthCasualByWeek['week 2'], 'casual'),
                    'week 3' => $this->calculateTotals($thisMonthCasualByWeek['week 3'], 'casual'),
                    'week 4' => $this->calculateTotals($thisMonthCasualByWeek['week 4'], 'casual'),
                    'week 5' => $this->calculateTotals($thisMonthCasualByWeek['week 5'], 'casual'),
                ];

                $thisMonthPassWeekTotals = [
                    'week 1' => $this->calculateTotals($thisMonthPassByWeek['week 1'], 'pass'),
                    'week 2' => $this->calculateTotals($thisMonthPassByWeek['week 2'], 'pass'),
                    'week 3' => $this->calculateTotals($thisMonthPassByWeek['week 3'], 'pass'),
                    'week 4' => $this->calculateTotals($thisMonthPassByWeek['week 4'], 'pass'),
                    'week 5' => $this->calculateTotals($thisMonthPassByWeek['week 5'], 'pass'),
                ];

                return response()->json([
                    'response' => 'Success Get Monthly Data',
                    'message' => 'Get Quantity Data for ' . $locationCode . ' - This Month: ' . $thisMonthStart . ' to ' . $thisMonthEnd . ' | Last Month: ' . $lastMonthStart . ' to ' . $lastMonthEnd,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_month' => [
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
                        'totals' => [
                            'casual' => $lastMonthCasualTotals,
                            'pass' => $lastMonthPassTotals,
                        ],
                    ],
                    'vehicle_comparison' => $vehicleData,
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
            'week 1' => [],
            'week 2' => [],
            'week 3' => [],
            'week 4' => [],
            'week 5' => [],
        ];

        foreach ($data as $item) {
            $day = Carbon::parse($item['tanggal'])->day;

            if ($day >= 1 && $day <= 7) {
                $weeks['week 1'][] = $item;
            } elseif ($day >= 8 && $day <= 14) {
                $weeks['week 2'][] = $item;
            } elseif ($day >= 15 && $day <= 21) {
                $weeks['week 3'][] = $item;
            } elseif ($day >= 22 && $day <= 28) {
                $weeks['week 4'][] = $item;
            } else {
                $weeks['week 5'][] = $item;
            }
        }

        return $weeks;
    }
}
