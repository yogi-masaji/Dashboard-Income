<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\CssSelector\Node\FunctionNode;



class IncomeController extends Controller
{
    public function incomePage()
    {
        return view('pages.income');
    }

    public function getDailyIncome()
    {
        try {
            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::get("http://110.0.100.135:8081/v3/api/daily-income", [
                'location_code' => $locationCode
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $data = $responseData['data'][0];

                $todayData = collect($data['today'][0] ?? []);
                $yesterdayData = collect($data['yesterday'][0] ?? []);

                $comparison = $this->formatDailyIncomeTable($todayData, $yesterdayData);
                $data['table_data'] = $comparison; // <<< INI AJA YANG DITAMBAH

                return response()->json([
                    'response' => $responseData['response'],
                    'message' => $responseData['message'],
                    'status_code' => $responseData['status_code'],
                    'location_code' => $responseData['location_code'],
                    'lastupdate' => $responseData['lastupdate'],
                    'data' => [$data] // tetep array
                ]);
            }

            return response()->json(['message' => 'Failed to fetch data from API'], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }






    public function weeklyIncome()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            $thisWeekStart = $today->copy()->subDays(6)->format('Y-m-d');
            $thisWeekEnd = $today->format('Y-m-d');

            $lastWeekStart = $today->copy()->subDays(13)->format('Y-m-d');
            $lastWeekEnd = $today->copy()->subDays(7)->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getincome', [
                'effective_start_date' => $lastWeekStart,
                'effective_end_date' => $thisWeekEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $incomeData = $response->json()['data'];

                $thisWeekData = collect($incomeData)->filter(function ($item) use ($thisWeekStart, $thisWeekEnd) {
                    return Carbon::parse($item['tanggal'])->between($thisWeekStart, $thisWeekEnd);
                })->values();

                $lastWeekData = collect($incomeData)->filter(function ($item) use ($lastWeekStart, $lastWeekEnd) {
                    return Carbon::parse($item['tanggal'])->between($lastWeekStart, $lastWeekEnd);
                })->values();

                // Hitung total income mingguan
                $thisWeekTotals = $this->calculateIncomeTotals($thisWeekData);
                $lastWeekTotals = $this->calculateIncomeTotals($lastWeekData);

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Income Data Period ' . $lastWeekStart . ' - ' . $lastWeekEnd . ' (Last Week) and ' . $thisWeekStart . ' - ' . $thisWeekEnd . ' (This Week)',
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_week' => [
                        'data' => $thisWeekData,
                        'totals' => $thisWeekTotals,
                    ],
                    'last_week' => [
                        'data' => $lastWeekData,
                        'totals' => $lastWeekTotals,
                    ],
                    'table_data' => $this->formatIncomeTable($thisWeekTotals, $lastWeekTotals),
                ]);
            }

            return response()->json(['message' => 'Failed to fetch data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function MonthlyIncome()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            $thisMonthStart = $today->copy()->startOfMonth()->format('Y-m-d');
            $thisMonthEnd = $today->format('Y-m-d');

            $lastMonthStart = $today->copy()->subMonth()->startOfMonth()->format('Y-m-d');
            $lastMonthEnd = $today->copy()->subMonth()->endOfMonth()->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getincome', [
                'effective_start_date' => $lastMonthStart,
                'effective_end_date' => $thisMonthEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $incomeData = $response->json()['data'];

                $thisMonthData = collect($incomeData)->filter(function ($item) use ($thisMonthStart, $thisMonthEnd) {
                    return Carbon::parse($item['tanggal'])->between($thisMonthStart, $thisMonthEnd);
                })->values();

                $lastMonthData = collect($incomeData)->filter(function ($item) use ($lastMonthStart, $lastMonthEnd) {
                    return Carbon::parse($item['tanggal'])->between($lastMonthStart, $lastMonthEnd);
                })->values();

                $thisMonthWeekly = $this->groupByWeek($thisMonthData);
                $lastMonthWeekly = $this->groupByWeek($lastMonthData);
                $thisMonthWeeklyTotals = collect($thisMonthWeekly)->map(function ($weekData) {
                    return $this->calculateIncomeTotals(collect($weekData));
                });

                $lastMonthWeeklyTotals = collect($lastMonthWeekly)->map(function ($weekData) {
                    return $this->calculateIncomeTotals(collect($weekData));
                });

                $thisMonthTotals = $this->calculateIncomeTotals($thisMonthData);
                $lastMonthTotals = $this->calculateIncomeTotals($lastMonthData);

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Income Data Period ' . $lastMonthStart . ' - ' . $lastMonthEnd . ' (Last Month) and ' . $thisMonthStart . ' - ' . $thisMonthEnd . ' (This Month)',
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_Month' => [

                        'totals' => $thisMonthTotals,
                        'weekly_totals' => $thisMonthWeeklyTotals,
                    ],
                    'last_Month' => [

                        'totals' => $lastMonthTotals,
                        'weekly_totals' => $lastMonthWeeklyTotals,
                    ],
                    'table_data' => $this->formatMonthlyIncomeTable($thisMonthTotals, $lastMonthTotals),

                ]);
            }

            return response()->json(['message' => 'Failed to fetch data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }


    private function calculateIncomeTotals($data)
    {
        return [
            'carincome' => $data->sum('carincome'),
            'motorbikeincome' => $data->sum('motorbikeincome'),
            'taxiincome' => $data->sum('taxiincome'),
            'truckincome' => $data->sum('truckincome'),
            'otherincome' => $data->sum('otherincome'),
            'vehicleincome' => $data->sum(function ($item) {
                return (float) $item['vehicleincome'];
            }),
            'ticketincome' => $data->sum('ticketincome'),
            'stickerincome' => $data->sum('stickerincome'),
            'totalnetSales' => $data->sum('totalnetSales'),
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

    private function formatIncomeTable($thisWeekTotals, $lastWeekTotals)
    {
        $vehicles = [
            'Car' => 'carincome',
            'Motorbike' => 'motorbikeincome',
            'Truck' => 'truckincome',
            'Taxi' => 'taxiincome',
            'Lost Ticket' => 'ticketincome',
            'Other' => 'otherincome',
            'All Casual Income' => 'vehicleincome',
            'All Sticker Income' => 'stickerincome',
        ];

        $result = [];

        foreach ($vehicles as $vehicleName => $key) {
            $last = $lastWeekTotals[$key] ?? 0;
            $current = $thisWeekTotals[$key] ?? 0;

            if ($last == 0 && $current == 0) {
                $percentChange = '0.0%';
                $direction = '-';
                $color = 'gray';
            } elseif ($last == 0) {
                $percentChange = '+100%';
                $direction = '↑';
                $color = 'green';
            } else {
                $diff = $current - $last;
                $percent = ($diff / $last) * 100;
                $percentFormatted = number_format(abs($percent), 1) . '%';
                $direction = $percent > 0 ? '↑' : '↓';
                $color = $percent > 0 ? 'green' : 'red';
                $percentChange = ($percent > 0 ? '+' : '-') . $percentFormatted;
            }

            $result[] = [
                'vehicle' => $vehicleName,
                'last_week' => $last,
                'this_week' => $current,
                'percent_change' => $percentChange,
                'direction' => $direction,
                'color' => $color,
            ];
        }

        return $result;
    }

    private function formatMonthlyIncomeTable($thisMonthTotals, $lastMonthTotals)
    {
        $vehicles = [
            'Car' => 'carincome',
            'Motorbike' => 'motorbikeincome',
            'Truck' => 'truckincome',
            'Taxi' => 'taxiincome',
            'Lost Ticket' => 'ticketincome',
            'Other' => 'otherincome',
            'All Casual Income' => 'vehicleincome',
            'All Sticker Income' => 'stickerincome',
        ];

        $result = [];

        foreach ($vehicles as $vehicleName => $key) {
            $last = $lastMonthTotals[$key] ?? 0;
            $current = $thisMonthTotals[$key] ?? 0;

            if ($last == 0 && $current == 0) {
                $percentChange = '0.0%';
                $direction = '-';
                $color = 'gray';
            } elseif ($last == 0) {
                $percentChange = '+100%';
                $direction = '↑';
                $color = 'green';
            } else {
                $diff = $current - $last;
                $percent = ($diff / $last) * 100;
                $percentFormatted = number_format(abs($percent), 1) . '%';
                $direction = $percent > 0 ? '↑' : '↓';
                $color = $percent > 0 ? 'green' : 'red';
                $percentChange = ($percent > 0 ? '+' : '-') . $percentFormatted;
            }

            $result[] = [
                'vehicle' => $vehicleName,
                'last_month' => $last,
                'this_month' => $current,
                'percent_change' => $percentChange,
                'direction' => $direction,
                'color' => $color,
            ];
        }

        return $result;
    }

    private function formatDailyIncomeTable($today, $yesterday)
    {
        $vehicles = [
            'Car' => 'carincome',
            'Motorbike' => 'motorbikeincome',
            'Truck' => 'truckincome',
            'Taxi' => 'taxiincome',
            'Lost Ticket' => 'ticketincome',
            'Other' => 'otherincome',
            'All Sticker Income' => 'stickerincome',
            'All Vehicle' => 'grandtotal',
        ];

        $result = [];

        foreach ($vehicles as $vehicleName => $key) {
            $yesterdayVal = isset($yesterday[$key]) ? (float) $yesterday[$key] : 0;
            $todayVal = isset($today[$key]) ? (float) $today[$key] : 0;

            if ($yesterdayVal == 0 && $todayVal == 0) {
                $percentChange = '0.0%';
                $direction = '-';
                $color = 'gray';
            } elseif ($yesterdayVal == 0) {
                $percentChange = '+100%';
                $direction = '↑';
                $color = 'green';
            } else {
                $diff = $todayVal - $yesterdayVal;
                $percent = ($diff / $yesterdayVal) * 100;
                $percentFormatted = number_format(abs($percent), 1) . '%';
                $direction = $percent > 0 ? '↑' : '↓';
                $color = $percent > 0 ? 'green' : 'red';
                $percentChange = ($percent > 0 ? '+' : '-') . $percentFormatted;
            }

            $result[] = [
                'vehicle' => $vehicleName,
                'yesterday' => $yesterdayVal,
                'today' => $todayVal,
                'percent_change' => $percentChange,
                'direction' => $direction,
                'color' => $color,
            ];
        }

        return $result;
    }
}
