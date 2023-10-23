<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Session;
use App\Core\Entities\Admin\mhr;
use App\Core\Entities\Admin\Auditoria;
use App\Rules\VerificaGoogle;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function logTXT()
    {
        $hoy = date("Y-m-d H:i:s");
        //  $fichero = public_path().'/'.'log.txt';
        $persona =  $hoy . '|' . Auth::user()->nombreCompleto;
        // file_put_contents($fichero, $persona.PHP_EOL, FILE_APPEND | LOCK_EX);
        $cqlLogs = new Logs();
        $cqlLogs->nombreCompleto = Auth::user()->nombreCompleto;
        $cqlLogs->fecha_inserta = $hoy;
        $cqlLogs->save();
    }
    protected function sendLoginResponse(Request $request)
    {
        dd($request);
        $request->session()->regenerate();
        $previous_session = Auth::User()->session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id = \Session::getId();
        Auth::user()->save();

        $this->logTXT();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    public function username()
    {
        return 'name';
    }

    protected function validateLogin(Request $request)
    {
        // dd(1);
        request()->validate([
            'token_google' => new VerificaGoogle(),
        ]);
    }
}
