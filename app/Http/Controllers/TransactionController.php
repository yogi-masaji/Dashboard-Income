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

            // Last week: 7 hari sebelum this week
            $lastWeekStart = $today->copy()->subDays(13)->format('Y-m-d');
            $lastWeekEnd = $today->copy()->subDays(7)->format('Y-m-d');

            // Two weeks ago: 7 hari sebelum last week
            $twoWeeksAgoStart = $today->copy()->subDays(20)->format('Y-m-d');
            $twoWeeksAgoEnd = $today->copy()->subDays(14)->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getquantity', [
                'effective_start_date' => $twoWeeksAgoStart,
                'effective_end_date' => $thisWeekEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'][0];

                $casualData = $data['casual'];
                $passData = $data['pass'];

                // Filter data by week ranges
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

                $twoWeeksAgoCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($twoWeeksAgoStart, $twoWeeksAgoEnd)
                )->toArray());

                // Calculate totals
                $thisWeekCasualTotals = $this->calculateTotals($thisWeekCasual, 'casual');
                $lastWeekCasualTotals = $this->calculateTotals($lastWeekCasual, 'casual');
                $twoWeeksAgoCasualTotals = $this->calculateTotals($twoWeeksAgoCasual, 'casual');

                $thisWeekPassTotals = $this->calculateTotals($thisWeekPass, 'pass');
                $lastWeekPassTotals = $this->calculateTotals($lastWeekPass, 'pass');

                // Vehicle comparison (only casual), comparing this week vs two weeks ago
                $vehicleTypes = ['car', 'motorbike', 'truck', 'taxi'];
                $vehicleData = [];
                $totalTwoWeeksAgo = 0;
                $totalThisWeek = 0;

                foreach ($vehicleTypes as $type) {
                    $twoWeeksAgoValue = $twoWeeksAgoCasualTotals['total_' . $type];
                    $thisWeekValue = $thisWeekCasualTotals['total_' . $type];

                    $totalTwoWeeksAgo += $twoWeeksAgoValue;
                    $totalThisWeek += $thisWeekValue;

                    $percentChange = $twoWeeksAgoValue != 0 ? (($thisWeekValue - $twoWeeksAgoValue) / $twoWeeksAgoValue) * 100 : 0;
                    $direction = $percentChange >= 0 ? '↑' : '↓';
                    $color = $percentChange >= 0 ? 'green' : 'red';

                    $vehicleData[] = [
                        'vehicle' => ucfirst($type),
                        'two_weeks_ago' => $twoWeeksAgoValue,
                        'this_week' => $thisWeekValue,
                        'percent_change' => number_format($percentChange, 1) . '%',
                        'direction' => $direction,
                        'color' => $color,
                    ];
                }

                // All Vehicle Total
                $percentChangeTotal = $totalTwoWeeksAgo != 0 ? (($totalThisWeek - $totalTwoWeeksAgo) / $totalTwoWeeksAgo) * 100 : 0;
                $directionTotal = $percentChangeTotal >= 0 ? '↑' : '↓';
                $colorTotal = $percentChangeTotal >= 0 ? 'green' : 'red';

                $vehicleData[] = [
                    'vehicle' => 'All Vehicle',
                    'two_weeks_ago' => $totalTwoWeeksAgo,
                    'this_week' => $totalThisWeek,
                    'percent_change' => number_format($percentChangeTotal, 1) . '%',
                    'direction' => $directionTotal,
                    'color' => $colorTotal,
                ];

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Quantity Data for Period ' . $locationCode . ' - ' . $thisWeekEnd . ' (This Week)',
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

            // Tanggal untuk bulan ini
            $thisMonthStart = $today->copy()->startOfMonth()->format('Y-m-d');
            $thisMonthEnd = $today->format('Y-m-d');

            // Tanggal untuk bulan lalu
            $lastMonthStart = $today->copy()->subMonth()->startOfMonth()->format('Y-m-d');
            $lastMonthEnd = $today->copy()->subMonth()->endOfMonth()->format('Y-m-d');

            // Tanggal untuk dua bulan lalu (khusus untuk vehicle_comparison)
            $twoMonthsAgoStart = $today->copy()->subMonths(2)->startOfMonth()->format('Y-m-d');
            $twoMonthsAgoEnd = $today->copy()->subMonths(2)->endOfMonth()->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getquantity', [
                'effective_start_date' => $twoMonthsAgoStart,
                'effective_end_date' => $thisMonthEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'][0];

                $casualData = $data['casual'];
                $passData = $data['pass'];

                // Filter data sesuai bulan
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

                $twoMonthsAgoCasual = array_values(collect($casualData)->filter(
                    fn($item) => Carbon::parse($item['tanggal'])->between($twoMonthsAgoStart, $twoMonthsAgoEnd)
                )->toArray());

                // Hitung total
                $thisMonthCasualTotals = $this->calculateTotals($thisMonthCasual, 'casual');
                $thisMonthPassTotals = $this->calculateTotals($thisMonthPass, 'pass');
                $lastMonthCasualTotals = $this->calculateTotals($lastMonthCasual, 'casual');
                $lastMonthPassTotals = $this->calculateTotals($lastMonthPass, 'pass');
                $twoMonthsAgoCasualTotals = $this->calculateTotals($twoMonthsAgoCasual, 'casual');

                // Vehicle comparison: 2 bulan lalu vs bulan ini
                $vehicleTypes = ['car', 'motorbike', 'truck', 'taxi'];
                $vehicleData = [];
                $totalTwoMonthsAgo = 0;
                $totalThisMonth = 0;

                foreach ($vehicleTypes as $type) {
                    $twoMonthsAgoValue = $twoMonthsAgoCasualTotals['total_' . $type];
                    $thisMonthValue = $thisMonthCasualTotals['total_' . $type];

                    $totalTwoMonthsAgo += $twoMonthsAgoValue;
                    $totalThisMonth += $thisMonthValue;

                    $percentChange = $twoMonthsAgoValue != 0 ? (($thisMonthValue - $twoMonthsAgoValue) / $twoMonthsAgoValue) * 100 : 0;
                    $direction = $percentChange >= 0 ? '↑' : '↓';
                    $color = $percentChange >= 0 ? 'green' : 'red';

                    $vehicleData[] = [
                        'vehicle' => ucfirst($type),
                        'two_months_ago' => $twoMonthsAgoValue,
                        'this_month' => $thisMonthValue,
                        'percent_change' => number_format($percentChange, 1) . '%',
                        'direction' => $direction,
                        'color' => $color,
                    ];
                }

                $percentChangeTotal = $totalTwoMonthsAgo != 0 ? (($totalThisMonth - $totalTwoMonthsAgo) / $totalTwoMonthsAgo) * 100 : 0;
                $directionTotal = $percentChangeTotal >= 0 ? '↑' : '↓';
                $colorTotal = $percentChangeTotal >= 0 ? 'green' : 'red';

                $vehicleData[] = [
                    'vehicle' => 'All Vehicle',
                    'two_months_ago' => $totalTwoMonthsAgo,
                    'this_month' => $totalThisMonth,
                    'percent_change' => number_format($percentChangeTotal, 1) . '%',
                    'direction' => $directionTotal,
                    'color' => $colorTotal,
                ];

                // Optional: Grouping per minggu
                $thisMonthCasualByWeek = $this->groupByWeek($thisMonthCasual);
                $thisMonthPassByWeek = $this->groupByWeek($thisMonthPass);

                $thisMonthCasualWeekTotals = [];
                $thisMonthPassWeekTotals = [];

                for ($i = 1; $i <= 5; $i++) {
                    $thisMonthCasualWeekTotals["week $i"] = $this->calculateTotals($thisMonthCasualByWeek["week $i"] ?? [], 'casual');
                    $thisMonthPassWeekTotals["week $i"] = $this->calculateTotals($thisMonthPassByWeek["week $i"] ?? [], 'pass');
                }

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
