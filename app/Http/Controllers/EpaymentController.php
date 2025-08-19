<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\CssSelector\Node\FunctionNode;

class EpaymentController extends Controller
{

    public function dailyEpayment()
    {
        try {
            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::get('http://110.0.100.70:8080/v3/api/daily-epayment', [
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $todayData = $data['data'][0]['today'][0] ?? [];
                $yesterdayData = $data['data'][0]['yesterday'][0] ?? [];

                $payments = [
                    'Flazz' => 'flazzpayment',
                    'TapCash' => 'tapcashpayment',
                    'Brizzi' => 'brizzipayment',
                    'Cash' => 'cashpayment',
                    'e-Money' => 'emoneypayment',
                    'Parkee' => 'parkeepayment',
                    'All Payments' => 'grandtotal',
                ];

                $result = [];

                foreach ($payments as $label => $key) {
                    $yesterday = (float) ($yesterdayData[$key] ?? 0);
                    $today = (float) ($todayData[$key] ?? 0);

                    // Calculate percent change, direction, and color
                    if ($yesterday == 0 && $today == 0) {
                        $percentChange = '0.0%';
                        $direction = '-';
                        $color = 'gray';
                    } elseif ($yesterday == 0) {
                        $percentChange = '+100%';
                        $direction = '↑';
                        $color = 'green';
                    } else {
                        $diff = $today - $yesterday;
                        $percent = ($diff / $yesterday) * 100;
                        $percentFormatted = number_format(abs($percent), 1) . '%';
                        $direction = $percent > 0 ? '↑' : '↓';
                        $color = $percent > 0 ? 'green' : 'red';
                        $percentChange = ($percent > 0 ? '+' : '-') . $percentFormatted;
                    }

                    // Store the result
                    $result[] = [
                        'method' => $label,
                        'yesterday' => $yesterday,
                        'today' => $today,
                        'percent_change' => $percentChange,
                        'direction' => $direction,
                        'color' => $color,
                    ];
                }

                // Add the comparison results to the original response
                $data['table_data'] = $result;

                return response()->json($data);
            }

            return response()->json(['message' => 'Failed to fetch daily epayment data'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    private function formatDailyEpaymentTable($todayTotals, $yesterdayTotals)
    {
        $payments = [
            'Flazz' => 'flazzpayment',
            'TapCash' => 'tapcashpayment',
            'Brizzi' => 'brizzipayment',
            'Cash' => 'cashpayment',
            'e-Money' => 'emoneypayment',
            'Parkee' => 'parkeepayment',
            'All Payments' => 'grandtotal',
        ];

        $result = [];

        foreach ($payments as $label => $key) {
            $yesterday = (float) ($yesterdayTotals[$key] ?? 0);
            $today = (float) ($todayTotals[$key] ?? 0);

            if ($yesterday == 0 && $today == 0) {
                $percentChange = '0.0%';
                $direction = '-';
                $color = 'gray';
            } elseif ($yesterday == 0) {
                $percentChange = '+100%';
                $direction = '↑';
                $color = 'green';
            } else {
                $diff = $today - $yesterday;
                $percent = ($diff / $yesterday) * 100;
                $percentFormatted = number_format(abs($percent), 1) . '%';
                $direction = $percent > 0 ? '↑' : '↓';
                $color = $percent > 0 ? 'green' : 'red';
                $percentChange = ($percent > 0 ? '+' : '-') . $percentFormatted;
            }

            $result[] = [
                'method' => $label,
                'yesterday' => $yesterday,
                'today' => $today,
                'percent_change' => $percentChange,
                'direction' => $direction,
                'color' => $color,
            ];
        }

        return $result;
    }

    public function weeklyEpayment()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            // --- DATE LOGIC FOR MAIN DATA (CYCLE-BASED) ---
            $thisWeekCycle = $this->getWeekCycle($today);
            $thisWeekStart = $thisWeekCycle['start'];
            $thisWeekEnd = $today->min($thisWeekCycle['end']);

            $dateForLastWeek = $thisWeekStart->copy()->subDay();
            $lastWeekCycle = $this->getWeekCycle($dateForLastWeek);
            $lastWeekStart = $lastWeekCycle['start'];
            $lastWeekEnd = $lastWeekCycle['end'];

            $dateForTwoWeeksAgo = $lastWeekStart->copy()->subDay();
            $twoWeeksAgoCycle = $this->getWeekCycle($dateForTwoWeeksAgo);
            $twoWeeksAgoStart = $twoWeeksAgoCycle['start'];
            $twoWeeksAgoEnd = $twoWeeksAgoCycle['end'];
            // --- END OF CYCLE-BASED DATE LOGIC ---

            // --- NEW DATE LOGIC FOR TABLE_DATA COMPARISON (MONDAY-SUNDAY) ---
            $lastWeekCompareStart = $today->copy()->subWeek()->startOfWeek(Carbon::MONDAY);
            $lastWeekCompareEnd = $today->copy()->subWeek()->endOfWeek(Carbon::SUNDAY);

            $twoWeeksAgoCompareStart = $today->copy()->subWeeks(2)->startOfWeek(Carbon::MONDAY);
            $twoWeeksAgoCompareEnd = $today->copy()->subWeeks(2)->endOfWeek(Carbon::SUNDAY);
            // --- END OF MONDAY-SUNDAY DATE LOGIC ---

            // Determine the earliest date needed for the API call.
            $apiStartDate = min($twoWeeksAgoStart, $twoWeeksAgoCompareStart)->format('Y-m-d');
            $apiEndDate = $thisWeekEnd->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getepayment', [
                'effective_start_date' => $apiStartDate,
                'effective_end_date' => $apiEndDate,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $epaymentData = collect($response->json()['data']);

                // --- FILTERING FOR MAIN DATA (CYCLE-BASED) ---
                $thisWeekData = $epaymentData->filter(fn($item) => isset($item['tanggal']) && Carbon::parse($item['tanggal'])->betweenIncluded($thisWeekStart, $thisWeekEnd))->values();
                $lastWeekData = $epaymentData->filter(fn($item) => isset($item['tanggal']) && Carbon::parse($item['tanggal'])->betweenIncluded($lastWeekStart, $lastWeekEnd))->values();
                $twoWeeksAgoData = $epaymentData->filter(fn($item) => isset($item['tanggal']) && Carbon::parse($item['tanggal'])->betweenIncluded($twoWeeksAgoStart, $twoWeeksAgoEnd))->values();

                // --- CALCULATE TOTALS FOR MAIN DATA (CYCLE-BASED) ---
                $thisWeekTotals = $this->calculateEpaymentTotals($thisWeekData);
                $lastWeekTotals = $this->calculateEpaymentTotals($lastWeekData);
                $twoWeeksAgoTotals = $this->calculateEpaymentTotals($twoWeeksAgoData);

                // --- NEW: FILTERING & TOTALS FOR TABLE_DATA (MONDAY-SUNDAY) ---
                $lastWeekCompareData = $epaymentData->filter(fn($item) => isset($item['tanggal']) && Carbon::parse($item['tanggal'])->betweenIncluded($lastWeekCompareStart, $lastWeekCompareEnd))->values();
                $twoWeeksAgoCompareData = $epaymentData->filter(fn($item) => isset($item['tanggal']) && Carbon::parse($item['tanggal'])->betweenIncluded($twoWeeksAgoCompareStart, $twoWeeksAgoCompareEnd))->values();

                $lastWeekCompareTotals = $this->calculateEpaymentTotals($lastWeekCompareData);
                $twoWeeksAgoCompareTotals = $this->calculateEpaymentTotals($twoWeeksAgoCompareData);
                // --- END OF NEW LOGIC ---

                return response()->json([
                    'response' => 'Success Get Epayment Data',
                    'message' => 'Epayment Weekly Data for ' . $locationCode,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_week' => [
                        'period' => $thisWeekStart->format('d M') . ' - ' . $thisWeekEnd->format('d M'),
                        'data' => $thisWeekData->toArray(),
                        'totals' => $thisWeekTotals,
                    ],
                    'last_week' => [
                        'period' => $lastWeekStart->format('d M') . ' - ' . $lastWeekEnd->format('d M'),
                        'data' => $lastWeekData->toArray(),
                        'totals' => $lastWeekTotals,
                    ],
                    'two_weeks_ago' => [
                        'period' => $twoWeeksAgoStart->format('d M') . ' - ' . $twoWeeksAgoEnd->format('d M'),
                        'data' => $twoWeeksAgoData->toArray(),
                        'totals' => $twoWeeksAgoTotals,
                    ],
                    // This now uses the Monday-Sunday totals for comparison.
                    'table_data' => $this->formatEpaymentTable($lastWeekCompareTotals, $twoWeeksAgoCompareTotals),
                ]);
            }

            return response()->json(['message' => 'Failed to fetch epayment data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function monthlyEpayment()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            $thisMonthStart = $today->copy()->startOfMonth()->format('Y-m-d');
            $thisMonthEnd = $today->copy()->endOfMonth()->format('Y-m-d');

            $lastMonthStart = $today->copy()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d');
            $lastMonthEnd = $today->copy()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d');

            $twoMonthsAgoStart = $today->copy()->subMonthsNoOverflow(2)->startOfMonth()->format('Y-m-d');
            $twoMonthsAgoEnd = $today->copy()->subMonthsNoOverflow(2)->endOfMonth()->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getepayment', [
                'effective_start_date' => $twoMonthsAgoStart,
                'effective_end_date' => $thisMonthEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $epaymentData = collect($response->json()['data']);

                $thisMonthData = $epaymentData->filter(function ($item) use ($thisMonthStart, $thisMonthEnd) {
                    return Carbon::parse($item['tanggal'])->between($thisMonthStart, $thisMonthEnd);
                })->values();

                $lastMonthData = $epaymentData->filter(function ($item) use ($lastMonthStart, $lastMonthEnd) {
                    return Carbon::parse($item['tanggal'])->between($lastMonthStart, $lastMonthEnd);
                })->values();

                $twoMonthsAgoData = $epaymentData->filter(function ($item) use ($twoMonthsAgoStart, $twoMonthsAgoEnd) {
                    return Carbon::parse($item['tanggal'])->between($twoMonthsAgoStart, $twoMonthsAgoEnd);
                })->values();

                $thisMonthWeekly = $this->groupByWeek($thisMonthData);
                $lastMonthWeekly = $this->groupByWeek($lastMonthData);
                $twoMonthsAgoWeekly = $this->groupByWeek($twoMonthsAgoData);
                $thisMonthWeeklyTotals = collect($thisMonthWeekly)->map(function ($weekData) {
                    return $this->calculateEpaymentTotals(collect($weekData));
                });

                $lastMonthWeeklyTotals = collect($lastMonthWeekly)->map(function ($weekData) {
                    return $this->calculateEpaymentTotals(collect($weekData));
                });

                $twoMonthsAgoWeeklyTotals = collect($twoMonthsAgoWeekly)->map(function ($weekData) {
                    return $this->calculateEpaymentTotals(collect($weekData));
                });

                $thisMonthTotals = $this->calculateEpaymentTotals($thisMonthData);
                $lastMonthTotals = $this->calculateEpaymentTotals($lastMonthData);
                $twoMonthsAgoTotals = $this->calculateEpaymentTotals($twoMonthsAgoData);
                return response()->json([
                    'response' => 'Success Get Epayment Data',
                    'message' => 'Epayment Data Period: Last Month ' . $lastMonthStart . ' - ' . $lastMonthEnd . ' | This Month ' . $thisMonthStart . ' - ' . $thisMonthEnd,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_Month' => [
                        // 'data' => $thisMonthData->toArray(),
                        'totals' => $thisMonthTotals,
                        'weekly_totals' => $thisMonthWeeklyTotals,
                    ],
                    'last_Month' => [
                        // 'data' => $lastMonthData->toArray(),
                        'totals' => $lastMonthTotals,
                        'weekly_totals' => $lastMonthWeeklyTotals,
                    ],
                    'table_data' => $this->formatMonthlyEpaymentTable($lastMonthTotals, $twoMonthsAgoTotals),

                ]);
            }

            return response()->json(['message' => 'Failed to fetch epayment data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function weeklyEpaymentThisWeekOnly()
    {
        try {
            $today = Carbon::now('Asia/Jakarta');

            // $prevWeek represents 6 days before today (start of this week)
            $thisWeekStart = $today->copy()->modify('-6 day')->format('Y-m-d');
            $thisWeekEnd = $today->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $thisWeekResponse = Http::post('http://110.0.100.70:8080/v3/api/getepayment', [
                'effective_start_date' => $thisWeekStart,
                'effective_end_date' => $thisWeekEnd,
                'location_code' => $locationCode,
            ]);

            if ($thisWeekResponse->successful()) {
                $thisWeekData = collect($thisWeekResponse->json()['data']);
                $thisWeekTotals = $this->calculateEpaymentTotals($thisWeekData);

                return response()->json([
                    'response' => 'Success Get Epayment Data (This Week Only)',
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_week' => [
                        'data' => $thisWeekData->toArray(),
                        'totals' => $thisWeekTotals,
                    ],
                    'table_data' => $this->formatEpaymentTable($thisWeekTotals, null), // null for last week
                ]);
            }

            return response()->json(['message' => 'Failed to fetch epayment data from API'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    private function calculateEpaymentTotals($data)
    {
        return [
            'flazzpayment' => $data->sum('flazzpayment'),
            'tapcashpayment' => $data->sum('tapcashpayment'),
            'brizzipayment' => $data->sum('brizzipayment'),
            'cashpayment' => $data->sum('cashpayment'),
            'dkijackpayment' => $data->sum('dkijackpayment'),
            'edcpayment' => $data->sum('edcpayment'),
            'luminousprepaidpayment' => $data->sum('luminousprepaidpayment'),
            'emoneypayment' => $data->sum('emoneypayment'),
            'megacashpayment' => $data->sum('megacashpayment'),
            'nobupayment' => $data->sum('nobupayment'),
            'parkeepayment' => $data->sum('parkeepayment'),
            'qrisepayment' => $data->sum('qrisepayment'),
            'allpayment' => $data->sum('allpayment'),
            'onstreet' => $data->sum('onstreet'),
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

    private function formatEpaymentTable($thisWeekTotals, $lastWeekTotals)
    {
        $payments = [
            'Flazz' => 'flazzpayment',
            'TapCash' => 'tapcashpayment',
            'Brizzi' => 'brizzipayment',
            'Cash' => 'cashpayment',
            // 'DKI JackCard' => 'dkijackpayment',
            // 'EDC' => 'edcpayment',
            // 'Luminous Prepaid' => 'luminousprepaidpayment',
            'e-Money' => 'emoneypayment',
            // 'MegaCash' => 'megacashpayment',
            // 'Nobu' => 'nobupayment',
            'Parkee' => 'parkeepayment',
            // 'QRIS' => 'qrisepayment',
            'All Payments' => 'allpayment',
            // 'On Street' => 'onstreet',
        ];

        $result = [];

        foreach ($payments as $label => $key) {
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
                'method' => $label,
                'last_week' => $last,
                'this_week' => $current,
                'percent_change' => $percentChange,
                'direction' => $direction,
                'color' => $color,
            ];
        }

        return $result;
    }
    private function formatMonthlyEpaymentTable($thisMonthTotals, $lastMonthTotals)
    {
        $payments = [
            'Flazz' => 'flazzpayment',
            'TapCash' => 'tapcashpayment',
            'Brizzi' => 'brizzipayment',
            'Cash' => 'cashpayment',
            // 'DKI JackCard' => 'dkijackpayment',
            // 'EDC' => 'edcpayment',
            // 'Luminous Prepaid' => 'luminousprepaidpayment',
            'e-Money' => 'emoneypayment',
            // 'MegaCash' => 'megacashpayment',
            // 'Nobu' => 'nobupayment',
            'Parkee' => 'parkeepayment',
            // 'QRIS' => 'qrisepayment',
            'All Payments' => 'allpayment',
            // 'On Street' => 'onstreet',
        ];

        $result = [];

        foreach ($payments as $label => $key) {
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
                'method' => $label,
                'last_month' => $last,
                'this_month' => $current,
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
