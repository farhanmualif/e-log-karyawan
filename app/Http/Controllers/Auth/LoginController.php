<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                ->where('nik', $username)
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
                }
            }

            return false;
        }

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
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
