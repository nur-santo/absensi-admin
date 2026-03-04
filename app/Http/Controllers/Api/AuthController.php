<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordOtp;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Login API user
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Akun tidak aktif'], 403);
        }

        // Generate token
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }

    /**
     * Ambil data user yang login
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('shift');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'instansi' => $user->instansi,
            'status' => $user->status,
            'mode_kerja' => $user->mode_kerja,
            'shift' => $user->shift ? [
                'nama_shift' => $user->shift->nama_shift,
                'mulai' => $user->shift->mulai,
                'selesai' => $user->shift->selesai,
            ] : null,
        ]);
    }

    /**
     * Logout API user
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    





    public function requestOtp(Request $request)
    {
        try {
    
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ]);
    
            $email = $request->email;
    
            $otp = random_int(100000, 999999);
    
            PasswordOtp::where('email', $email)->delete();
    
            PasswordOtp::create([
                'email' => $email,
                'otp' => Hash::make($otp),
                'expired_at' => Carbon::now()->addMinutes(5)
            ]);
    
            Mail::raw("Kode OTP Anda adalah: $otp", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Kode OTP Reset Password')
                        ->from(
                            config('mail.from.address'),
                            config('mail.from.name')
                        );
            });
    
            return response()->json([
                'message' => 'OTP berhasil dikirim'
            ], 200);
    
        } catch (\Throwable $e) {
    
            Log::error($e->getMessage());
    
            return response()->json([
                'message' => 'Gagal mengirim OTP'
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $record = PasswordOtp::where('email', $request->email)->first();

        if (!$record) {
            return response()->json(['message' => 'OTP tidak ditemukan'], 400);
        }

        if (Carbon::now()->gt($record->expired_at)) {
            return response()->json(['message' => 'OTP sudah expired'], 400);
        }

        if (!Hash::check($request->otp, $record->otp)) {
            return response()->json(['message' => 'OTP salah'], 400);
        }

        return response()->json([
            'message' => 'OTP valid'
        ]);
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|confirmed'
        ]);

        $record = PasswordOtp::where('email', $request->email)->first();

        if (!$record ||
            Carbon::now()->gt($record->expired_at) ||
            !Hash::check($request->otp, $record->otp)
        ) {
            return response()->json(['message' => 'OTP tidak valid'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password; // auto hashed via cast
        $user->save();

        // Hapus OTP setelah dipakai
        $record->delete();

        return response()->json([
            'message' => 'Password berhasil diubah'
        ]);
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        // Generate password baru acak 8 karakter
        $newPassword = Str::random(8);

        /**
         * PENTING:
         * Jangan pakai Hash::make() karena di model User
         * sudah ada cast: 'password' => 'hashed'
         * Kalau di-hash lagi akan double hash dan gagal login.
         */
        $user->password = $newPassword;
        $user->save();

        return response()->json([
            'message' => 'Password berhasil direset',
            'new_password' => $newPassword
        ]);
    }
}