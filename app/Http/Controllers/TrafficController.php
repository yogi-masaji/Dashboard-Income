<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class TrafficController extends Controller
{

    public function dailyTraffic()
    {
        $locationCode = session('selected_location_kode_lokasi');

        $response = Http::get("http://110.0.100.70:8080/v3/api/trafficmanagementdaily", [
            'location_code' => $locationCode
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Failed to fetch traffic management data',
                'status' => $response->status(),
            ], $response->status());
        }
    }

    public function weeklyTraffic()
    {
        try {
            $today = Carbon::now('Asia/Jakarta');
            $prevWeek = $today->copy()->modify('-6 day');

            $dateToday = $today->format('d-m-Y');
            $dateLastWeek = $prevWeek->format('d-m-Y');

            $locationCode = session('selected_location_kode_lokasi');

            $payload = [
                'effective_start_date' => $dateLastWeek,
                'effective_end_date' => $dateToday,
                'location_code' => $locationCode,
            ];

            $response = Http::post('http://110.0.100.70:8080/v3/api/trafficmanagement', $payload);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json($data);
            } else {
                return response()->json(['message' => 'Failed to fetch traffic data from API'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function monthlyTraffic()
    {
        try {
            $today = Carbon::now('Asia/Jakarta');
            $firstDayOfMonth = $today->copy()->startOfMonth()->format('d-m-Y');
            $lastDayOfMonth = $today->copy()->endOfMonth()->format('d-m-Y');

            $locationCode = session('selected_location_kode_lokasi');

            $payload = [
                'effective_start_date' => $firstDayOfMonth,
                'effective_end_date' => $lastDayOfMonth,
                'location_code' => $locationCode,
            ];

            $response = Http::post('http://110.0.100.70:8080/v3/api/trafficmanagement', $payload);

            if ($response->successful()) {
                return response()->json(
                    $response->json(),

                );
            } else {
                return response()->json(['message' => 'Failed to fetch traffic data from API'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }
}
