<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IncomePaymentController extends Controller
{
    public function index()
    {

        $paymentMethods = [
            'BCA_FLAZZ' => 'BCA FLAZZ',
            'BNI_TAPCASH' => 'BNI TAPCASH',
            'BRI_BRIZZI' => 'BRI BRIZZI',
            'CASH' => 'CASH',
            'DKI_JAKCARD' => 'DKI JAKCARD',
            'LUMINOS_PREPAID' => 'LUMINOS PREPAID',
            'MANDIRI_EMONEY' => 'MANDIRI EMONEY',
            'MEGA_MEGACASH' => 'MEGA MEGACASH',
            'NOBU' => 'NOBU',
        ];

        $vehicleTypes = [
            'ALL' => 'ALL',
            'CAR' => 'CAR',
            'MOTORCYCLE' => 'MOTORCYCLE',
            'BOX' => 'BOX',
        ];

        return view('pages.rekapreport', [
            'paymentMethods' => $paymentMethods,
            'vehicleTypes' => $vehicleTypes
        ]);
    }

    public function getIncomePayment(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'payment_method' => 'required|string',
        ]);

        $startDate = $validated['tgl_awal'];
        $endDate = $validated['tgl_akhir'];
        $paymentMethod = $validated['payment_method'];

        // The API URL from your original script
        $apiUrl = "http://110.0.100.70:8080/v1/api/centralpark/paymentmethodtransaction";

        // Use Laravel's HTTP Client to make the request with an increased timeout
        $response = Http::timeout(300)->get($apiUrl, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payment_method' => $paymentMethod,
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            // Return the JSON response from the API
            return $response->json();
        }

        // If the request failed, return an error response
        return response()->json(['error' => 'Failed to fetch data from the API.'], $response->status());
    }

    public function getLostIncomePayment(Request $request)
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
        ]);
        $startDate = $validated['tgl_awal'];
        $endDate = $validated['tgl_akhir'];
        $apiUrl = "http://110.0.100.70:8080/v1/api/centralpark/income-lostticket?start_date={$startDate}&end_date={$endDate}";
        // Increased timeout to 300 seconds (5 minutes)
        $response = Http::timeout(300)->get($apiUrl);
        if ($response->successful()) {
            return $response->json();
        }
        return response()->json(['error' => 'Failed to fetch data from the API.'], $response->status());
    }

    public function getRekapIncome(Request $request)
    {
        // Validasi request yang masuk
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'vehicle_type' => 'required|string|in:ALL,CAR,MOTORCYCLE,BOX',
        ]);

        $startDate = $validated['tgl_awal'];
        $endDate = $validated['tgl_akhir'];
        $vehicleType = $validated['vehicle_type'];

        // URL API untuk rekap income
        $apiUrl = "http://110.0.100.70:8080/v1/api/centralpark/recap-income";

        // Menggunakan HTTP Client Laravel untuk membuat request dengan timeout yang lebih lama
        // Timeout diatur ke 300 detik (5 menit)
        $response = Http::timeout(300)->get($apiUrl, [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vehicle_type' => $vehicleType,
        ]);

        // Cek jika request berhasil
        if ($response->successful()) {
            // Kembalikan response JSON dari API
            return $response->json();
        }

        // Jika request gagal, kembalikan response error
        return response()->json(['error' => 'Failed to fetch data from the API.'], $response->status());
    }
}
