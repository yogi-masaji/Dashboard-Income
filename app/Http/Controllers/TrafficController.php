<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
}
