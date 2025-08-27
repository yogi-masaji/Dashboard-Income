<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class lupaPassword extends Controller
{
    // Menampilkan halaman form lupa password
    public function viewLupaPassword()
    {
        return view('lupapassword');
    }

    // Mengirim email link reset password
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi request
        $request->validate(['email' => 'required|email']);

        // 2. Cek apakah email ada di database (tabel ms_login)
        $user = DB::table('ms_login')->where('email_Staff', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
        }

        // 3. Hapus token lama jika ada
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // 4. Buat token baru
        $token = Str::random(60);

        // 5. Simpan token ke database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // 6. Kirim email ke pengguna
        try {
            Mail::send('emails.reset-password', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Notifikasi Reset Password Akun Anda');
            });
        } catch (\Exception $e) {
            // Jika email gagal terkirim
            return back()->withErrors(['email' => 'Gagal mengirim email reset password. Silakan coba lagi nanti.']);
        }

        return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda!');
    }

    // Menampilkan halaman form reset password
    public function viewResetPassword(Request $request)
    {
        $token = $request->route('token');

        // Cek apakah token valid
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)->first();

        // Jika token tidak ada atau sudah lebih dari 1 jam, tolak request
        if (!$tokenData || Carbon::parse($tokenData->created_at)->addHour()->isPast()) {
            return redirect()->route('login')->withErrors(['email' => 'Token reset password tidak valid atau telah kedaluwarsa.']);
        }

        return view('resetpassword', ['token' => $token, 'email' => $tokenData->email]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // 2. Cek apakah token dan email cocok
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return redirect()->route('login')->withErrors(['email' => 'Token atau email tidak valid.']);
        }

        // 3. Update password di tabel ms_login
        DB::table('ms_login')->where('email_Staff', $request->email)->update([
            'password_Staff' => Hash::make($request->password)
        ]);

        // 4. Hapus token dari database
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // 5. Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Password Anda telah berhasil direset! Silakan login.');
    }
}
