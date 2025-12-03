<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     * Override method dari AuthenticatesUsers trait untuk menggunakan username
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Validate the user login request.
     * Custom validation untuk login dengan username
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     * Custom credentials untuk login dengan username
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Attempt to log the user into the application.
     * Override untuk mengecek NIK di tabel pegawai dan auto-create user jika belum ada
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $username = $request->input($this->username());
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if (!$user) {
            $pegawai = DB::connection('mysql_khanza')
                ->table('pegawai')
                ->whereRaw('BINARY nik = ?', [$username])
                ->first();

            if ($pegawai) {
                if ($password === '12345') {
                    $user = User::create([
                        'name' => $pegawai->nama ?? 'User',
                        'username' => $username,
                        'email' => null,
                        'password' => Hash::make('12345'),
                        'password_changed' => false,
                        'role' => 'karyawan',
                    ]);

                    $this->guard()->login($user, $request->has('remember'));
                    return true;
                } else {
                    $request->session()->flash('login_error', 'ID User atau Password yang Anda masukkan salah');
                    return false;
                }
            } else {
                $request->session()->flash('login_error', 'ID User tidak ditemukan. Pastikan ID User yang Anda masukkan sama dengan yang digunakan di sistem Khanza.');
                return false;
            }
        }

        // User sudah ada, coba login dengan password
        $loginAttempt = $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );

        if (!$loginAttempt) {
            $request->session()->flash('login_error', 'User ID atau Password yang Anda masukkan salah. Silakan periksa kembali User ID dan password Anda.');
        }

        return $loginAttempt;
    }

    /**
     * Get the failed login response instance.
     * Override untuk memberikan pesan error yang lebih jelas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Ambil custom error message dari session jika ada
        $errorMessage = $request->session()->get('login_error', 'ID User atau password yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.');

        throw ValidationException::withMessages([
            $this->username() => [$errorMessage],
        ]);
    }

    /**
     * The user has been authenticated.
     * Custom logic setelah user berhasil login
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Contoh: Log aktivitas login
        // Activity::create([
        //     'user_id' => $user->id,
        //     'action' => 'login',
        //     'description' => 'User logged in',
        // ]);

        // Redirect berdasarkan role (contoh)
        // if ($user->role === 'admin') {
        //     return redirect('/admin/dashboard');
        // }

        // Redirect ke halaman ubah password jika masih menggunakan password default
        if ($user->is_default_password) {
            return redirect('/password/change');
        }

        return redirect()->intended($this->redirectPath());
    }
}
