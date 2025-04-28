<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function PeakSearch(Request $request)
    {
        $validated = $request->validate([
            'first_start_date' => 'required|string',
            'first_end_date' => 'required|string',
            'second_start_date' => 'required|string',
            'second_end_date' => 'required|string',
            'location_code' => 'required|string',
        ]);

        $apiUrl = 'http://110.0.100.70:8080/v3/api/peaksearch';

        $response = Http::post($apiUrl, $validated);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'API call failed',
                'status' => $response->status(),
                'body' => $response->body()
            ], $response->status());
        }
    }
    public function membershipSearch(Request $request)
    {
        $selection = $request->input('selection');
        $locationCode = session('selected_location_kode_lokasi');
        $startPeriod = $request->input('period'); // ✅ Ambil selalu dari inputan

        // Handle jika user tidak isi period (fallback bulan ini)
        if ($startPeriod) {
            $startDate = \Carbon\Carbon::createFromFormat('m/Y', $startPeriod)->format('m-Y');
        } else {
            $startDate = now()->format('m-Y');
        }

        $payload = [
            'month_date' => $startDate,
            'location_code' => $locationCode,
        ];

        // Send POST request
        $response = Http::post('http://110.0.100.70:8080/v3/api/bcamembership', $payload);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to fetch data from server.'
            ], 500);
        }

        $bcaData = $response->json();

        $aktifData = $bcaData['aktif'] ?? [];
        $nonaktifData = $bcaData['nonaktif'] ?? [];

        return response()->json([
            'aktif' => $aktifData,
            'nonaktif' => $nonaktifData,
        ]);
    }



    public function parkingDetailView()
    {
        return view('pages.parkingdetailsearch');
    }
    public function membershipSearchView()
    {
        return view('pages.membershipsearch');
    }
    public function peakSearchView()
    {
        return view('pages.peaksearch');
    }
}
