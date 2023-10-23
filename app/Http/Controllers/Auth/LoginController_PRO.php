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
use App\Core\Entities\Admin\Role;
use App\Core\Entities\Admin\Logs;
use PragmaRX\Google2FA\Google2FA;
use App\Core\Entities\Admin\Institucion;
use App\Rules\VerificaGoogle;
use App\Http\Controllers\Ajax\SelectController;

class LoginController_PRO extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $this->clearLoginAttempts($request);
        $validaActive = $this->validaActiveDirectory($request);

        $user = User::where($this->username(), '=', $request->name)->first();

        //    dd($validaActive);
        if ($validaActive['status']) {

            if ($user->token_expire == false) {
                $request->session()->regenerate();

                Auth::login($user);
                $this->logTXT();
                return redirect()->intended($this->redirectPath());
            }

            $generado = null;
            if ($user->valida_qr == false) {
                $generado = 1;
                $token = (new Google2FA)->generateSecretKey();
                //  dd($token,$user);
                $user->token_login = $token;
                $user->save();
            }
            $token = $user->token_login;

            $urlQR = 'data:image/png;base64,' . base64_encode($this->createUserUrlQR($user));

            return view("auth.2fa", compact('urlQR', 'user', 'generado', 'token'));
        } else {
            return redirect()->back()->withErrors(['error' => $validaActive['message']]);
        }


        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    public function createUserUrlQR($user)
    {
        $url =
            (new Google2FA)->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $user->token_login
            );

        $bacon = \QrCode::format('png')->size(300)->generate($url);

        return $bacon;
    }
    public function login2FA(Request $request, User $user)
    {
        $request->validate(['code_verification' => 'required']);
        if ((new Google2FA())->verifyKey($user->token_login, $request->code_verification)) {
            if ($user->valida_qr == false) {
                $user->valida_qr = true;
                $user->save();
            }

            $request->session()->regenerate();

            Auth::login($user);

            return redirect()->intended($this->redirectPath());
        }
        return redirect()->back()->withErrors(['error' => 'Código de verificación incorrecto']);
    }
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
    /*  protected $maxAttempts = 1; // Default is 5
    protected $decayMinutes = 1; // Default is 1
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
        //     $fichero = public_path().'/'.'log.txt';
        $persona =  $hoy . '|' . Auth::user()->nombreCompleto;
$nombre_completo=Auth::user()->nombreCompleto;
        $cqlLogs = new Logs();
        $cqlLogs->nombreCompleto = is_null($nombre_completo)||$nombre_completo==''?Auth::user()->name:$nombre_completo;
        $cqlLogs->fecha_inserta = $hoy;
        $cqlLogs->ip_registro = $this->verificarIP();
        $cqlLogs->save();
        //  file_put_contents($fichero, $persona.PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $previous_session = Auth::User()->session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id_anterior = $previous_session;
        Auth::user()->session_id = \Session::getId();
        Auth::user()->save();



        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }


    public function username()
    {
        return 'name';
    }
    protected function verificarIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    protected function validateLogin(Request $request)
    {
        //     $this->validate($request, [ 'name' => 'required|exists:users,' . 'name' . ',estado,A', 'password' => 'required', ]);
        //dd($request);
        // dd(1);
        request()->validate([
            'token_google' => new VerificaGoogle(),
        ]);
    }
    protected function agregarRolBase($usuario)
    {

        $user = User::find($usuario);
        $objSelect = new SelectController();
        $objSelect->logsCRUDRegistro('SERVIDOR', $user, 'ASIGNACION ROL');
        $user->syncRoles(['SERVIDOR']);
        return true;
    }
    protected function consultarIP()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        $ip = $ipaddress;
        return $ip;
    }
    protected function validarPorBaseDeDatos($username, $password, $cqlInstitucion, $user)
    {
        $objSelect = new SelectController();
        if ($user) {
            $objSelect = new SelectController();

            $consultaIPS = $objSelect->validaIPEquipo($this->consultarIP(), $user->identificacion);
            if (!$consultaIPS) return false;

            $pass = \Hash::check($password, $user->password);
            if ($pass) {
                $this->guard()->login($user, true);
                return true;
            }
            return false;
        } else
            return false;
    }

    protected function attemptLogin(Request $request)
    {
        $departamento = '';
        $cedula = "";
        $nombre = "";
        $cargo = "";
        $departamento = "";
        $mail = "";
        $extension = "";
        $ldaphost = "presidencia.int";
        $cadenaActiva = "dc=presidencia,dc=int";
        $cadenaBusquedaActiva = 'samaccountname=';
        $objSelect = new SelectController();
        $cqlInstitucion = Institucion::where('nivel', 0)->get()->first();
        $cqlInstitucion = $cqlInstitucion != null ? $cqlInstitucion->id : null;

        $credentials = $request->only($this->username(), 'password');
        $username = $credentials[$this->username()];
        $password = $credentials['password'];
        $username = $objSelect->eliminar_acentos_minuscula($username);
        $User = $username;
        $ldappass = $password;
        $user = \App\User::where($this->username(), $username)->where('estado', 'A')->first();

        $user_institucion_id = $user != null ? $user->institucion_id : $cqlInstitucion;
        if (!env('LOGIN_ACTIVE') || ($user_institucion_id != $cqlInstitucion))
            return $this->validarPorBaseDeDatos($username, $password, $cqlInstitucion, $user);

        $ldapconn = ldap_connect($ldaphost, 389);
        if (!$ldapconn) return false;

        $ldaprdn = $username . "@presidencia.int";
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if ($ldapconn) {
            $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);

            if ($ldapbind == false) return false;
            if ($ldapbind) {
                $res = ldap_search($ldapconn, $cadenaActiva, $cadenaBusquedaActiva . $username);
                $user_info = ldap_get_entries($ldapconn, $res);

                if ($user_info["count"] > 0) {
                    if (!array_search('postalcode', $user_info[0])) return false;
                    $cedula = $user_info[0]["postalcode"][0];

                    if (array_search('physicaldeliveryofficename', $user_info[0])) $cargo = $user_info[0]["physicaldeliveryofficename"][0];
                    if (array_search('name', $user_info[0])) $nombre = $user_info[0]["name"][0];
                    if (array_search('telephonenumber', $user_info[0]))  $extension = $user_info[0]["telephonenumber"][0];
                    if (array_search('department', $user_info[0]))  $departamento = $user_info[0]["department"][0];
                    $mail = $username . '@presidencia.gob.ec';
                } else
                    return false;

                $nombreCompleto = "CN=" . $nombre . ",CARGO=" . $cargo . ",CEDULA=" . $cedula;

                $consultaIPS = $objSelect->validaIPEquipo($this->consultarIP(), $cedula);
                if (!$consultaIPS) return false;

                if (!$user) {
                    $user = \App\User::where('identificacion', $cedula)->first();

                    if (!$user) {
                        $user = new \App\User();
                        $user->name = $username;
                        $user->email = $mail;
                        $user->nombreCompleto = $nombreCompleto;
                        $user->nombres = $nombre;
                        $user->identificacion = $cedula;
                        $user->departamento = $departamento;
                        $user->password = $password;
                        $user->extension = $extension;
                        $user->institucion_id = $cqlInstitucion;
                        $user->save();

                        $objSelect->logsCRUDRegistro('ALTA', $user, 'CREACION DE USUARIO');

                        $cqlRoleUser = $this->agregarRolBase($user->id);
                    }
                }
                if ($user) {
                    $hoy = date("Y-m-d H:i:s");
                    $user = \App\User::find($user->id);
                    $user->last_login = $hoy;


                    $user->nombreCompleto = $nombreCompleto;
                    $user->departamento = $departamento;
                    $user->password = $password;
                    $user->ultima_ip = $this->consultarIP();
                    $user->extension = $extension;
                    $user->save();
                }

                $this->guard()->login($user, true);
                return true;
            } else {
                return false;
            }
        }
        ldap_close($ldapconn);

        return false;
    }
    protected function validaActiveDirectory($request)
    {
        $departamento = '';
        $cedula = "";
        $nombre = "";
        $cargo = "";
        $departamento = "";
        $mail = "";
        $extension = "";
        $ldaphost = "presidencia.int";
        $cadenaActiva = "dc=presidencia,dc=int";
        $cadenaBusquedaActiva = 'samaccountname=';
        $objSelect = new SelectController();
        $cqlInstitucion = Institucion::where('nivel', 0)->get()->first();
        $cqlInstitucion = $cqlInstitucion != null ? $cqlInstitucion->id : null;
        $credentials = $request->only($this->username(), 'password');

        $username = $credentials[$this->username()];
        $password = $credentials['password'];
        $username = $objSelect->eliminar_acentos_minuscula($username);
        $User = $username;
        $ldappass = $password;

        $user = \App\User::where($this->username(), $username)->where('estado', 'A')->first();


        $user_institucion_id = $user != null ? $user->institucion_id : $cqlInstitucion;
        if (!config('app.LOGIN_ACTIVE') || ($user_institucion_id != $cqlInstitucion)) {
            //     dd(1,env('LOGIN_ACTIVE'));
            $validandoSesion = $this->validarPorBaseDeDatos($username, $password, $cqlInstitucion, $user);
            if ($validandoSesion) {
                $array_response['status'] = true;
                $array_response['message'] = 'Conección exitosa';
            } else {
                $array_response['status'] = false;
                $array_response['message'] = 'Error de credenciales';
            }


            return $array_response;
        }

        $ldapconn = ldap_connect($ldaphost, 389);
        if (!$ldapconn) {
            $array_response['status'] = false;
            $array_response['message'] = 'Error de conexión con el Servidor';

            return $array_response;
        }

        $ldaprdn = $username . "@presidencia.int";
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        if ($ldapconn) {
            $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);

            if ($ldapbind == false) {
                $array_response['status'] = false;
                $array_response['message'] = 'Error de credenciales ingresadas, contactese con soporte técnico para restaurar su clave';

                return $array_response;
            }
            if ($ldapbind) {

                $res = ldap_search($ldapconn, $cadenaActiva, $cadenaBusquedaActiva . $username);
                $user_info = ldap_get_entries($ldapconn, $res);
                ///dd($user_info);
                /*CONSULTA ACTIVE*/
                $data = $this->rCountRemover($user_info);
                foreach ($data as $key => $value) {

                    $user_display_name = ucfirst($value['displayname'][0]);
                    $username = $value['samaccountname'][0];
                    //  $user_email = $value['mail'][0];

                    // Get the time since last password change
                    $fileTime         = $value['pwdlastset'][0];
                    $winSecs          = (int)($fileTime / 10000000); // divide by 10 000 000 to get seconds
                    $unixTimestamp     = ($winSecs - 11644473600); // 1.1.1600 -> 1.1.1970 difference in seconds
                    $timestamp = date(\DateTime::RFC822, $unixTimestamp);  // time passed since Last Password Change

                    $date2 = date_create($timestamp);
                    $datetime2 = date_format($date2, 'Y-m-d');

                    $since = strtotime($datetime2);
                    $today = time();
                    $difference = $today - $since;
                    $expire_days =  floor($difference / (60 * 60 * 24));
                    $remaining_days = 42 - $expire_days;

                    // mail($to, $subject, $message, $headers, '-fserver_email@example.com');

                }

                $clave_expira = date("Y-m-d", strtotime($date2->format("Y-m-d") . "+ 42 days"));
                //  dd($user_info,$expire_days,$remaining_days,$since,$date2->format("Y-m-d"),$difference,$start);

                /*CONSULTA ACTIVE*/




                if (!array_search('postalcode', $user_info[0])) {
                    $array_response['status'] = false;
                    $array_response['message'] = 'Error de cédula no registrada en nuestros datos, contactese con soporte técnico';

                    return $array_response;
                }
                if ($user_info["count"] > 0) {
                    if (!array_search('postalcode', $user_info[0])) {
                        $array_response['status'] = false;
                        $array_response['message'] = 'Error de cédula no registrada en nuestros datos, contactese con soporte técnico';

                        return $array_response;
                    }

                    $cedula = $user_info[0]["postalcode"][0];

                    if (array_search('physicaldeliveryofficename', $user_info[0])) $cargo = $user_info[0]["physicaldeliveryofficename"][0];
                    if (array_search('name', $user_info[0])) $nombre = $user_info[0]["name"][0];
                    if (array_search('telephonenumber', $user_info[0]))  $extension = $user_info[0]["telephonenumber"][0];
                    if (array_search('department', $user_info[0]))  $departamento = $user_info[0]["department"][0];
                    $mail = $username . '@presidencia.gob.ec';
                } else {
                    $array_response['status'] = false;
                    $array_response['message'] = 'Faltan datos por registrar en nuestros datos, contactese con soporte técnico';

                    return $array_response;
                }


                $nombreCompleto = "CN=" . $nombre . ",CARGO=" . $cargo . ",CEDULA=" . $cedula;

                $consultaIPS = $objSelect->validaIPEquipo($this->consultarIP(), $cedula);

                if (!$consultaIPS) {
                    $array_response['status'] = false;
                    $array_response['message'] = 'No tiene acceso para registrarse en este equipo';

                    return $array_response;
                }
                if (!$user) {
                    $user = \App\User::where('identificacion', $cedula)->first();

                    if (!$user) {
                        $user = new \App\User();
                        $user->name = $username;
                        $user->email = $mail;
                        $user->nombreCompleto = $nombreCompleto;
                        $user->nombres = $nombre;
                        $user->identificacion = $cedula;
                        $user->departamento = $departamento;
                        $user->password = $password;
                        $user->extension = $extension;
                        $user->institucion_id = $cqlInstitucion;
                        $user->clave_expira = $clave_expira;
                        $user->save();
                        $objSelect->logsCRUDRegistro('ALTA', $user, 'CREACION DE USUARIO');

                        $cqlRoleUser = $this->agregarRolBase($user->id);
                    }
                }
                if ($user) {
                    $hoy = date("Y-m-d H:i:s");
                    $user = \App\User::find($user->id);
                    $user->last_login = $hoy;


                    $user->nombreCompleto = $nombreCompleto;
                    $user->departamento = $departamento;
                    $user->password = $password;
                    $user->ultima_ip = $this->consultarIP();
                    $user->extension = $extension;
                    $user->clave_expira = $clave_expira;
                    $user->save();
                }
                $array_response['status'] = true;
                $array_response['message'] = 'Conexión Exitosa';

                return $array_response;
            } else {
                $array_response['status'] = false;
                $array_response['message'] = 'Error de credenciales ingresadas, contactese con soporte técnico para restaurar su clave';

                return $array_response;
            }
        }
        ldap_close($ldapconn);

        return false;
    }
    protected function rCountRemover($arr)
    {
        foreach ($arr as $key => $val) {
            // (int)0 == "count", so we need to use ===
            if ($key === "count")
                unset($arr[$key]);
            elseif (is_array($val))
                $arr[$key] = $this->rCountRemover($arr[$key]);
        }
        return $arr;
    }
    /*protected function authenticated(Request $request,$user){
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());
    }*/
}
