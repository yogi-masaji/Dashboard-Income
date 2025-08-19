<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\MsLogin;
use App\Models\TrStaff;
use App\Models\MsStaff;
use App\Models\MsGroup;
use App\Models\lokasi;

class Login extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function LoginView()
    {
        return view('login');
    }

    /**
     * Menangani proses autentikasi pengguna.
     */
    public function authenticate(Request $request)
    {
        Log::info('Proses autentikasi dimulai untuk email: ' . $request->email);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator)->withInput();
        }

        try {
            // **MODIFICATION**: Query now includes all necessary fields from ms_lokasi
            $user = DB::table('ms_login as ml')
                ->join('ms_staff as ms', 'ml.id_Staff', '=', 'ms.id_Staff')
                ->join('ms_user_type as mut', 'ml.user_type_code', '=', 'mut.user_type_code')
                ->join('ms_lokasi as mk', 'ml.ip', '=', 'mk.ip_Lokasi')
                ->where('ml.email_Staff', $request->email)
                ->where('ml.user_active', 1)
                ->select(
                    'ml.*',
                    'ms.foto_profile',
                    'ms.nama_Staff',
                    'mut.user_type_name',
                    'mk.kode_Lokasi',
                    'mk.nama_Lokasi as site_name',
                    'mk.id_Lokasi',
                    'mk.id_Group',
                    'mk.ip_Lokasi', // Added for session
                    'mk.chisel_Version' // Added for session
                )
                ->first();

            if (!$user) {
                Log::warning('Login gagal: Pengguna tidak ditemukan untuk email: ' . $request->email);
                return back()->withErrors(['email' => 'Email atau Password yang Anda masukkan salah.'])->withInput();
            }

            $passwordMatch = password_verify($request->password, $user->password_Staff);

            if (!$passwordMatch) {
                DB::table('login_history')->insert([
                    'id_Staff' => $user->id_Staff,
                    'datetime_Login' => now(),
                    'status_Login' => 0
                ]);
                Log::warning('Login gagal: Password salah untuk email: ' . $request->email);
                return back()->withErrors(['email' => 'Email atau Password yang Anda masukkan salah.'])->withInput();
            }

            DB::table('login_history')->insert([
                'id_Staff' => $user->id_Staff,
                'datetime_Login' => now(),
                'status_Login' => 1
            ]);

            // ### PERUBAHAN DI SINI ###
            // Memperbarui status_login dan last_login saat pengguna berhasil login.
            DB::table('ms_login')->where('id_Staff', $user->id_Staff)->update([
                'status_login' => 1,
                'last_login' => now()
            ]);
            Log::info('Status login berhasil diupdate untuk id_Staff: ' . $user->id_Staff);

            // **MODIFICATION**: All session data is set here, including the selected_location_*
            $request->session()->put([
                'authentication'   => true,
                'status_login'     => 1,
                'id_Staff'         => $user->id_Staff,
                'nama_Staff'       => $user->nama_Staff,
                'user_type'        => $user->user_type_code,
                'user_type_name'   => $user->user_type_name,
                'foto_profile'     => $user->foto_profile,
                'ip'               => $user->ip,
                'system_loc'       => $user->default_system_loc,
                'kode_Lokasi'      => $user->kode_Lokasi,
                'site_name'        => $user->site_name,
                'lokasi'           => $user->lokasi,
                'id_Group'         => $user->id_Group,
                'selected_location_id' => $user->id_Lokasi,
                'selected_location_id_grup' => $user->id_Group,
                'selected_location_name' => $user->site_name,
                'selected_location_ip_lokasi' => $user->ip_Lokasi,
                'selected_location_kode_lokasi' => $user->kode_Lokasi,
                'selected_location_chisel_Version' => $user->chisel_Version,
            ]);

            // This part can be simplified if the primary logic is now based on selected_location_id_grup
            $userLocations = DB::table('group_lokasi')
                ->where('group_lokasi.id_Group', $user->id_Group)
                ->join('ms_lokasi', 'group_lokasi.id_Lokasi', '=', 'ms_lokasi.id_Lokasi')
                ->select('group_lokasi.*', 'ms_lokasi.nama_Lokasi', 'ms_lokasi.kode_Lokasi')
                ->get();

            if ($userLocations->count() > 1) {
                $request->session()->put('UserLocations', $userLocations);
            }

            $request->session()->regenerate();

            $redirectTarget = 'dailyTransaction';
            Log::info('Login berhasil. Mengalihkan ke route: ' . $redirectTarget);
            return redirect()->route($redirectTarget);
        } catch (\Exception $e) {
            Log::error('Terjadi exception saat proses login: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['email' => 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.'])->withInput();
        }
    }

    /**
     * Menangani proses logout pengguna.
     */
    public function logout(Request $request)
    {
        $id_Staff = $request->session()->get('id_Staff');

        if ($id_Staff) {
            DB::table('ms_login')->where('id_Staff', $id_Staff)->update(['status_login' => 0]);
            Log::info('Logout berhasil untuk id_Staff: ' . $id_Staff);
        }

        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('login');
    }


    public function formUserView()
    {
        return view('pages.formuser');
    }

    public function showDataUser()
    {
        $users = MsLogin::with('group')
            ->where('removed', 0)
            ->orderBy('id_Staff', 'desc')
            ->get();

        return response()->json($users);
    }

    /**
     * Mendapatkan ID staff baru.
     */
    public function showId()
    {
        $lastStaff = MsLogin::orderBy('id_Staff', 'desc')->first();
        $newId = $lastStaff ? $lastStaff->id_Staff + 1 : 1;
        return response()->json([['id_staff' => $newId]]);
    }

    /**
     * Menambahkan user baru.
     */
    public function insertUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:ms_login,email_Staff',
            'password' => 'required|string|min:6',
            'role' => 'required|integer',
            'location' => 'required|string',
            'ip' => 'required|string', // Validasi diubah ke string
            'system' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 400, 'message' => $validator->errors()->first()]);
        }

        try {
            DB::beginTransaction();

            $locationData = explode(',', $request->location);
            $id_Lokasi = $locationData[0];
            $nama_Lokasi = $locationData[1];

            $last_staff = MsLogin::orderBy('id_Staff', 'desc')->first();
            $new_staff_id = $last_staff ? $last_staff->id_Staff + 1 : 1;


            MsLogin::create([
                'id_Staff' => $new_staff_id,
                'email_Staff' => $request->email,
                'password_Staff' => password_hash($request->password, PASSWORD_ARGON2I),
                'user_type_code' => 'UC', // Hardcoded as in original script
                'lokasi' => $request->role,
                'site_name' => $nama_Lokasi,
                'ip' => $request->ip,
                'default_system_loc' => $request->system,
                'status_login' => 0,
                'user_active' => 1,
                'removed' => 0,
            ]);

            TrStaff::create([
                'id_Staff' => $new_staff_id,
                'id_Group' => $request->role,
                'id_Lokasi' => $id_Lokasi,
                'id_Registrant' => 1, // Assuming id_Registrant is 1 from session
                'id_removed' => 0,
            ]);

            MsStaff::create([
                'id_Staff' => $new_staff_id,
                'nama_Staff' => $request->nama,
                'foto_profile' => '', // Default empty
                'id_Registrant' => 1, // Assuming id_Registrant is 1 from session
            ]);

            DB::commit();
            return response()->json(['code' => 200, 'message' => 'Success input user']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error inserting user: ' . $e->getMessage());
            return response()->json(['code' => 500, 'message' => 'Failed to input user: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan data user spesifik untuk modal edit.
     */
    public function showUser(Request $request)
    {
        $user = MsLogin::where('id_Staff', $request->id)->first(['id_Staff', 'email_Staff']);
        return response()->json([$user]);
    }

    /**
     * Menampilkan lokasi default untuk user.
     */
    public function showDefault(Request $request)
    {
        $id = $request->id;
        $nama_group = $request->nama; // id_Group

        $user = MsLogin::find($id);

        if (!$user) {
            return response()->json([]);
        }

        $locations = DB::table('group_lokasi as gl')
            ->join('ms_lokasi as mls', 'gl.id_Lokasi', '=', 'mls.id_Lokasi')
            ->where('gl.id_Group', $user->lokasi)
            ->select('gl.id_Lokasi', 'mls.nama_Lokasi')
            ->orderBy('gl.id_Lokasi', 'asc')
            ->get()
            ->map(function ($item) use ($id) {
                $item->id_Staff = $id;
                return $item;
            });

        return response()->json($locations);
    }

    /**
     * Mengubah email dan password user.
     */
    public function changeUser(Request $request)
    {
        $user = MsLogin::find($request->e_id);
        if ($user) {
            $user->email_Staff = $request->e_email;
            if ($request->filled('e_password')) {
                $user->password_Staff = Hash::make($request->e_password);
            }
            $user->save();
            return response()->json(['message' => 'Update Berhasil']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Mengubah lokasi default user.
     */
    public function editDefault(Request $request)
    {
        $user = MsLogin::find($request->d_id);
        if ($user) {
            $locationData = explode(',', $request->e_location);
            $user->site_name = $locationData[1];
            $user->ip = $request->e_ip;
            $user->default_system_loc = $request->e_system;
            $user->save();
            return response()->json(['message' => 'Update Berhasil']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Menghapus user (soft delete).
     */
    public function deleteUser(Request $request)
    {
        try {
            DB::beginTransaction();

            MsLogin::where('id_Staff', $request->id)->update(['removed' => 1]);
            TrStaff::where('id_Staff', $request->id)->update(['id_removed' => 1]);
            // This is the line causing the error
            MsStaff::where('id_Staff', $request->id)->update(['id_removed' => 1]);

            DB::commit();
            return response()->json(['code' => 200, 'message' => 'Success delete user']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['code' => 500, 'message' => 'Failed to delete user']);
        }
    }

    /**
     * Mengambil daftar group.
     */
    public function showGroup()
    {
        $groups = MsGroup::whereNotIn('id_Group', [58])->orderBy('id_Group', 'asc')->get();
        return response()->json($groups);
    }

    /**
     * Mengambil daftar lokasi berdasarkan role/group.
     */
    public function roleUser(Request $request)
    {
        $role = $request->role;
        if ($role == 58) {
            $locations = lokasi::orderBy('nama_Lokasi', 'asc')->get(['id_Lokasi', 'nama_Lokasi']);
        } else {
            $locations = DB::table('group_lokasi as gl')
                ->join('ms_lokasi as ml', 'gl.id_Lokasi', '=', 'ml.id_Lokasi')
                ->where('gl.id_Group', $role)
                ->orderBy('ml.nama_Lokasi', 'asc')
                ->get(['gl.id_Lokasi', 'ml.nama_Lokasi']);
        }
        return response()->json($locations);
    }

    /**
     * Mencari IP berdasarkan ID Lokasi.
     */
    public function findIp(Request $request)
    {
        $location = lokasi::where('id_Lokasi', $request->id)
            ->first(['ip_Lokasi', 'system_code']);
        return response()->json([$location]);
    }




    public function userLoginHistoryView()
    {
        $userType = Session::get('user_type');
        $queryModifier = function ($query) use ($userType) {
            if ($userType == 'IC') {
                $query->where('ml.user_type_code', '!=', 'IT');
            } elseif ($userType != 'IT' && $userType != 'IC') {
                $query->where('ml.user_type_code', '=', 'UC');
            }
        };

        $totalUserQuery = DB::table('ms_login as ml')->join('ms_staff as ms', 'ms.id_Staff', '=', 'ml.id_Staff');
        $queryModifier($totalUserQuery);
        $totalUser = $totalUserQuery->count();

        $onlineUserQuery = DB::table('ms_login as ml')->join('ms_staff as ms', 'ms.id_Staff', '=', 'ml.id_Staff')->where('ml.status_login', 1);
        $queryModifier($onlineUserQuery);
        $onlineUser = $onlineUserQuery->count();

        $offlineUser = $totalUser - $onlineUser;

        $registeredUserQuery = DB::table('ms_login as ml')->join('ms_staff as ms', 'ms.id_Staff', '=', 'ml.id_Staff')->where('ms.id_Registrant', 1);
        $queryModifier($registeredUserQuery);
        $registeredUser = $registeredUserQuery->count();

        $userlogQuery = DB::table('ms_login as ml')
            ->join('ms_staff as ms', 'ms.id_Staff', '=', 'ml.id_Staff')
            ->join('ms_user_type as mut', 'ml.user_type_code', '=', 'mut.user_type_code') // Join untuk mendapatkan user_type_name
            ->select(
                'ml.id_Staff',
                'ml.email_Staff',
                'ml.lokasi',
                'ml.last_login',
                'mut.user_type_name', // Ambil user_type_name
                'ml.status_login', // ### PERUBAHAN DI SINI ### Mengambil status login langsung dari database.
                'ms.nama_Staff',
                'ms.foto_profile',
                'ms.id_Registrant',
                DB::raw('(SELECT mls.nama_Lokasi FROM ms_lokasi mls WHERE mls.id_Lokasi = ml.lokasi LIMIT 1) as default_location'),
                DB::raw('(SELECT COUNT(lh.id_Staff) FROM login_history lh WHERE lh.id_Staff = ms.id_Staff AND MONTH(lh.datetime_Login) = MONTH(CURRENT_DATE()) AND YEAR(lh.datetime_Login) = YEAR(CURRENT_DATE())) as total_login')
            );

        if ($userType == 'IT') {
            $userlogQuery->join('tr_staff as ts', 'ts.id_Staff', '=', 'ms.id_Staff')
                ->join('ms_group as mg', 'mg.id_Group', '=', 'ts.id_Group')
                ->addSelect('mg.id_Group', 'mg.nama_Group');
        }

        $queryModifier($userlogQuery);
        $userlog_list = $userlogQuery->orderBy('total_login', 'desc')->get();

        // Hanya ambil data login bulan pertama untuk tampilan awal
        $firstUserId = $userlog_list->first()->id_Staff ?? 0;
        $totalLoginMonth = $this->getMonthlyLoginDataForUser($firstUserId);

        $listGroupUser = MsGroup::where('id_Registrant', 1)->get();

        return view('pages.userlogin', compact(
            'totalUser',
            'onlineUser',
            'offlineUser',
            'registeredUser',
            'userlog_list',
            'totalLoginMonth',
            'listGroupUser'
        ));
    }

    /**
     * Mengambil data login bulanan untuk pengguna tertentu.
     */
    private function getMonthlyLoginDataForUser($staffId)
    {
        if ($staffId == 0) return collect();

        return DB::table('login_history as lh')
            ->where('lh.id_Staff', $staffId)
            ->where('lh.status_Login', 1)
            ->whereYear('lh.datetime_Login', date('Y'))
            ->select(
                DB::raw("COUNT(lh.id_Staff) as total_login"),
                DB::raw("DATE_FORMAT(lh.datetime_Login, '%M %Y') as period"),
                DB::raw("DATE_FORMAT(lh.datetime_Login, '%Y-%m') as period_order") // for sorting
            )
            ->groupBy('period', 'period_order')
            ->orderBy('period_order', 'asc')
            ->get();
    }


    /**
     * API endpoint untuk mengambil riwayat login bulanan pengguna.
     */
    public function getUserMonthlyLogins(Request $request, $id)
    {
        $loginHistory = $this->getMonthlyLoginDataForUser($id);
        return response()->json($loginHistory);
    }

    /**
     * Memperbarui grup pengguna.
     */
    public function updateUserGroup(Request $request)
    {
        $request->validate([
            'idStaff' => 'required|integer',
            'groupUser' => 'required|integer',
        ]);

        TrStaff::where('id_Staff', $request->idStaff)
            ->update(['id_Group' => $request->groupUser]);

        return redirect()->route('user.login.history')->with('success', 'User group has been updated successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('pages.changepassword');
    }

    public function changePassword(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencocokkan dengan 'new_password_confirmation'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'title' => 'Validation Error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // 2. Dapatkan pengguna dari Session, bukan dari Auth::user()
        $userId = Session::get('id_Staff');

        // Cek jika ID pengguna ada di session
        if (!$userId) {
            return response()->json([
                'success' => false,
                'title' => 'Unauthorized',
                'message' => 'Sesi Anda tidak valid atau telah berakhir. Silakan login kembali.'
            ], 401);
        }

        // Ambil data user dari database menggunakan ID dari session
        $user = MsLogin::find($userId);

        // Cek jika user ditemukan di database
        if (!$user) {
            return response()->json([
                'success' => false,
                'title' => 'User Not Found',
                'message' => 'Pengguna dengan sesi ini tidak ditemukan di database.'
            ], 404);
        }


        // 3. Cek apakah password saat ini cocok
        if (!Hash::check($request->current_password, $user->password_Staff)) {
            return response()->json([
                'success' => false,
                'title' => 'Password Salah',
                'message' => 'Password Anda saat ini tidak cocok.'
            ], 401);
        }

        // 4. Update password baru
        try {
            // Model user sudah didapatkan, jadi bisa langsung digunakan
            $user->password_Staff = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'title' => 'Berhasil',
                'message' => 'Password Anda telah berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengubah password untuk user ID: ' . $user->id_Staff . ' - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'title' => 'Kesalahan Server',
                'message' => 'Terjadi kesalahan saat memperbarui password Anda. Silakan coba lagi.'
            ], 500);
        }
    }
}
