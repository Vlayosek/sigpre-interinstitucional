<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Core\Entities\Admin\Webservice;
use App\Core\Entities\Admin\parametro_ciudad;

use App\Core\Entities\Portal\Area as Departamento;
use App\Core\Entities\Portal\Subusuario_;
use App\Core\Entities\Portal\ReunionCiudadana\Encuesta;
use App\Core\Entities\Portal\ReunionCiudadana\Reunion;

use App\Core\Entities\Inventario\Producto;
use App\Core\Entities\Inventario\BienTecnologico;
use App\Core\Entities\InventarioSoporte\BienTecnologico as BT;
use App\Core\Entities\InventarioSoporte\AdaptadorRed;
use App\Core\Entities\InventarioSoporte\Disco;
use App\Core\Entities\InventarioSoporte\PersonaBienTecnologico;
use App\Core\Entities\InventarioSoporte\EstadoBienTecnologico;
use App\Core\Entities\InventarioSoporte\Software;
use App\Core\Entities\TalentoHumano\Distributivo\Persona as Persona_;
use App\Http\Controllers\Ajax\SelectController;
use App\Core\Entities\Admin\EncuestaUsuario;
use Illuminate\Support\Facades\Redirect;
use App\User;
use SoapClient;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function index()
    {
        //    dd($this->prueba());
        $data = [];
        $tipo = config('app_encuesta_usuario.tipo');
        $descripcion_encuesta = config('app_encuesta_usuario.descripcion_encuesta');
        $url = config('url');
        if (Auth::user() != null) {
            $contadorGeneral = EncuestaUsuario::where('tipo', $tipo)->get()->count();
            $contadorEncuesta = 1;
            if ($contadorGeneral < config('app_encuesta_usuario.encuesta_activa'))
                $contadorEncuesta = EncuestaUsuario::where('identificacion', Auth::user()->identificacion)->where('tipo', $tipo)->get()->count();
            $habilita_si_no = config('app_encuesta_usuario.habilita_si_no');
            return view('home', compact('contadorEncuesta', 'descripcion_encuesta', 'habilita_si_no'));
        } else {
            //return redirect()->away($url);
            return Redirect::to($url);
        }
    }
    public function grabarUsuarioEncuesta(request $request)
    {
        $tipo = config('app_encuesta_usuario.tipo');

        if (config('app_encuesta_usuario.encuesta_activa') == 0) {
            $array_response['status'] = '200';
            $array_response['message'] = 'Link no esta disponible';
            return response()->json($array_response, 200);
        }


        $cql = Persona_::select(
            'apellidos_nombres',
            'id',
            'correo_institucional'
        )
            ->with(['historial_completo' => function ($q) {
                $q->with(['area', 'cargo']);
            }])
            ->where('identificacion', Auth::user()->identificacion)
            ->limit(1)
            ->first();
        //->get()

        //->toArray();
        //dd($apellidos_nombres);
        $funcionario = '--';
        $correo_institucional = '--';
        $area = '--';
        $cargo = '--';
        $historial = null;
        $historial_id = 0;

        if ($cql != null) {
            $funcionario = $cql->apellidos_nombres;
            $correo_institucional = $cql->correo_institucional;
            $historial = $cql->historial_completo != null ? $cql->historial_completo[0] : null;
            if ($historial != null) {
                $historial_id = $historial->id;
                $area = $historial->area != null ? $historial->area->nombre : '--';
                $cargo = $historial->cargo != null ? $historial->cargo->nombre : '--';
            }
        }


        $conteoEncuesta = EncuestaUsuario::where('tipo', $tipo)->get()->count();
        if ($conteoEncuesta < config('app_encuesta_usuario.encuesta_activa')) {
            $cqlRegistro = new EncuestaUsuario();
            $cqlRegistro->tipo = $tipo;
            $cqlRegistro->area = $area;
            $cqlRegistro->cargo = $cargo;
            $cqlRegistro->correo_institucional = $correo_institucional;
            $cqlRegistro->funcionario = $funcionario;
            $cqlRegistro->usuario_inserta = Auth::user()->name;
            $cqlRegistro->usuario_inserta_id = Auth::user()->id;
            $cqlRegistro->identificacion = Auth::user()->identificacion;
            $cqlRegistro->historia_laboral_id = $historial_id;
            $cqlRegistro->descripcion = $request->descripcion;
            $cqlRegistro->save();
        } else {
            $array_response['status'] = '200';
            $array_response['message'] = 'Se ha superado el numero de inscritos';
        }


        $array_response['status'] = '200';
        $array_response['message'] = 'Grabado Exitosamente';

        return response()->json($array_response, 200);
    }
    public function consultaWebService(request $request)
    {
        $cql = Webservice::where('mac', 'LIKE', '%' . trim($request->mac, " ") . '%')->get()->first();
        if ($cql == null) {
            $array_response['status'] = '300';
            $array_response['message'] = 'No se encontro datos';
        } else {
            $array_response['status'] = '200';
            $array_response['message'] = $cql;
        }

        return response()->json($array_response, 200);
    }
    public function grabarUsuarioAtencionCiudadana(request $request)
    {

        if (is_null($request->inp_lyt_reunion_ciudadana_encuesta_id))
            $cqlConsulta = new Encuesta();
        else
            $cqlConsulta = Encuesta::find($request->inp_lyt_reunion_ciudadana_encuesta_id);

        $cqlConsulta->reunion_id1 = $request->inp_lyt_reunion_ciudadana__id;
        $cqlConsulta->fecha = date('Y-m-d H:i:s');
        $cqlConsulta->punto_atencion = $request->inp_lyt_reunion_ciudadana_1;
        $cqlConsulta->edad = $request->inp_lyt_reunion_ciudadana_2;
        $cqlConsulta->satisfaccion_disponibilidad = $request->inp_lyt_reunion_ciudadana_3;
        $cqlConsulta->satisfaccion_acceso = $request->inp_lyt_reunion_ciudadana_4;
        $cqlConsulta->satisfaccion_utilizacion = $request->inp_lyt_reunion_ciudadana_5;
        $cqlConsulta->save();

        $cqlReunion = Reunion::find($cqlConsulta->reunion_id1);
        if ($cqlReunion != null) {
            $cqlReunion->estado_reunion_id1 = 527;
            $cqlReunion->save();
        }
        $url = 'https://portal2.presidencia.gob.ec/';
        return redirect()->away($url);
        // return Redirect::to($url);
        $array_response['status'] = '200';
        $array_response['message'] = 'Grabado  Exitoso';

        return response()->json($array_response, 200);
    }

    public function provincias()
    {
        $cql = parametro_ciudad::with(['lista_detalle' => function ($q) {
            $q->with(['lista_detalle']);
        }])->where('verificacion', 'PROVINCIA')->orderby('id', 'asc')->get();
        if ($cql == null) {
            $array_response['status'] = '300';
            $array_response['message'] = 'No se encontro datos';
        } else {
            $array_response['status'] = '200';
            $array_response['message'] = $cql;
        }

        return $array_response;
    }
    public function cantonParroquia($busqueda)
    {
        $busqueda = explode(",", $busqueda);
        $cql = parametro_ciudad::with(['lista_detalle'])->whereIn('id', $busqueda)->get();
        if ($cql == null) {
            $array_response['status'] = '300';
            $array_response['message'] = 'No se encontro datos';
        } else {
            $array_response['status'] = '200';
            $array_response['message'] = $cql;
        }
        return $array_response;
    }
    public function webservice(
        $mac,
        $ip,
        $procesador,
        $disco,
        $memoria,
        $host,
        $sistema,
        $modelo,
        $id
    ) {

        $cql = Webservice::where('mac', 'LIKE', '%' . $mac . '%')->get()->first();
        if ($cql == null) {
            $consulta = new Webservice();
        } else {
            $consulta = Webservice::find($cql->id);
        }

        $consulta->host = $host;

        $consulta->mac = $mac;
        $consulta->ip = $ip;
        $consulta->procesador = $procesador;
        $consulta->disco = $disco;
        $consulta->memoria = $memoria;
        $consulta->sistema = $sistema;
        $consulta->modelo = $modelo;
        $consulta->producto_id = $id;
        $consulta->save();

        $cql = Producto::find(trim($id));
        if ($cql == null)
            return false;
        if ($cql->check_tecnologia != 1)
            return false;
        $cql->direccion_ip = $ip;
        $cql->direccion_mac = $mac;
        $cql->save();

        $cqlBT = BienTecnologico::find($cql->bien_tecnologico_id);
        $cqlBT->ip = $ip;
        $cqlBT->mac = $mac;
        $cqlBT->procesador = $procesador;
        $cqlBT->memoria = $memoria;
        $cqlBT->disco = $disco;
        $cqlBT->modelo = $modelo;
        $cqlBT->host = $host;
        $cqlBT->sistema = $sistema;
        $cqlBT->save();

        return;
    }
    public function webserviceSoporte(
        $ip,
        $memoria,
        $procesador,
        $disco,
        $usuario,
        $arquitectura,
        $sistema,
        $version,
        $serie,
        $host,
        $cedula,
        $modelo,
        $tipo,
        $dominio,
        $chasis
    ) {
        $cedula = trim($cedula);
        $objSelect = new SelectController();

        $cqlPersona = $objSelect->buscarDatosUath($cedula);

        $departamento = '';
        $nombres = '';
        $correo = '';
        $persona_id = 0;
        $departamento_id = 0;

        if ($cqlPersona != null) {
            $persona_id = $cqlPersona['persona_id'];
            $nombres = $cqlPersona['nombres_'];
            $correo = $cqlPersona['correo_institucional'];
            $departamento = $cqlPersona['nombre_area'];
            $departamento_id = $cqlPersona['area_id'];
        }

        $cqlUpdate = BT::where('host', $host)->get()->first();
        if ($cqlUpdate != null) {
            $cql = BT::find($cqlUpdate->id);
            $cql->revision = $cql->revision + 1;
        } else {
            $cql = new BT();
            $cqlEstado = EstadoBienTecnologico::where('abv', 'BUE')->get()->first();

            $cql->estado_bien_tecnologico_id = $cqlEstado->id;
            $cql->departamento_id = $departamento_id;
            $cql->persona_id = $persona_id;
            $cql->revision = 1;
        }
        $cql->memoria = $memoria;
        $cql->procesador = $procesador;
        $cql->usuario = $usuario;
        $cql->arquitectura = $arquitectura;
        $cql->sistema = $sistema;
        $cql->version = $version;
        $cql->serie = $serie;
        $cql->host = $host;
        $cql->cedula = trim($cedula);
        $cql->modelo = $modelo;
        $cql->tipo = $tipo;
        $cql->dominio = $dominio;
        $cql->chasis_id = $chasis;
        $cql->estado = 'ACT';
        $cql->created_at = date("Y-m-d h:m:s");
        $cql->save();


        $cqlBuscarPersona = PersonaBienTecnologico::where('bien_tecnologico_id', $cql->id)->orderby('created_at', 'asc')
            ->get()->first();
        if ($cqlBuscarPersona != null) {
            $cqlPersona = PersonaBienTecnologico::find($cqlBuscarPersona->id);
        } else {
            $cqlPersona = new PersonaBienTecnologico();
            $cqlPersona->created_at = date("Y-m-d h:m:s");
        }
        $cqlPersona->correo = $correo;
        $cqlPersona->departamento = $departamento;
        $cqlPersona->bien_tecnologico_id = $cql->id;
        $cqlPersona->nombres = $nombres;
        $cqlPersona->save();


        $cqlDelete = AdaptadorRed::where('bien_tecnologico_id', $cql->id)->delete();
        $cqlDeleteDisco = Disco::where('bien_tecnologico_id', $cql->id)->delete();
        //$cqlDeletePersona=PersonaBienTecnologico::where('bien_tecnologico_id',$cql->id)->delete();

        $arregloIP = explode(",", $ip);
        foreach ($arregloIP as $value) {
            if (strlen(trim($value)) > 0) {
                $arregloValue = explode("_", $value);
                $cqlAR = new AdaptadorRed();
                $cqlAR->adaptador = $arregloValue[0];
                $cqlAR->tipo = $arregloValue[1];
                $cqlAR->bien_tecnologico_id = $cql->id;
                $cqlAR->ip = $arregloValue[2];
                $cqlAR->mac = $arregloValue[3];
                $cqlAR->created_at = date("Y-m-d h:m:s");
                $cqlAR->save();
            }
        }
        $arregloDisco = explode("_", $disco);
        foreach ($arregloDisco as $value) {
            if (strlen(trim($value)) > 0) {
                $cqlAR = new Disco();
                $cqlAR->descripcion = $value;
                $cqlAR->bien_tecnologico_id = $cql->id;
                $cqlAR->created_at = date("Y-m-d h:m:s");
                $cqlAR->save();
            }
        }



        return;
    }
    public function webserviceSoporteProgramas($cedula, $usuario, $programas)
    {
        $arregloProgramas = explode("_", $programas);

        $cqlUpdate = BT::where('usuario', $usuario)
            ->where('cedula', trim($cedula))->orderby('id', 'desc')->get()->first();
        foreach ($arregloProgramas as $value) {
            if (strlen(trim($value)) > 0) {

                $cqlAR = new Software();
                $cqlAR->descripcion = explode(",", $value)[0];
                $cqlAR->version = explode(",", $value)[1];
                $cqlAR->bien_tecnologico_id = $cqlUpdate->id;
                $cqlAR->created_at = date("Y-m-d h:m:s");
                $cqlAR->save();
            }
        }

        return;
    }
    public function webserviceSoporteProgramasPOST(request $request)
    {
        $arregloProgramas = explode("_", $request->programas);

        $cqlUpdate = BT::where('usuario', $request->usuario)
            ->where('cedula', trim($request->cedula))->orderby('id', 'desc')->get()->first();


        $cqlDelete = Software::where('bien_tecnologico_id', $cqlUpdate->id)
            ->update(['estado' => 'INA']);

        foreach ($arregloProgramas as $value) {
            if (strlen(trim($value)) > 0) {
                $cqlAR = new Software();
                $cqlAR->descripcion = $value;
                $cqlAR->bien_tecnologico_id = $cqlUpdate->id;
                $cqlAR->revision = $cqlUpdate != null ? $cqlUpdate->revision : 1;
                $cqlAR->estado = 'ACT';
                $cqlAR->created_at = date("Y-m-d h:m:s");
                $cqlAR->save();
            }
        }
        $array_response['status'] = '200';
        $array_response['usuario'] = $cqlUpdate->id;
        return response()->json($array_response, 200);
    }
    /* INACTIVO */
    public function getCargaDatosFuncionarioPersona(request $request)
    {
        $cqlPersona = Subusuario_::leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
            ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
            ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
            ->select(
                'a.are_nombre as departamento',
                'distributivo.subusuario.sus_id as persona_id',
                'p.nombre_completo as nombres',
                'p.per_mail as correo',
                'distributivo.subusuario.are_id as departamento_id'
            )
            ->where('a.are_id', $request->departamento_id)
            ->where('u.est_id', 1)
            ->where('distributivo.subusuario.est_id', 1)
            ->pluck('nombres', 'persona_id')->toArray();

        $array_response['status'] = '200';
        $array_response['message'] = $cqlPersona;
        return response()->json($array_response, 200);
    }
    /* INACTIVO */

    function getCargaDatosFuncionario(Request $request)
    {
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = Subusuario_::select('nombres', 'persona_id')
                ->leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
                ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
                ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
                ->select(
                    'a.are_nombre as departamento',
                    'distributivo.subusuario.sus_id as persona_id',
                    'p.nombre_completo as nombres',
                    'p.per_mail as correo',
                    'distributivo.subusuario.are_id as departamento_id'
                )
                ->where('a.are_id', $request->departamento_id)
                ->where('u.est_id', 1)
                ->where(DB::raw('upper(nombres)'), "LIKE", "%{$busqueda}%")
                ->where('distributivo.subusuario.est_id', 1)
                ->get()->take(5);
        } else {
            $data = Subusuario_::select('nombres', 'persona_id')
                ->leftjoin('login.usuario as u', 'distributivo.subusuario.usu_id', 'u.usu_id')
                ->leftjoin('distributivo.persona as p', 'p.per_id', 'u.per_id')
                ->leftjoin('distributivo.area as a', 'a.are_id', 'distributivo.subusuario.are_id')
                ->select(
                    'a.are_nombre as departamento',
                    'distributivo.subusuario.sus_id as persona_id',
                    'p.nombre_completo as nombres',
                    'p.per_mail as correo',
                    'distributivo.subusuario.are_id as departamento_id'
                )
                ->where('a.are_id', $request->departamento_id)
                ->where('u.est_id', 1)
                ->where('distributivo.subusuario.est_id', 1)
                ->get()->take(5);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres,
                );
            }
        }
        return response()->json($countries);
    }
    /* INACTIVO */

    function getCargaDatosDepartamentos(Request $request)
    {

        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            $data = Departamento::select('are_nombre', 'are_id')
                ->where('est_id', '1')
                ->where(DB::raw('upper(are_nombre)'), "LIKE", "%{$busqueda}%")
                ->get()->take(5);
        } else {
            $data = Departamento::select('are_nombre', 'are_id')
                ->where('est_id', '1')
                ->get()->take(5);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->are_id,
                    "text" => $country->are_nombre,
                );
            }
        }
        return response()->json($countries);
    }
    function consultaDatosRegistroCivilGETPortal($cedula)
    {

        $client = new \GuzzleHttp\Client();
        $url = 'https://portal2.presidencia.gob.ec/consultaGETRC.php?cedula=';
        $res = $client->request('GET', $url . $cedula);
        $estado = $res->getStatusCode();
        $result = 'error';
        if ($estado == '200') {
            $resultado = $res->getBody()->getContents(); // recibe un json
            $result = json_decode($resultado, true);
        }
        $array['status'] = $estado;
        $array['result'] = $result;
        return $array;
    }
    function consultaDatosRegistroCivilGETSIGPRE($cedula)
    {

        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        $url = "https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl";

        $opcionesStream = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $options = array(
            'login' => 'ItnOMhIpRe',
            'password' => '?2eZH;;BpA',
            'stream_context' => $opcionesStream,
            'https' => array(
                'curl_verify_ssl_peer'  => false,
                'curl_verify_ssl_host'  => false
            ),
            'location' => $url,
            'cache_wsdl' => 0,
            'cache_ttl' => 0,
            'exceptions' => 1,
            'trace' => 1,
        );
        $usuario = "ItnOMhIpRe";
        $clave = "?2eZH;;BpA";
        $paquete = "472";
        $status = '200';
        $message = '';
        $client = new SoapClient($url, $options);
        //$estado=$client->getStatusCode();
        $message = $client->getFichaGeneral([
            "numeroIdentificacion" => $cedula, "codigoPaquete" => "472"
        ]);
        $status = '200';
        dd($message);
        /*
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        $url = 'https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl';
        $usuario = "ItnOMhIpRe";
        $clave = "?2eZH;;BpA";
        $paquete = "472";
        $opcionesStream = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $opciones=     [
            'login' => $usuario,
            'password' => $clave,
            'stream_context' => $opcionesStream,
            'https' => array(
                'curl_verify_ssl_peer'  => false,
                'curl_verify_ssl_host'  => false
            ),
            'location' => $url,
            'cache_wsdl' => 0,
            'cache_ttl' => 0,
            'exceptions' => 1,
            "trace" => 1
        ];

        $parametro = ["numeroIdentificacion" => $cedula, "codigoPaquete" => $paquete];
        try {
            $soapClient = new SoapClient(
                $url,
                $opciones
            );
            $respuesta = $soapClient->getFichaGeneral($parametro);
            dd($respuesta);
        } catch (SoapFault $exception) {
            dd('Servicio con problemas.');
            exit;
        }*/
    }
    function consultaDatosRegistroCivilGET($cedula)
    {
        $consulta_portal = $this->consultaDatosRegistroCivilGETPortal($cedula);
        if ($consulta_portal['status'] != 200) {
            ini_set('soap.wsdl_cache_enabled', 0);
            ini_set('soap.wsdl_cache_ttl', 0);
            $link = "https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl";

            $opcionesStream = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
            $options = array(
                "connection_timeout" => 60,
                'cache_wsdl' => 0,
                'cache_ttl' => 0,
                'trace' => 1,
                'exceptions' => 1,
                'login' => 'ItnOMhIpRe',
                'password' => '?2eZH;;BpA',
                'stream_context' => $opcionesStream,
                'location' => $link,
                'https' => array(
                    'curl_verify_ssl_peer'  => false,
                    'curl_verify_ssl_host'  => false
                )
            );
            $usuario = "ItnOMhIpRe";
            $clave = "?2eZH;;BpA";
            $paquete = "472: Registro Civil";
            $status = '200';
            $message = '';
            ini_set('default_socket_timeout', 60000);
            $client = new SoapClient("https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl", $options);
            $estado = $client->getStatusCode();
            $message = $client->getFichaGeneral([
                "codigoPaquete" => "472", "numeroIdentificacion" => $cedula
            ]);
            $status = '200';
            dd($message, $status, $client, $options, $estado);
        } else
            dd($consulta_portal['result']);
    }
    public function consultaDatosRegistroCivil(request $request)
    {
        $cedula = $request->cedula;
        $consulta_portal = $this->consultaDatosRegistroCivilGETPortal($cedula);

        if ($consulta_portal['status'] != 200) {
            $link = "https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl";

            $options = array(
                'cache_wsdl' => 0,
                'trace' => 1,
                'exceptions' => 0,
                'login' => 'ItnOMhIpRe',
                'password' => '?2eZH;;BpA',
                "stream_context" => stream_context_create(array('ssl' => array(
                    '
                 ciphers' => 'AES256-SHA',
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ))),
            );

            $usuario = "ItnOMhIpRe";
            $clave = "?2eZH;;BpA";
            $paquete = "472: Registro Civil";
            $status = '200';
            $message = '';
            ini_set('default_socket_timeout', 6000);

            try {
                $client = new SoapClient("https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl", $options);
                $message = $client->getFichaGeneral(["codigoPaquete" => "472", "numeroIdentificacion" => $cedula]);
            } catch (SoapFault $fault) {
                $message = 'NO HAY ACCESO AL REGISTRO CIVIL';
                $status = '300';
            }
        } else {
            $message = $consulta_portal['result'];
            $status = '200';
        }

        $obj = $this->loadXmlStringAsArray($message);
        if (array_key_exists('faultstring', $obj)) {
            $message = 'NO EXISTE CEDULA';
            $status = '300';
        } else {
            $message = $obj["return"]["instituciones"]["datosPrincipales"]["registros"];
            $status = '200';
        }

        $array_response['status'] = $status;
        $array_response['message'] = $message;

        return response()->json($array_response, 200);
    }
    public function consultaDatosRegistroCivilPersona($cedula)
    {
        try {
            //code...
        /*   $consulta_portal = $this->consultaDatosRegistroCivilGETPortal($cedula);

        if ($consulta_portal['status'] != 200) {*/
            $link = "https://interoperabilidad.dinardap.gob.ec/interoperador?wsdl";
            $opcionesStream = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);

            $options = array(
                "connection_timeout" => 60,
                'cache_wsdl' => 0,
                'cache_ttl' => 0,
                'trace' => 1,
                'exceptions' => 1,
                'login' => 'ItnOMhIpRe',
                'password' => '?2eZH;;BpA',
                'stream_context' => $opcionesStream,
                'location' => $link,
                'https' => array(
                    'curl_verify_ssl_peer'  => false,
                    'curl_verify_ssl_host'  => false
                )
            );

            $usuario = "ItnOMhIpRe";
            $clave = "?2eZH;;BpA";
            $paquete = "472: Registro Civil";
            $status = '200';
            $message = '';
            try {
                $client = new SoapClient($link, $options);
                $message = $client->getFichaGeneral(["codigoPaquete" => "472", "numeroIdentificacion" => $cedula]);
            } catch (SoapFault $fault) {
                $message = 'NO HAY ACCESO AL REGISTRO CIVIL';
                $status = '300';
            }
            /*  } else {
                $message = $consulta_portal['result'];
                $status = '200';
            }*/
            $obj = $this->loadXmlStringAsArray($message);
            if (array_key_exists('faultstring', $obj)) {
                $message = 'NO EXISTE CEDULA';
                $status = '300';
            } else {
                $message = $obj["return"]["instituciones"]["datosPrincipales"]["registros"];
                $status = '200';
            }

            $array_response['status'] = $status;
            $array_response['message'] = $message;

            return $array_response;
        } catch (\Throwable $th) {
            // * seteo de objeto SoapClient...
            $objeto = new $th(-1, "Cédula incorrecta: " . $cedula);
            // * retorna error customizado...
            throw $objeto;
        }
    }


    public function loadXmlStringAsArray($xmlString)
    {
        $respuesta = json_encode($xmlString, JSON_FORCE_OBJECT);
        $respuesta = json_decode($respuesta, true);

        return $respuesta;
    }

    /* ACTIVO */

    function getCargaDatosFuncionarioActualizadoSIGPRE(Request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id");
                $buscar = $busqueda;
                $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                    $arregloBuscar = explode(" ", $buscar);
                    foreach ($arregloBuscar as $value) {
                        $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                    }
                });
                //    $data= $data->where('actualizado',true) ->get()->take(5);
                $data = $data->get()->take(5);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->get()->take(5);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->get()->take(5);
                }
            }
        } else {
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id")
                    // ->where('actualizado',true)
                    // ->where('estado','ACT')
                    ->get()->take(5);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    )
                        //   ->where('actualizado',true)
                        // ->where('estado','ACT')
                        ->get()->take(5);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    )
                        //    ->where('actualizado',true)
                        //  ->where('estado','ACT')
                        ->get()->take(5);
                }
            }
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres_completos,
                );
            }
        }
        return response()->json($countries);
    }
    function getCargaDatosFuncionarioSIGPREOrden(Request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id");
                $buscar = $busqueda;
                $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                    $arregloBuscar = explode(" ", $buscar);
                    foreach ($arregloBuscar as $k => $value) {
                        if ($k == 0)
                            $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                        if ($k == 1)
                            $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                        if ($k == 2)
                            $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                        if ($k == 3)
                            $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                    }
                });
                $data = $data->orderby('nombres_completos', 'desc')->get();
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $k => $value) {
                                if ($k == 0)
                                    $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                                if ($k == 1)
                                    $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                                if ($k == 2)
                                    $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                                if ($k == 3)
                                    $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                            }
                        }
                    });
                    $data = $data->orderby('nombres_completos', 'desc')->get();
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $k => $value) {
                                if ($k == 0)
                                    $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                                if ($k == 1)
                                    $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                                if ($k == 2)
                                    $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                                if ($k == 3)
                                    $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                            }
                        }
                    });
                    $data = $data->orderby('nombres_completos', 'desc')->get();
                }
            }
        } else {
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id")
                    // ->where('estado','ACT')
                    ->orderby('nombres_completos', 'desc')
                    ->get()->take(5);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    )
                        // ->where('estado','ACT')
                        ->orderby('nombres_completos', 'desc')
                        ->get()->take(5);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    )
                        //  ->where('estado','ACT')
                        ->orderby('nombres_completos', 'desc')
                        ->get()->take(20);
                }
            }
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres_completos,
                );
            }
        }
        return response()->json($countries);
    }
    function getCargaDatosFuncionarioSIGPREOrdenChosen(Request $request)
    {
        //   dd($request);
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['q'])) {
            $busqueda = strtoupper($input['q']);
            $data = Persona_::select(
                DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                "id"
            );
            $buscar = $busqueda;
            $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                $arregloBuscar = explode(" ", $buscar);
                if (is_numeric($buscar))
                    $q->where('identificacion', $buscar);
                else {
                    foreach ($arregloBuscar as $k => $value) {
                        if ($k == 0)
                            $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                        if ($k == 1)
                            $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                        if ($k == 2)
                            $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', [strtoupper($value) . '%']);
                        if ($k == 3)
                            $q->whereRaw('translate(UPPER(nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value)]);
                    }
                }
            });
            $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
        } else {
            $data = Persona_::select(
                DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                "id"
            )
                // ->where('estado','ACT')
                ->orderby('nombres_completos', 'desc')
                ->get()->take(20);
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres_completos,
                );
            }
        }
        return response()->json($countries);
    }
    function getCargaDatosFuncionarioSIGPRE(Request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id");
                $buscar = $busqueda;
                $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                    $arregloBuscar = explode(" ", $buscar);
                    foreach ($arregloBuscar as $k => $value) {
                        $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                    }
                });
                $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
                }
            }
        } else {
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id")
                    // ->where('estado','ACT')
                    ->orderby('nombres_completos', 'desc')
                    ->get()->take(5);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    )
                        // ->where('estado','ACT')
                        ->orderby('nombres_completos', 'desc')
                        ->get()->take(5);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    )
                        //  ->where('estado','ACT')
                        ->orderby('nombres_completos', 'desc')
                        ->get()->take(20);
                }
            }
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres_completos,
                );
            }
        }
        return response()->json($countries);
    }
    function getCargaDatosFuncionarioSIGPREActivos(Request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id");
                $buscar = $busqueda;
                $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                    $arregloBuscar = explode(" ", $buscar);
                    foreach ($arregloBuscar as $k => $value) {
                        $q->whereRaw('translate(UPPER(apellidos),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                    }
                });
                $data = $data->where(function ($query) {
                    $query->select(DB::RAW('COUNT(estado)'))
                        ->from('historias_laborales as hs')
                        ->whereColumn('personas.id', 'hs.persona_id')
                        ->where('hs.eliminado', false)
                        ->where('hs.eliminado_por_reingreso', false)
                        ->where('hs.estado', 'ACT');
                }, '>', 0);
                $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->where(function ($query) {
                        $query->select(DB::RAW('COUNT(estado)'))
                            ->from('historias_laborales as hs')
                            ->whereColumn('personas.id', 'hs.persona_id')
                            ->where('hs.eliminado', false)
                            ->where('hs.eliminado_por_reingreso', false)
                            ->where('hs.estado', 'ACT');
                    }, '>', 0);
                    $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->where(function ($query) {
                        $query->select(DB::RAW('COUNT(estado)'))
                            ->from('historias_laborales as hs')
                            ->whereColumn('personas.id', 'hs.persona_id')
                            ->where('hs.eliminado', false)
                            ->where('hs.eliminado_por_reingreso', false)
                            ->where('hs.estado', 'ACT');
                    }, '>', 0);
                    $data = $data->orderby('nombres_completos', 'desc')->get()->take(20);
                }
            }
        } else {
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id")
                    // ->where('estado','ACT')
                    ->orderby('nombres_completos', 'desc')
                    ->get()->take(5);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    )
                        // ->where('estado','ACT')
                        ->orderby('nombres_completos', 'desc')
                        ->get()->take(5);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    )
                        //  ->where('estado','ACT')
                        ->orderby('nombres_completos', 'desc')
                        ->get()->take(20);
                }
            }
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres_completos,
                );
            }
        }
        return response()->json($countries);
    }
    function getCargaDatosFuncionarioDIRECTORIO(Request $request)
    {
        $letras_tildes = 'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ';
        $letras_sin_tildes = 'aeiouAEIOUaeiouAEIOU';
        $input = $request->all();
        if (!empty($input['query'])) {
            $busqueda = strtoupper($input['query']);
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id");
                $buscar = $busqueda;
                $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                    $arregloBuscar = explode(" ", $buscar);
                    foreach ($arregloBuscar as $value) {
                        $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                    }
                });
                $data = $data->get()->take(20);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->get()->take(20);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    );
                    $buscar = $busqueda;
                    $data = $data->where(function ($q) use ($letras_sin_tildes, $letras_tildes, $buscar) {
                        $arregloBuscar = explode(" ", $buscar);
                        if (is_numeric($buscar))
                            $q->where('identificacion', $buscar);
                        else {
                            foreach ($arregloBuscar as $value) {
                                $q->whereRaw('translate(UPPER(apellidos_nombres),\'' . $letras_tildes . '\',\'' . $letras_sin_tildes . '\') ILIKE ? ', ['%' . strtoupper($value) . '%']);
                            }
                        }
                    });
                    $data = $data->get()->take(20);
                }
            }
        } else {
            if ($input['tipo'] == "persona_id_") {
                $data = Persona_::select("apellidos_nombres as nombres_completos", "id")
                    ->where('estado', 'ACT')
                    ->get()->take(20);
            } else {
                if ($input['tipo'] == "persona_id") {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "id"
                    )
                        ->where('estado', 'ACT')
                        ->get()->take(20);
                } else {
                    $data = Persona_::select(
                        DB::RAW("CONCAT(identificacion,' / ',apellidos_nombres) as nombres_completos"),
                        "identificacion as id"
                    )
                        ->where('estado', 'ACT')
                        ->get()->take(20);
                }
            }
        }
        $countries = [];
        if (count($data) > 0) {
            foreach ($data as $country) {
                $countries[] = array(
                    "id" => $country->id,
                    "text" => $country->nombres_completos,
                );
            }
        }
        return response()->json($countries);
    }
    public function impersonate($user_id)
    {
        $user = User::find($user_id);
        Auth::user()->impersonate($user);
        return redirect()->route('home');
    }
    public function impersonate_leave()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('home');
    }
    public function reloj_servidor()
    {
        echo date('Y-m-d H:i:s');
    }
    public function cargarHtmlLugares(request $request){
      $data=view('helper.lugares.'.$request->lugar)->render();
      return response()->json($data);
    }
}
