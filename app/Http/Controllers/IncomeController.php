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

            $response = Http::timeout(0)->get("http://110.0.100.70:8080/v3/api/daily-income", [
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
            // --- LOGIKA TANGGAL BARU DENGAN SIKLUS ---
            $today = Carbon::now()->timezone('Asia/Jakarta');

            // Dapatkan siklus untuk minggu ini
            $thisWeekCycle = $this->getWeekCycle($today);
            $thisWeekStart = $thisWeekCycle['start'];
            // Tanggal akhir adalah hari ini, tetapi tidak bisa melebihi akhir siklus
            $thisWeekEnd = $today->min($thisWeekCycle['end']);

            // Dapatkan siklus untuk minggu lalu
            $dateForLastWeek = $thisWeekStart->copy()->subDay(); // Mundur satu hari untuk masuk ke siklus sebelumnya
            $lastWeekCycle = $this->getWeekCycle($dateForLastWeek);
            $lastWeekStart = $lastWeekCycle['start'];
            $lastWeekEnd = $lastWeekCycle['end'];

            // Dapatkan siklus untuk dua minggu lalu
            $dateForTwoWeeksAgo = $lastWeekStart->copy()->subDay(); // Mundur satu hari dari awal siklus minggu lalu
            $twoWeeksAgoCycle = $this->getWeekCycle($dateForTwoWeeksAgo);
            $twoWeeksAgoStart = $twoWeeksAgoCycle['start'];
            $twoWeeksAgoEnd = $twoWeeksAgoCycle['end'];

            // Ambil data dari API untuk keseluruhan rentang tanggal
            $apiStartDate = $twoWeeksAgoStart->format('Y-m-d');
            $apiEndDate = $thisWeekEnd->format('Y-m-d');
            // --- AKHIR LOGIKA TANGGAL BARU ---

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getincome', [
                'effective_start_date' => $apiStartDate,
                'effective_end_date' => $apiEndDate,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $incomeData = $response->json()['data'];

                // Filter data berdasarkan rentang tanggal yang sudah dihitung
                $thisWeekData = collect($incomeData)->filter(fn($item) => Carbon::parse($item['tanggal'])->betweenIncluded($thisWeekStart, $thisWeekEnd))->values();
                $lastWeekData = collect($incomeData)->filter(fn($item) => Carbon::parse($item['tanggal'])->betweenIncluded($lastWeekStart, $lastWeekEnd))->values();
                $twoWeeksAgoData = collect($incomeData)->filter(fn($item) => Carbon::parse($item['tanggal'])->betweenIncluded($twoWeeksAgoStart, $twoWeeksAgoEnd))->values();

                // Hitung total untuk setiap periode
                $thisWeekTotals = $this->calculateIncomeTotals($thisWeekData);
                $lastWeekTotals = $this->calculateIncomeTotals($lastWeekData);
                $twoWeeksAgoTotals = $this->calculateIncomeTotals($twoWeeksAgoData);

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Income Data for Period ' . $locationCode,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_week' => [
                        'period' => $thisWeekStart->format('d M') . ' - ' . $thisWeekEnd->format('d M'),
                        'data' => $thisWeekData,
                        'totals' => $thisWeekTotals,
                    ],
                    'last_week' => [
                        'period' => $lastWeekStart->format('d M') . ' - ' . $lastWeekEnd->format('d M'),
                        'data' => $lastWeekData,
                        'totals' => $lastWeekTotals,
                    ],
                    'two_weeks_ago' => [
                        'period' => $twoWeeksAgoStart->format('d M') . ' - ' . $twoWeeksAgoEnd->format('d M'),
                        'data' => $twoWeeksAgoData,
                        'totals' => $twoWeeksAgoTotals,
                    ],
                    'table_data' => $this->formatIncomeTable($lastWeekTotals, $twoWeeksAgoTotals), // perbandingan last week vs two weeks ago
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
            $thisMonthEnd = $today->copy()->endOfMonth()->format('Y-m-d');

            // FIX: Menggunakan subMonthNoOverflow() untuk menghindari bug tanggal.
            $lastMonthStart = $today->copy()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d');
            $lastMonthEnd = $today->copy()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');

            // FIX: Menggunakan subMonthsNoOverflow() untuk menghindari bug tanggal.
            $twoMonthsAgoStart = $today->copy()->subMonthsNoOverflow(2)->startOfMonth()->format('Y-m-d');
            $twoMonthsAgoEnd = $today->copy()->subMonthsNoOverflow(2)->endOfMonth()->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getincome', [
                'effective_start_date' => $twoMonthsAgoStart,
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

                $twoMonthsAgoData = collect($incomeData)->filter(function ($item) use ($twoMonthsAgoStart, $twoMonthsAgoEnd) {
                    return Carbon::parse($item['tanggal'])->between($twoMonthsAgoStart, $twoMonthsAgoEnd);
                })->values();

                $thisMonthWeekly = $this->groupByWeek($thisMonthData);
                $lastMonthWeekly = $this->groupByWeek($lastMonthData);
                $twoMonthsAgoWeekly = $this->groupByWeek($twoMonthsAgoData);

                $thisMonthWeeklyTotals = collect($thisMonthWeekly)->map(function ($weekData) {
                    return $this->calculateIncomeTotals(collect($weekData));
                });

                $lastMonthWeeklyTotals = collect($lastMonthWeekly)->map(function ($weekData) {
                    return $this->calculateIncomeTotals(collect($weekData));
                });

                $twoMonthsAgoWeeklyTotals = collect($twoMonthsAgoWeekly)->map(function ($weekData) {
                    return $this->calculateIncomeTotals(collect($weekData));
                });

                $thisMonthTotals = $this->calculateIncomeTotals($thisMonthData);
                $lastMonthTotals = $this->calculateIncomeTotals($lastMonthData);
                $twoMonthsAgoTotals = $this->calculateIncomeTotals($twoMonthsAgoData);

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Compare Income Data: ' . $twoMonthsAgoStart . ' - ' . $twoMonthsAgoEnd . ' (Two Months Ago) vs ' . $lastMonthStart . ' - ' . $lastMonthEnd . ' (Last Month)',
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
                    'two_Months_Ago' => [
                        'totals' => $twoMonthsAgoTotals,
                        'weekly_totals' => $twoMonthsAgoWeeklyTotals,
                    ],
                    'table_data' => $this->formatMonthlyIncomeTable($lastMonthTotals, $twoMonthsAgoTotals),
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

    private function formatIncomeTable($lastWeekTotals, $twoWeeksAgoTotals)
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
            $previous = $twoWeeksAgoTotals[$key] ?? 0;
            $current = $lastWeekTotals[$key] ?? 0;

            if ($previous == 0 && $current == 0) {
                $percentChange = '0.0%';
                $direction = '-';
                $color = 'gray';
            } elseif ($previous == 0) {
                $percentChange = '+100%';
                $direction = '↑';
                $color = 'green';
            } else {
                $diff = $current - $previous;
                $percent = ($diff / $previous) * 100;
                $percentFormatted = number_format(abs($percent), 1) . '%';
                $direction = $percent > 0 ? '↑' : '↓';
                $color = $percent > 0 ? 'green' : 'red';
                $percentChange = ($percent > 0 ? '+' : '-') . $percentFormatted;
            }

            $result[] = [
                'vehicle' => $vehicleName,
                'last_week' => $previous,
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

    /**
     * Helper function to get the weekly cycle (1-7, 8-14, etc.) for a given date.
     * @param Carbon $date
     * @return array
     */
    private function getWeekCycle(Carbon $date)
    {
        $dayOfMonth = $date->day;
        $month = $date->month;
        $year = $date->year;

        if ($dayOfMonth <= 7) {
            $start = Carbon::create($year, $month, 1);
            $end = Carbon::create($year, $month, 7);
        } elseif ($dayOfMonth <= 14) {
            $start = Carbon::create($year, $month, 8);
            $end = Carbon::create($year, $month, 14);
        } elseif ($dayOfMonth <= 21) {
            $start = Carbon::create($year, $month, 15);
            $end = Carbon::create($year, $month, 21);
        } elseif ($dayOfMonth <= 28) {
            $start = Carbon::create($year, $month, 22);
            $end = Carbon::create($year, $month, 28);
        } else { // For dates 29, 30, or 31
            $start = Carbon::create($year, $month, 29);
            $end = $date->copy()->endOfMonth(); // The end is the actual end of the month
        }

        return ['start' => $start->startOfDay(), 'end' => $end->endOfDay()];
    }
}
