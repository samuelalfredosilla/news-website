<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite; // Impor Socialite
use App\Models\User;                    // Impor Model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role; // Impor Role jika ingin langsung menugaskan peran

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Dapatkan informasi pengguna dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah terdaftar dengan google_id ini
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Jika belum ada user dengan google_id ini, cek berdasarkan email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Jika email sudah terdaftar, update user yang ada dengan google_id
                    $user->google_id = $googleUser->id;
                    $user->avatar = $googleUser->avatar;
                    $user->email_verified_at = now(); // Asumsikan email dari Google sudah terverifikasi
                    $user->save();
                } else {
                    // Jika email juga belum terdaftar, buat user baru
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'email_verified_at' => now(), // Email dari Google dianggap sudah terverifikasi
                        'password' => Hash::make(\Illuminate\Support\Str::random(16)), // Buat password random, tidak akan digunakan jika selalu login via Google
                    ]);

                    // Opsional: Tetapkan peran default untuk pengguna baru dari Google
                    // Misalnya, beri peran 'wartawan' secara default
                    $wartawanRole = Role::where('name', 'wartawan')->first();
                    if ($wartawanRole) {
                        $user->assignRole($wartawanRole);
                    }

                    // Jika Anda menggunakan fitur verifikasi email Laravel Breeze,
                    // Anda bisa memicu event Registered di sini jika diperlukan untuk workflow lain.
                    // event(new Registered($user));
                }
            }

            // Login user
            Auth::login($user);

            // Redirect ke dashboard atau halaman utama setelah login
            return redirect()->intended(RouteServiceProvider::HOME);

        } catch (\Exception $e) {
            // Tangani error, misal jika user membatalkan otentikasi atau ada masalah koneksi
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi atau login dengan cara lain. Error: ' . $e->getMessage());
        }
    }
}