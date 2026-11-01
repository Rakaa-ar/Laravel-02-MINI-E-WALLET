<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dompet');
        }

        return back()->withErrors([
            'email' => 'Email and Password yang lu masukin salah.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        $otpCode = rand(100000, 999999);

        // Otomatis update kalau akun belum terverifikasi / buat baru kalau belum ada
        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'name'           => $request->name,
                'password'       => bcrypt($request->password),
                'otp'            => $otpCode,
                'otp_expires_at' => Carbon::now()->addMinutes(5),
                'is_verified'    => false,
            ]
        );

        // Wajib simpan email ke session biar halaman OTP bisa kebuka
        session(['otp_email' => $user->email]);
        session()->save();

        // Kirim Email OTP HTML
        Mail::send('emails.otp', ['user' => $user, 'otpCode' => $otpCode], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Verifikasi Akun');
        });

        return redirect()->route('otp.verify')->with('info', 'Masukkan 6 digit kode OTP yang dikirim ke Gmail.');
    }

    public function showOtpForm()
    {
        if (!session()->has('otp_email')) {
            return redirect('/register');
        }
        return view('auth.register_otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric'],
        ]);

        $email = session('otp_email');
        $user  = User::where('email', $email)->first();

        if (!$user || $user->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah!']);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP kedaluwarsa. Silakan daftar ulang.']);
        }

        // OTP Sukses: Set verified & hapus OTP
        $user->update([
            'otp'         => null,
            'is_verified' => true,
        ]);

        session()->forget('otp_email');

        // FLOW BALIK KE FORM LOGIN
        return redirect('/login')->with('success', 'Akun berhasil diverifikasi! Silakan login.');
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (!$user) {

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                ]);
            } else {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

            Auth::login($user);
            return redirect('/dompet');
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal Login dengan google. coba lagi');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // --- 1. FORM & PROSES KIRIM EMAIL OTP ---
    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email ini belum terdaftar.'
        ]);

        $user = User::where('email', $request->email)->first();
        $otpCode = rand(100000, 999999);

        // Update kolom OTP di tabel users
        $user->update([
            'otp'            => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Simpan sesi email buat lanjut ke form OTP
        session(['reset_email' => $user->email]);

        // Kirim Email OTP
        Mail::send('emails.reset_otp', ['user' => $user, 'otpCode' => $otpCode], function ($message) use ($user) {
            $message->to($user->email)->subject('Kode OTP Reset Password');
        });

        return redirect()->route('password.otp.form')->with('info', 'Kode OTP reset password udah dikirim ke Gmail lu.');
    }
    // --- 2. FORM & PROSES VERIFIKASI OTP ---
    public function showResetOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect('/forgot-password');
        }
        return view('auth.reset_otp');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'numeric']]);

        $email = session('reset_email');
        $user  = User::where('email', $email)->first();

        if (!$user || $user->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah!']);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP kedaluwarsa. Ulangi dari awal.']);
        }

        // Kalau OTP bener, kasih izin ke form password baru
        $user->update(['otp' => null]);
        session(['reset_otp_verified' => true]);

        return redirect()->route('password.reset')->with('info', 'OTP Valid! Silakan buat password baru.');
    }

    // --- 3. FORM & PROSES UPDATE PASSWORD ---
    public function showResetPasswordForm()
    {
        if (!session()->has('reset_otp_verified') || !session()->has('reset_email')) {
            return redirect('/forgot-password');
        }
        return view('auth.reset_password', ['email' => session('reset_email')]);
    }

    public function processResetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'password.confirmed' => 'Konfirmasi password nggak cocok.'
        ]);

        $user = User::where('email', session('reset_email'))->first();
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Bersihin sesi
        session()->forget(['reset_email', 'reset_otp_verified']);

        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login.');
    }
}
