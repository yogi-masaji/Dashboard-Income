<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\CssSelector\Node\FunctionNode;

class EpaymentController extends Controller
{
    public function weeklyEpayment()
    {
        try {
            $today = Carbon::now()->timezone('Asia/Jakarta');

            $thisWeekStart = $today->copy()->subDays(6)->format('Y-m-d');
            $thisWeekEnd = $today->format('Y-m-d');

            $lastWeekStart = $today->copy()->subDays(13)->format('Y-m-d');
            $lastWeekEnd = $today->copy()->subDays(7)->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getepayment', [
                'effective_start_date' => $lastWeekStart,
                'effective_end_date' => $thisWeekEnd,
                'location_code' => $locationCode,
            ]);

            if ($response->successful()) {
                $epaymentData = collect($response->json()['data']);

                $thisWeekData = $epaymentData->filter(function ($item) use ($thisWeekStart, $thisWeekEnd) {
                    return Carbon::parse($item['tanggal'])->between($thisWeekStart, $thisWeekEnd);
                })->values();

                $lastWeekData = $epaymentData->filter(function ($item) use ($lastWeekStart, $lastWeekEnd) {
                    return Carbon::parse($item['tanggal'])->between($lastWeekStart, $lastWeekEnd);
                })->values();

                $thisWeekTotals = $this->calculateEpaymentTotals($thisWeekData);
                $lastWeekTotals = $this->calculateEpaymentTotals($lastWeekData);

                return response()->json([
                    'response' => 'Success Get Epayment Data',
                    'message' => 'Epayment Data Period: Last Week ' . $lastWeekStart . ' - ' . $lastWeekEnd . ' | This Week ' . $thisWeekStart . ' - ' . $thisWeekEnd,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_week' => [
                        'data' => $thisWeekData->toArray(),
                        'totals' => $thisWeekTotals,
                    ],
                    'last_week' => [
                        'data' => $lastWeekData->toArray(),
                        'totals' => $lastWeekTotals,
                    ],
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
            $thisMonthEnd = $today->format('Y-m-d');

            $lastMonthStart = $today->copy()->subMonth()->startOfMonth()->format('Y-m-d');
            $lastMonthEnd = $today->copy()->subMonth()->endOfMonth()->format('Y-m-d');

            $locationCode = session('selected_location_kode_lokasi');

            $response = Http::post('http://110.0.100.70:8080/v3/api/getepayment', [
                'effective_start_date' => $lastMonthStart,
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

                $thisMonthTotals = $this->calculateEpaymentTotals($thisMonthData);
                $lastMonthTotals = $this->calculateEpaymentTotals($lastMonthData);

                return response()->json([
                    'response' => 'Success Get Epayment Data',
                    'message' => 'Epayment Data Period: Last Month ' . $lastMonthStart . ' - ' . $lastMonthEnd . ' | This Month ' . $thisMonthStart . ' - ' . $thisMonthEnd,
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_Month' => [
                        'data' => $thisMonthData->toArray(),
                        'totals' => $thisMonthTotals,
                    ],
                    'last_Month' => [
                        'data' => $lastMonthData->toArray(),
                        'totals' => $lastMonthTotals,
                    ],
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
}
