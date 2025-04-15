<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Symfony\Component\CssSelector\Node\FunctionNode;



class IncomeController extends Controller
{
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

                // Hitung total income mingguan
                $thisMonthTotals = $this->calculateIncomeTotals($thisMonthData);
                $lastMonthTotals = $this->calculateIncomeTotals($lastMonthData);

                return response()->json([
                    'response' => 'Success Get Data',
                    'message' => 'Get Income Data Period ' . $lastMonthStart . ' - ' . $lastMonthEnd . ' (Last Month) and ' . $thisMonthStart . ' - ' . $thisMonthEnd . ' (This Month)',
                    'status_code' => 200,
                    'location_code' => $locationCode,
                    'this_Month' => [
                        'data' => $thisMonthData,
                        'totals' => $thisMonthTotals,
                    ],
                    'last_Month' => [
                        'data' => $lastMonthData,
                        'totals' => $lastMonthTotals,
                    ],
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
}
