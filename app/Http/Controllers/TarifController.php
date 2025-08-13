<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\QueryException;

class TarifController extends Controller
{
    /**
     * Menyiapkan koneksi database dinamis berdasarkan session.
     */
    private function setupDynamicConnection()
    {
        $host = Session::get('ip', '127.0.0.1');
        Config::set('database.connections.pgsql_tarif.host', $host);
        DB::purge('pgsql_tarif');
    }

    /**
     * Menampilkan halaman form simulasi tarif.
     */
    public function index()
    {
        return view('pages.simulasitarif');
    }

    /**
     * Menghitung dan menampilkan hasil simulasi tarif.
     */
    public function calculate(Request $request)
    {
        // Validasi input
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            't_awal' => 'required|numeric',
            'select' => 'required|string',
            'gp' => 'required|string',
        ]);

        $this->setupDynamicConnection();

        // Ambil semua data dari request
        $tgl_aw   = date('Ymd', strtotime($request->input("tgl_awal")));
        $tgl_ak   = date('Ymd', strtotime($request->input("tgl_akhir")));
        $t_awal   = (float) $request->input('t_awal');
        $t_akhir  = (float) $request->input('t_akhir', 0);
        $t_ska    = (float) $request->input('t_ska');
        $t_ske    = (float) $request->input('t_ske');
        $t_max    = (float) $request->input('t_max', 0);
        $t_maxs   = (float) $request->input('t_maxs', 0);
        $v_select = $request->input('select');
        $gp       = $request->input('gp');
        $berlaku  = $request->input('berlaku');
        $berlaku2 = $request->input('berlaku2');
        $yesno    = $request->input('simulasi1_tipe'); // 'flat' atau 'progresif'

        $konver   = @($berlaku * 60);
        $konver2  = @($berlaku2 * 60);
        $expire   = gmdate('H:i:s', $konver);
        $expire2  = gmdate('H:i:s', $konver2);

        $vehicleCode = '';
        switch ($v_select) {
            case 'Mobil':
                $vehicleCode = 'B';
                break;
            case 'Motor':
                $vehicleCode = 'C';
                break;
            case 'Truck':
                $vehicleCode = 'T';
                break;
            case 'Taxi':
                $vehicleCode = 'X';
                break;
        }

        $rawQuery = "SELECT to_timestamp(periode,'YYYYMMDD'):: timestamp without time zone AS tanggal, 
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk < '01:00:00' THEN 1 ELSE 0  END) AS c0001,
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk <= '02:00:00' AND tglkeluar - tglmasuk > '01:00:00' THEN 1 ELSE 0  END) AS c0102,
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk <= '03:00:00' AND tglkeluar - tglmasuk > '02:00:00' THEN 1 ELSE 0  END) AS c0203, 
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk <= '04:00:00' AND tglkeluar - tglmasuk > '03:00:00' THEN 1 ELSE 0  END) AS c0304,  
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk <= '05:00:00' AND tglkeluar - tglmasuk > '04:00:00' THEN 1 ELSE 0  END) AS c0405,  
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk > '05:00:00' THEN 1 ELSE 0  END) AS c05,
                        SUM(CASE WHEN  (NOPASS='' OR nopass = 'EXPIRE' or transaksifee > 0) AND tglkeluar - tglmasuk < '00:10:00' THEN 1 ELSE 0  END) AS cgp,
                        admtarifparkir.normalrate01 as t01, admtarifparkir.normalrate02 as t02, case when admtarifparkir.fmaxfee = true then admtarifparkir.maxnormalrate else 0 end as max
                        FROM historytransaksi left outer join admtarifparkir on historytransaksi.kodevehicle = admtarifparkir.kodevehicle  WHERE historytransaksi.kodevehicle = ? AND (periode BETWEEN ? AND ?) GROUP BY admtarifparkir.kodevehicle, admtarifparkir.normalrate01, admtarifparkir.normalrate02, admtarifparkir.fmaxfee, admtarifparkir.maxnormalrate";

        try {
            $results = DB::connection('pgsql_tarif')->select($rawQuery, [$vehicleCode, $tgl_aw, $tgl_ak]);
        } catch (QueryException $e) {
            return back()->withInput()->withErrors(['database' => 'Gagal terhubung ke database tarif: ' . $e->getMessage()]);
        }

        // Inisialisasi semua variabel
        $tC1 = $tC2 = $tC3 = $tC4 = $tC5 = $tCm5 = 0;
        $tf_D1 = $tf_D2 = $tf_D3 = $tf_D4 = $tf_D5 = $tf_Dm5 = 0;
        $tf_C1 = $tf_C2 = $tf_C3 = $tf_C4 = $tf_C5 = $tf_Cm5 = 0;
        $tf_S1 = $tf_S2 = $tf_S3 = $tf_S4 = $tf_S5 = $tf_Sm5 = 0;
        $td01 = $ts02 = $max = 0;
        $td02 = $td03 = $td04 = $td05 = $tdm5 = 0;
        $tfC1 = $tfC2 = $tfC3 = $tfC4 = $tfCm5 = 0;
        $tfS1 = $tfS2 = $tfS3 = $tfS4 = $tfSm5 = 0;

        foreach ($results as $data) {
            $data = (array)$data;

            // --- LOGIKA DARI upgrade.php DITERAPKAN DI SINI ---

            $tglAwal = date('d ', strtotime($tgl_aw));
            $tglAkhir = date('d F Y', strtotime($tgl_ak));

            $C1_val = ($gp == 'gp') ? ($data['cgp'] + $data['c0001']) : $data['c0001'];
            $tC1  += $C1_val;
            $tC2  += $data['c0102'];
            $tC3  += $data['c0203'];
            $tC4  += $data['c0304'];
            $tC5  += $data['c0405'];
            $tCm5 += $data['c05'];

            $td01 = (float) $data['t01'];
            $ts02 = (float) $data['t02'];
            $max  = (float) $data['max'];

            // Kalkulasi Tarif Existing (Database)
            if ($max == 0) {
                $td02 = $td01 + $ts02;
                $td03 = $td02 + $ts02;
                $td04 = $td03 + $ts02;
                $td05 = $td04 + $ts02;
                $tdm5 = $td05 + $ts02;
            } else {
                $a = $td01 + $ts02;
                $td02 = ($a > $max) ? $max : $a;
                $b = $td02 + $ts02;
                $td03 = ($b > $max) ? $max : $b;
                $c = $td03 + $ts02;
                $td04 = ($c > $max) ? $max : $c;
                $d = $td04 + $ts02;
                $td05 = ($d > $max) ? $max : $d;
                $e = $td05 + $ts02;
                $tdm5 = ($e > $max) ? $max : $e;
            }
            $tf_D1 += $C1_val * $td01;
            $tf_D2 += $data['c0102'] * $td02;
            $tf_D3 += $data['c0203'] * $td03;
            $tf_D4 += $data['c0304'] * $td04;
            $tf_D5 += $data['c0405'] * $td05;
            $tf_Dm5 += $data['c05'] * $tdm5;

            // Kalkulasi Simulasi 1
            if ($yesno == 'flat') {
                $tfC1 = $tfC2 = $tfC3 = $tfC4 = $tfCm5 = $t_awal;
            } else { // Progresif
                if (empty($berlaku)) {
                    $tfC1 = $t_awal + $t_akhir;
                    $tfC2 = $tfC1 + $t_akhir;
                    $tfC3 = $tfC2 + $t_akhir;
                    $tfC4 = $tfC3 + $t_akhir;
                    $tfCm5 = $tfC4 + $t_akhir;
                } else {
                    // Logika masa berlaku (expire)
                    if ($expire <= '01:00:00') {
                        $tfC1 = $t_awal + $t_akhir;
                        $tfC2 = $tfC1 + $t_akhir;
                        $tfC3 = $tfC2 + $t_akhir;
                        $tfC4 = $tfC3 + $t_akhir;
                        $tfCm5 = $tfC4 + $t_akhir;
                    } elseif ($expire > '01:00:00' && $expire <= '02:00:00') {
                        $tfC1 = $t_awal;
                        $tfC2 = $t_awal + $t_akhir;
                        $tfC3 = $tfC2 + $t_akhir;
                        $tfC4 = $tfC3 + $t_akhir;
                        $tfCm5 = $tfC4 + $t_akhir;
                    } // ... tambahkan else if untuk jam berikutnya jika perlu
                    else {
                        $tfC1 = $t_awal;
                        $tfC2 = $t_awal;
                        $tfC3 = $t_awal;
                        $tfC4 = $t_awal;
                        $tfCm5 = $t_awal;
                    }
                }
                // Terapkan tarif max simulasi 1
                if ($t_max > 0) {
                    if ($tfC1 > $t_max) $tfC1 = $t_max;
                    if ($tfC2 > $t_max) $tfC2 = $t_max;
                    if ($tfC3 > $t_max) $tfC3 = $t_max;
                    if ($tfC4 > $t_max) $tfC4 = $t_max;
                    if ($tfCm5 > $t_max) $tfCm5 = $t_max;
                }
            }
            $tf_C1 += $C1_val * $t_awal; // Jam pertama selalu tarif awal
            $tf_C2 += $data['c0102'] * $tfC1;
            $tf_C3 += $data['c0203'] * $tfC2;
            $tf_C4 += $data['c0304'] * $tfC3;
            $tf_C5 += $data['c0405'] * $tfC4;
            $tf_Cm5 += $data['c05'] * $tfCm5;

            // Kalkulasi Simulasi 2 (jika ada input)
            if (!empty($t_ska)) {
                // Logika ini mirip dengan simulasi 1, tapi pakai variabel t_ska, t_ske, t_maxs
                if (empty($berlaku2)) {
                    $tfS1 = $t_ska + $t_ske;
                    $tfS2 = $tfS1 + $t_ske;
                    $tfS3 = $tfS2 + $t_ske;
                    $tfS4 = $tfS3 + $t_ske;
                    $tfSm5 = $tfS4 + $t_ske;
                } else {
                    // Logika masa berlaku 2
                    if ($expire2 <= '01:00:00') {
                        $tfS1 = $t_ska + $t_ske;
                        $tfS2 = $tfS1 + $t_ske;
                        $tfS3 = $tfS2 + $t_ske;
                        $tfS4 = $tfS3 + $t_ske;
                        $tfSm5 = $tfS4 + $t_ske;
                    } // ... dst
                    else {
                        $tfS1 = $t_ska;
                        $tfS2 = $t_ska;
                        $tfS3 = $t_ska;
                        $tfS4 = $t_ska;
                        $tfSm5 = $t_ska;
                    }
                }
                if ($t_maxs > 0) {
                    if ($tfS1 > $t_maxs) $tfS1 = $t_maxs;
                    if ($tfS2 > $t_maxs) $tfS2 = $t_maxs;
                    if ($tfS3 > $t_maxs) $tfS3 = $t_maxs;
                    if ($tfS4 > $t_maxs) $tfS4 = $t_maxs;
                    if ($tfSm5 > $t_maxs) $tfSm5 = $t_maxs;
                }
                $tf_S1 += $C1_val * $t_ska;
                $tf_S2 += $data['c0102'] * $tfS1;
                $tf_S3 += $data['c0203'] * $tfS2;
                $tf_S4 += $data['c0304'] * $tfS3;
                $tf_S5 += $data['c0405'] * $tfS4;
                $tf_Sm5 += $data['c05'] * $tfSm5;
            }
        }

        // Kalkulasi Total Akhir
        $tCgt = $tC1 + $tC2 + $tC3 + $tC4 + $tC5 + $tCm5;
        $sumtd = $tf_D1 + $tf_D2 + $tf_D3 + $tf_D4 + $tf_D5 + $tf_Dm5;
        $sumti = $tf_C1 + $tf_C2 + $tf_C3 + $tf_C4 + $tf_C5 + $tf_Cm5;
        $sumts = $tf_S1 + $tf_S2 + $tf_S3 + $tf_S4 + $tf_S5 + $tf_Sm5;
        $tdmti = $sumti - $sumtd;
        $tdmts = $sumts - $sumtd;

        $viewData = [
            'results' => $results,
            'inputs' => $request->all(),
            'tglAwal' => $tglAwal ?? '',
            'tglAkhir' => $tglAkhir ?? '',
            'td01' => $td01,
            'ts02' => $ts02,
            'max' => $max,
            'td02' => $td02,
            'td03' => $td03,
            'td04' => $td04,
            'td05' => $td05,
            'tdm5' => $tdm5,
            'tC1' => $tC1,
            'tC2' => $tC2,
            'tC3' => $tC3,
            'tC4' => $tC4,
            'tC5' => $tC5,
            'tCm5' => $tCm5,
            'tCgt' => $tCgt,
            'tf_D1' => $tf_D1,
            'tf_D2' => $tf_D2,
            'tf_D3' => $tf_D3,
            'tf_D4' => $tf_D4,
            'tf_D5' => $tf_D5,
            'tf_Dm5' => $tf_Dm5,
            'sumtd' => $sumtd,
            't_awal' => $t_awal,
            't_akhir' => $t_akhir,
            't_max' => $t_max,
            'tfC1' => $tfC1,
            'tfC2' => $tfC2,
            'tfC3' => $tfC3,
            'tfC4' => $tfC4,
            'tfCm5' => $tfCm5,
            'tf_C1' => $tf_C1,
            'tf_C2' => $tf_C2,
            'tf_C3' => $tf_C3,
            'tf_C4' => $tf_C4,
            'tf_C5' => $tf_C5,
            'tf_Cm5' => $tf_Cm5,
            'sumti' => $sumti,
            'tdmti' => $tdmti,
            't_ska' => $t_ska,
            't_ske' => $t_ske,
            't_maxs' => $t_maxs,
            'tfS1' => $tfS1,
            'tfS2' => $tfS2,
            'tfS3' => $tfS3,
            'tfS4' => $tfS4,
            'tfSm5' => $tfSm5,
            'tf_S1' => $tf_S1,
            'tf_S2' => $tf_S2,
            'tf_S3' => $tf_S3,
            'tf_S4' => $tf_S4,
            'tf_S5' => $tf_S5,
            'tf_Sm5' => $tf_Sm5,
            'sumts' => $sumts,
            'tdmts' => $tdmts,
        ];

        return view('simulasi-tarif', $viewData);
    }
}
