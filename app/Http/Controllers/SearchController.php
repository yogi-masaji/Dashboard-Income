<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;

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

    public function parkingDetailSearch(Request $request)
    {
        // Validate input
        $request->validate([
            'start1' => 'required|date',
            'end1'   => 'required|date',
        ]);

        // Get date inputs
        $startDate = $request->input('start1');
        $endDate   = $request->input('end1');

        // Get session data
        $locationCode = session('selected_location_kode_lokasi');

        // Prepare API request payload
        $payload = [
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'location_code' => $locationCode,
        ];

        // Send API request (using Laravel Http Client)
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post('http://110.0.100.70:8080/v3/api/parkingdetail', $payload);

        if ($response->successful()) {
            // Get the 'data' part
            $data = $response->json();

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from server.',
            ], 500);
        }
    }

    public function summaryReportSearch(Request $request)
    {
        // Validasi input untuk tanggal
        $validated = $request->validate([
            'start1' => 'required|date',
            'end1'   => 'required|date',
        ]);

        // Ambil tanggal dari request
        $first_date = $request->input('start1');
        $end_date = $request->input('end1');

        // Format tanggal
        $start_date = \Carbon\Carbon::parse($first_date)->format('d-m-Y');
        $end_date = \Carbon\Carbon::parse($end_date)->format('d-m-Y');

        // Kirim request ke API
        $response = Http::post('http://110.0.100.70:8080/v3/api/report-management', [
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);

        $data_report = $response->json();
        $first_period = $data_report['data'][0]['first_period'] ?? null;

        // Menyiapkan data untuk ditampilkan
        $bank_data = [
            'first_period' => $first_period
        ];

        return response()->json($bank_data);
    }

    public function incomeGateSearchApi(Request $request)
    {
        // Validate input
        $request->validate([
            'start1' => 'required|date',
            'end1'   => 'required|date',
        ]);

        // Get date inputs
        $startDate = $request->input('start1');
        $endDate   = $request->input('end1');

        // Get session data
        $locationCode = session('selected_location_kode_lokasi');

        // Prepare API request payload
        $payload = [
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'location_code' => $locationCode,
        ];

        // Send API request
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post('http://110.0.100.70:8080/v3/api/summary-income-ptp', $payload);

        if ($response->successful()) {
            $json = $response->json();

            return response()->json([
                'success' => true,
                'data' => $json['data'] ?? [], // hanya ambil bagian ['data']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from server.',
            ], 500);
        }
    }
    public function ritaseSerachAPI(Request $request)
    {
        // Allow script to run for up to 5 minutes
        ini_set('max_execution_time', 300);

        // Retrieve the session variables
        $locationCode = session('selected_location_kode_lokasi');

        // Handle the request for start and end date
        $startDate = $request->input('start1');
        $endDate = $request->input('end1');

        // API endpoint and parameters
        $url = 'http://110.0.100.70:8080/api/retase-juanda';
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location_code' => $locationCode
        ];

        // Make the API request with 5-minute timeout
        $response = Http::timeout(300)->post($url, $params);

        // Return the actual response from the API
        return response()->json($response->json());
    }

    public function occupancySearchAPI(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $startDate = $data['start1'] ?? null;
        $locationCode = session('selected_location_kode_lokasi');

        $queryParams = [
            'start_date' => $startDate,
            'location_code' => $locationCode,
        ];

        try {
            $response = Http::timeout(5)->get('http://110.0.100.70:8080/v3/api/occupancy-pgs', $queryParams);

            return response()->json($response->json()); // Just return JSON as-is
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function CustomSearch(Request $request)
    {
        // Validate input for dates
        $validated = $request->validate([
            'first_start_date' => 'required|date',
            'first_end_date'   => 'required|date',
            'second_start_date' => 'required|date',
            'second_end_date'   => 'required|date',
        ]);

        // Parse input dates
        $firstStartDate  = Carbon::parse($request->input('first_start_date'))->startOfDay()->toISOString();
        $firstEndDate    = Carbon::parse($request->input('first_end_date'))->endOfDay()->toISOString();
        $secondStartDate = Carbon::parse($request->input('second_start_date'))->startOfDay()->toISOString();
        $secondEndDate   = Carbon::parse($request->input('second_end_date'))->endOfDay()->toISOString();

        // Retrieve location code from session
        $locationCode = session('selected_location_kode_lokasi');

        if (!$locationCode) {
            return response()->json(['error' => 'Location code is missing'], 400);
        }

        // Set API request parameters
        $queryParams = [
            'first_start_date'  => $firstStartDate,
            'first_end_date'    => $firstEndDate,
            'second_start_date' => $secondStartDate,
            'second_end_date'   => $secondEndDate,
            'location_code'     => $locationCode,
        ];

        try {
            $response = Http::post('http://110.0.100.70:8080/v3/api/customsearch', $queryParams);

            if ($response->successful()) {
                $data = $response->json();
                $results = $data['data'][0];

                // Hitung total
                $firstTotals = $this->calculateTotals($results['first_period']);
                $secondTotals = $this->calculateTotals($results['second_period']);
                $grandTotals = $this->calculateGrandTotals($firstTotals, $secondTotals);

                // Tentukan daftar metode pembayaran yang akan dicek
                $paymentMethods = [
                    'parkeepayment',
                    'qrisepayment',
                    'emoney',
                    'flazz',
                    'brizzi',
                    'tapcash',
                    'dkijackpayment',
                    'cash'
                ];

                // Filter hanya metode pembayaran dari daftar di atas
                $firstPaymentSubset = array_intersect_key($firstTotals, array_flip($paymentMethods));
                $secondPaymentSubset = array_intersect_key($secondTotals, array_flip($paymentMethods));

                // Cari top payment dari masing-masing periode
                $firstMaxSource = array_keys($firstPaymentSubset, max($firstPaymentSubset))[0];
                $secondMaxSource = array_keys($secondPaymentSubset, max($secondPaymentSubset))[0];

                return response()->json([
                    'response' => $data['response'],
                    'message' => $data['message'],
                    'status_code' => $data['status_code'],
                    'location_code' => $data['location_code'],
                    'data' => $results,
                    'totals' => [
                        'first_period' => $firstTotals,
                        'second_period' => $secondTotals
                    ],
                    'grand_totals' => $grandTotals,
                    'top_payment' => [
                        'first_period' => [
                            'source' => $firstMaxSource,
                            'value' => $firstPaymentSubset[$firstMaxSource]
                        ],
                        'second_period' => [
                            'source' => $secondMaxSource,
                            'value' => $secondPaymentSubset[$secondMaxSource]
                        ]
                    ]
                ]);
            } else {
                return response()->json([
                    'error' => 'API call failed',
                    'status' => $response->status(),
                    'body' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception occurred: ' . $e->getMessage()], 500);
        }
    }



    private function calculateTotals(array $periodData)
    {
        $totals = [];

        foreach ($periodData as $entry) {
            foreach ($entry as $key => $value) {
                if (is_numeric($value)) {
                    $totals[$key] = ($totals[$key] ?? 0) + $value;
                }
            }
        }

        return $totals;
    }


    public function calculateGrandTotals($firstTotals, $secondTotals)
    {
        // Sum of income payments for both periods
        $incomePaymentFirstPeriod = array_sum([
            $firstTotals['parkeepayment'],
            $firstTotals['qrisepayment'],
            $firstTotals['emoney'],
            $firstTotals['flazz'],
            $firstTotals['brizzi'],
            $firstTotals['tapcash'],
            $firstTotals['dkijackpayment'],
            $firstTotals['cash']
        ]);

        $incomePaymentSecondPeriod = array_sum([
            $secondTotals['parkeepayment'],
            $secondTotals['qrisepayment'],
            $secondTotals['emoney'],
            $secondTotals['flazz'],
            $secondTotals['brizzi'],
            $secondTotals['tapcash'],
            $secondTotals['dkijackpayment'],
            $secondTotals['cash']
        ]);

        // Sum of vehicle quantities for both periods
        $quantityPassFirstPeriod = array_sum([
            $firstTotals['carpassqty'],
            $firstTotals['motorbikepassqty'],
            $firstTotals['truckpassqty'],
            $firstTotals['taxipassqty'],
            $firstTotals['otherqtypass']
        ]);

        $quantityPassSecondPeriod = array_sum([
            $secondTotals['carpassqty'],
            $secondTotals['motorbikepassqty'],
            $secondTotals['truckpassqty'],
            $secondTotals['taxipassqty'],
            $secondTotals['otherqtypass']
        ]);

        $quantityCasualFirstPeriod = array_sum([
            $firstTotals['carqty'],
            $firstTotals['motorbikeqty'],
            $firstTotals['truckqty'],
            $firstTotals['taxiqty'],
            $firstTotals['otherqty'],
            $firstTotals['valetlobbyqty'],
            $firstTotals['valetnonlobbyqty'],
            $firstTotals['vipqty'],
            $firstTotals['vipramayanaqty'],
            $firstTotals['vipnonramayanaqty'],
            $firstTotals['evipqty'],
            $firstTotals['valetvipqty'],
            $firstTotals['carpreferredqty'],
            $firstTotals['motorbikepreferredqty'],
            $firstTotals['extendchargingqty'],
            $firstTotals['lostticketqty']
        ]);

        $quantityCasualSecondPeriod = array_sum([
            $secondTotals['carqty'],
            $secondTotals['motorbikeqty'],
            $secondTotals['truckqty'],
            $secondTotals['taxiqty'],
            $secondTotals['otherqty'],
            $secondTotals['valetlobbyqty'],
            $secondTotals['valetnonlobbyqty'],
            $secondTotals['vipqty'],
            $secondTotals['vipramayanaqty'],
            $secondTotals['vipnonramayanaqty'],
            $secondTotals['evipqty'],
            $secondTotals['valetvipqty'],
            $secondTotals['carpreferredqty'],
            $secondTotals['motorbikepreferredqty'],
            $secondTotals['extendchargingqty'],
            $secondTotals['lostticketqty']
        ]);


        // Sum of vehicle income for both periods
        $incomeVehicleFirstPeriod = array_sum([
            $firstTotals['carincome'],
            $firstTotals['motorbikeincome'],
            $firstTotals['taxiincome'],
            $firstTotals['truckincome'],
            $firstTotals['otherincome'],
            $firstTotals['valetlobbyincome'],
            $firstTotals['valetnonlobbyincome'],
            $firstTotals['vipbrizzi'],
            $firstTotals['vipemoney'],
            $firstTotals['vipincome'],
            $firstTotals['vipqris'],
            $firstTotals['vipramayanaqty'],
            $firstTotals['vipramayanaincome'],
            $firstTotals['vipnonramayanaqty'],
            $firstTotals['vipnonramayanaincome'],
            $firstTotals['evipincome'],
            $firstTotals['evipqty'],
            $firstTotals['valetvipincome'],
            $firstTotals['valetvipqty'],
            $firstTotals['carpreferredincome'],
            $firstTotals['motorbikepreferredincome'],
            $firstTotals['extendchargingincome'],
            $firstTotals['lostticket']
        ]);

        $incomeVehicleSecondPeriod = array_sum([
            $secondTotals['carincome'],
            $secondTotals['motorbikeincome'],
            $secondTotals['taxiincome'],
            $secondTotals['truckincome'],
            $secondTotals['otherincome'],
            $secondTotals['valetlobbyincome'],
            $secondTotals['valetnonlobbyincome'],
            $secondTotals['vipbrizzi'],
            $secondTotals['vipemoney'],
            $secondTotals['vipincome'],
            $secondTotals['vipqris'],
            $secondTotals['vipramayanaqty'],
            $secondTotals['vipramayanaincome'],
            $secondTotals['vipnonramayanaqty'],
            $secondTotals['vipnonramayanaincome'],
            $secondTotals['evipincome'],
            $secondTotals['evipqty'],
            $secondTotals['valetvipincome'],
            $secondTotals['valetvipqty'],
            $secondTotals['carpreferredincome'],
            $secondTotals['motorbikepreferredincome'],
            $secondTotals['extendchargingincome'],
            $secondTotals['lostticket']
        ]);

        // Return grand totals
        return [
            'income_payment' => [
                'first_period' => $incomePaymentFirstPeriod,
                'second_period' => $incomePaymentSecondPeriod,
            ],
            'quantity_pass' => [
                'first_period' => $quantityPassFirstPeriod,
                'second_period' => $quantityPassSecondPeriod,
            ],
            'quantity_casual' => [
                'first_period' => $quantityCasualFirstPeriod,
                'second_period' => $quantityCasualSecondPeriod,
            ],
            'income_vehicle' => [
                'first_period' => $incomeVehicleFirstPeriod,
                'second_period' => $incomeVehicleSecondPeriod,
            ]
        ];
    }


    public function parkingMemberSearchApi(Request $request)
    {
        // Validate input
        $request->validate([
            'start1' => 'required|date',
            'end1'   => 'required|date',
        ]);

        // Get date inputs
        $startDate = $request->input('start1');
        $endDate   = $request->input('end1');

        // Get session data
        $locationCode = session('selected_location_kode_lokasi');

        // Prepare API request payload
        $payload = [
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'location_code' => $locationCode,
        ];

        // Send API request
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post('http://110.0.100.70:8080/v3/api/membership-detail', $payload);

        if ($response->successful()) {
            $json = $response->json();
            $data = $json['data'] ?? [];

            // Inisialisasi agregasi
            $summary = [
                'motor' => ['total_income' => 0, 'count' => 0],
                'mobil' => ['total_income' => 0, 'count' => 0],
                'box'   => ['total_income' => 0, 'count' => 0],
            ];

            foreach ($data as $item) {
                $type = strtolower($item['vehicleType']);

                if ($type === 'motorcycle') {
                    $summary['motor']['total_income'] += $item['grandTotalAmount'];
                    $summary['motor']['count'] += 1;
                } elseif ($type === 'car') {
                    $summary['mobil']['total_income'] += $item['grandTotalAmount'];
                    $summary['mobil']['count'] += 1;
                } elseif ($type === 'box') {
                    $summary['box']['total_income'] += $item['grandTotalAmount'];
                    $summary['box']['count'] += 1;
                }
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'summary' => $summary,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from server.',
            ], 500);
        }
    }



    public function occupancyRateSearchAPI(Request $request)
    {
        $startDate = $request->input('start1');
        $locationCode = session('selected_location_kode_lokasi');

        $payload = [
            'start_date' => $startDate,
            'location_code' => $locationCode,
        ];

        try {
            // Kirim POST request tanpa timeout
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post('http://110.0.100.70:8080/api/unpaid-quantity-dps', $payload);

            $body = $response->json();

            $data_unpaid = $body['data'][0]['percentage_lot'] ?? null;
            $data_realtime = $body['data'][0]['datarealtime'] ?? [];

            return response()->json([
                'data_unpaid' => $data_unpaid,
                'data_realtime' => $data_realtime,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function ritaseDurationSearchAPI(Request $request)
    {
        $request->validate([
            'start1' => 'required|date',
            'end1' => 'required|date',
        ]);

        $startDate = $request->start1;
        $endDate = $request->end1;
        $vehicleType = $request->vehicle_type ?? 'ALL';
        $parkingslipType = $request->status_vehicle ?? 'ALL';

        // Ambil kode lokasi dari session
        $locationCode = session('selected_location_kode_lokasi');

        // Payload
        $payload = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vehicle_type' => $vehicleType,
            'parkingslip_type' => $parkingslipType,
            'location_code' => $locationCode,
        ];

        try {
            // Kirim POST request ke API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('http://110.0.100.70:8080/v3/api/retase-traffic-duration', $payload);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                // Hitung summary
                $summary = $this->calculateSummary($data);

                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'summary' => $summary,
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'API request failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function ritaseTrafficGateSearchAPI(Request $request)
    {
        $request->validate([
            'start1' => 'required|date',
            'end1' => 'required|date',
        ]);

        $startDate = $request->start1;
        $endDate = $request->end1;
        $vehicleType = $request->vehicle_type ?? 'ALL';
        $parkingslipType = $request->status_vehicle ?? 'ALL';
        $gateType = $request->gate_type ?? 'IN';

        // Ambil kode lokasi dari session
        $locationCode = session('selected_location_kode_lokasi');

        // Payload
        $payload = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'vehicle_type' => $vehicleType,
            'parkingslip_type' => $parkingslipType,
            'gate_type' => $gateType,
            'location_code' => $locationCode,
        ];

        try {
            // Kirim POST request ke API
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('http://110.0.100.70:8080/v3/api/retase-traffic-out', $payload);

            if ($response->successful()) {
                $data = $response->json()['data'] ?? [];

                // Hitung summary
                $summary = $this->calculateSummary($data);

                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'summary' => $summary,
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'API request failed'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function calculateSummary($data)
    {
        $summary = [];
        for ($i = 1; $i <= 24; $i++) {
            $key = "jam{$i}";
            $summary["jam{$i}_sum"] = array_sum(array_column($data, $key));
        }

        $summary['jam24over_sum'] = array_sum(array_column($data, 'over24jam'));
        $summary['total_sum'] = array_sum(array_column($data, 'total'));

        return $summary;
    }

    public function incomePelindoSearchAPI(Request $request)
    {
        $request->validate([
            'start1' => 'required|date',
            'end1'   => 'required|date',
        ]);

        $startDate = date('Y-m-d', strtotime($request->input('start1')));
        $endDate   = date('Y-m-d', strtotime($request->input('end1')));

        // Misalnya session kode lokasi sudah diset sebelumnya
        $locationCode = session('selected_location_kode_lokasi');

        if (!$locationCode) {
            return response()->json(['success' => false, 'message' => 'Lokasi tidak ditemukan dalam sesi.']);
        }

        $payload = [
            'start_date'    => $startDate,
            'end_date'      => $endDate,
            'location_code' => $locationCode,
        ];

        try {
            $response = Http::post('http://110.0.100.70:8080/v3/api/income-posline-pitj', $payload);

            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'Gagal ambil data dari API.']);
            }

            $data = $response->json();

            if (!isset($data['data'][0])) {
                return response()->json(['success' => false, 'message' => 'Data kosong.']);
            }

            $item = $data['data'][0];

            return response()->json([
                'success' => true,
                'data' => [
                    'buffer' => $item['buffer'][0] ?? null,
                    'pos1'   => $item['pos1'][0] ?? null,
                    'pos8'   => $item['pos8'][0] ?? null,
                    'pos9'   => $item['pos9'][0] ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function prodpendapatansearchAPI(Request $request)
    {
        ini_set('max_execution_time', 300); // Biar PHP script nggak timeout

        $year = $request->input('year');
        $locationCode = session('selected_location_kode_lokasi');

        $payload = [
            'start_date' => (string) $year,
            'location_code' => $locationCode
        ];

        $response = Http::timeout(300)->post('http://110.0.100.70:8080/api/monthly-income-juanda', $payload);

        $responseData = $response->json();  // Assuming the response is in the same format as described.

        // Prepare the final arranged data
        $arrangedData = [];
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        foreach ($months as $month) {
            $cargoData = [];
            $terminalData = [];
            $grandtotalData = [];

            foreach ($responseData['data'][0]['cargo'] as $cargo) {
                if ($cargo['bulan'] == $month) {
                    $cargoData[] = $cargo;
                }
            }

            foreach ($responseData['data'][0]['terminal'] as $terminal) {
                if ($terminal['bulan'] == $month) {
                    $terminalData[] = $terminal;
                }
            }

            foreach ($responseData['data'][0]['grandtotal'] as $grandtotal) {
                if ($grandtotal['bulan'] == $month) {
                    $grandtotalData[] = $grandtotal;
                }
            }

            if (!empty($cargoData) || !empty($terminalData) || !empty($grandtotalData)) {
                $arrangedData[] = [
                    'bulan' => $month,
                    'cargo' => $cargoData,
                    'terminal' => $terminalData,
                    'grandtotal' => $grandtotalData
                ];
            }
        }

        return response()->json([
            'status_name' => 'Success Get Data',
            'status_code' => 200,
            'data' => $arrangedData
        ]);
    }


    public function quantitypergateAPI(Request $request)
    {
        ini_set('max_execution_time', 300);
        // Validate
        $request->validate([
            'start1' => 'required|date',
            'end1' => 'required|date',
        ]);

        // Format dates
        $startDate = \Carbon\Carbon::parse($request->start1)->format('d-m-Y');
        $endDate = \Carbon\Carbon::parse($request->end1)->format('d-m-Y');

        // Get location code from session
        $locationCode = session('selected_location_kode_lokasi');

        // Prepare payload
        $payload = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location_code' => $locationCode,
        ];

        // Call external API
        try {
            $response = Http::timeout(300)->post('http://110.0.100.70:8080/v3/api/traffic-golongan-pitj', $payload);

            if (!$response->ok()) {
                return response()->json(['success' => false, 'message' => 'API error'], 500);
            }

            $data = $response->json();

            if (empty($data['data'][0])) {
                return response()->json(['success' => false, 'message' => 'No data found'], 200);
            }

            $trafik = $data['data'][0];

            // Extract per_pos
            $perPos = [
                'buffer_area' => $trafik['per_pos'][0]['buffer_area'] ?? 0,
                'pos1'        => $trafik['per_pos'][1]['pos_1'] ?? 0,
                'pos8'        => $trafik['per_pos'][2]['pos_8'] ?? 0,
                'pos9'        => $trafik['per_pos'][3]['pos_9'] ?? 0,
                'totalPos'    => $trafik['per_pos'][4]['total'] ?? 0,
            ];

            // Extract per_golongan
            $perGolongan = [
                'motor'         => $trafik['per_golongan'][0]['motor'] ?? 0,
                'mobil'         => $trafik['per_golongan'][1]['mobil'] ?? 0,
                'truk'          => $trafik['per_golongan'][2]['truck'] ?? 0,
                'totalGolongan' => $trafik['per_golongan'][3]['total'] ?? 0,
            ];

            return response()->json([
                'success' => true,
                'per_pos' => $perPos,
                'per_golongan' => $perGolongan
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function dataCompareAPI(Request $request)
    {
        // Ambil tanggal dari request POST
        $start_date = $request->input('startdate');
        $end_date = $request->input('enddate');

        // Ambil location_code dari session
        $locationCode = session('selected_location_kode_lokasi');

        // Persiapkan data untuk dikirim ke API
        $payload = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location_code' => $locationCode
        ];

        // Kirim data ke API eksternal menggunakan HTTP client Laravel
        $response = Http::post('http://110.0.100.70:8080/v3/api/compare-data', $payload);

        // Decode responsenya
        $result = $response->json();

        // Ambil data first_period dari data[0]
        $data = $result['data'][0]['first_period'] ?? null;

        // Kembalikan hasil sebagai JSON
        return response()->json([$data]);
    }

    public function dataCompareView()
    {
        return view('pages.datacomparesearch');
    }




    public function quantitypergateView()
    {
        return view('pages.quantitypergatesearch');
    }


    public function prodpendapatansearchView()
    {
        return view('pages.prodpendapatansearch');
    }

    public function incomePelindoSearchView()
    {
        return view('pages.incomesearchpelindo');
    }


    public function ritaseTrafficGateView()
    {
        return view('pages.ritasegatesearch');
    }

    public function ritaseDurationSearchView()
    {
        return view('pages.ritasedurationsearch');
    }

    public function occupancyRateSearchView()
    {
        return view('pages.occupancyratesearch');
    }

    public function parkingMemberView()
    {
        return view('pages.parkingmember');
    }
    public function customSearchView()
    {
        return view('pages.customsearch');
    }
    public function occupancySearchView()
    {
        return view('pages.occupancysearch');
    }

    public function ritaseSearchView()
    {
        return view('pages.ritasesearch');
    }

    public function incomeGateSearchView()
    {
        return view('pages.incomegatesearch');
    }
    public function SummaryReportView()
    {
        return view('pages.summaryreportsearch');
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

    public function longstaySearchView()
    {
        return view('pages.longstaysearch');
    }

    public function longstaySearchAPI(Request $request)
    {
        // Validasi request
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date',
            'tgl_awal_row2' => 'required|date',
            'tgl_akhir_row2' => 'required|date',
        ]);

        // Mengambil kode lokasi dari session
        $locationCode = session('selected_location_kode_lokasi');

        if (!$locationCode) {
            return response()->json(['error' => 'Kode lokasi tidak ditemukan di session.'], 400);
        }

        // Format tanggal dari Y-m-d (HTML date input) ke d-m-Y (API format)
        $first_start_date = Carbon::parse($request->tgl_awal)->format('d-m-Y');
        $first_end_date = Carbon::parse($request->tgl_akhir)->format('d-m-Y');
        $second_start_date = Carbon::parse($request->tgl_awal_row2)->format('d-m-Y');
        $second_end_date = Carbon::parse($request->tgl_akhir_row2)->format('d-m-Y');

        // URL API
        $apiUrl = 'http://110.0.100.70:8080/external/v1/report/long-stay-search';

        // Body untuk request API
        $payload = [
            'first_start_date'  => $first_start_date,
            'first_end_date'    => $first_end_date,
            'second_start_date' => $second_start_date,
            'second_end_date'   => $second_end_date,
            'location_code'     => $locationCode,
        ];

        try {
            // Melakukan POST request ke API menggunakan Laravel HTTP Client
            $response = Http::post($apiUrl, $payload);

            // Cek jika request berhasil
            if ($response->successful()) {
                $data = $response->json();

                // Memastikan struktur data sesuai dengan yang diharapkan
                if (isset($data['data']) && count($data['data']) > 0) {
                    $first_period = $data['data'][0]['first_period'];
                    $second_period = $data['data'][0]['second_period'];

                    // Mengembalikan data dalam format yang sama dengan script PHP lama
                    return response()->json([$first_period, $second_period]);
                } else {
                    return response()->json(['error' => 'Struktur data dari API tidak sesuai.'], 500);
                }
            } else {
                // Mengembalikan error jika request ke API gagal
                return response()->json(['error' => 'Gagal mengambil data dari API.', 'details' => $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            // Menangani exception jika terjadi error koneksi atau lainnya
            return response()->json(['error' => 'Terjadi kesalahan saat menghubungi API.', 'details' => $e->getMessage()], 500);
        }
    }
    public function quantityPerGatePmbeAPI(Request $request)
    {
        $validated = $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date',
            'gate_option' => 'required|string|in:IN,OUT',
        ]);

        $locationCode = session('selected_location_kode_lokasi', 'PMBE');

        $payload = [
            'start_date' => $validated['tgl_awal'],
            'end_date' => $validated['tgl_akhir'],
            'location_code' => $locationCode,
            'gate' => $validated['gate_option'],
        ];

        $apiUrl = 'http://110.0.100.70:8080/v3/api/quantity-pergate-pmbe';

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to fetch data from API.',
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
