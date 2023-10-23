<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Asesores extends MY_Controller
{

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   * 		http://example.com/index.php/welcome
   * 	- or -
   * 		http://example.com/index.php/welcome/index
   * 	- or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see http://codeigniter.com/user_guide/general/urls.html
   */
  public $impuestos     = array();
  private $usuario      = array();
  private $data         = array();
  private $tipo_usuario = "";

  public function __construct()
  {
    parent::__construct();
    $this->tipo_usuario = strtolower($this->session->login->tipo_usuarioDescripcion);
    //echo $this->tipo_usuario;
    $this->load->model("asesores_model");
  }

  public function index()
  {

    $datos["content"] = "";
    $this->template->Admin($datos);
  }

  public function Listado()
  {

    if ($this->input->is_ajax_request()) {
      // Listado de Asesores
      try {
        if ($this->input->method() == 'post') {
          $post = $this->input->post();
          if ($post["accion"]) {
            $response = [
              "estado" => "error",
            ];
            switch ($post["accion"]) {
              case 'listarAsesores': {
                  $parametros = [
                    "ases.tipo_asesor" => "JUEZ"
                  ];
                  $preAsesores = $this->asesores_model->FindAll($parametros);
                  foreach ($preAsesores as $asesor) {
                    $ase = new stdClass();
                    $ase->id = $asesor->id;
                    $ase->nombre_primero = $asesor->nombre_primero;
                    $ase->apellido_primero = $asesor->apellido_primero;
                    $ase->cedula_id = $asesor->cedula_id;
                    $ase->fecha_nacimiento = $asesor->fecha_nacimiento;
                    $ase->acerca_de_persona = $asesor->acerca_de_persona;
                    $ase->ip = $asesor->ip;
                    $ase->correo = $asesor->correo;
                    $ase->ciudad = $asesor->ciudad;
                    $ase->whatsapp = $asesor->whatsapp;
                    $ase->estado = $asesor->estado;
                    $asesores[] = $ase;
                  }
                  $response = [
                    "estado" => "ok",
                    "asesores" => $asesores,
                  ];
                }
                break;
              case "actualizarAsesor": {
                  $post["tipo_asesor"] = "JUEZ";
                  unset($post["accion"]);
                  $this->asesores_model->ActualizarDatosPersonales($post);
                  $response = [
                    "estado" => "ok",
                    "mensaje" => "Se actualizó el perfil"
                  ];
                }
                break;
              case "registrarAsesor": {
                  $this->asesores_model->RegistrarAsesor($post);

                  $response = array(
                    "estado"  => "ok",
                    "mensaje" => "Se registro con éxito el Juez",
                  );
                }
                break;
              default:
                throw new Exception("No se pudo revisar");
                break;
            }
            echo json_encode($response);
          }
        }
      } catch (Exception $exc) {
        $exc->destino = "";
        $exc->url_regresar = "";
        $this->CatchErroresDestino2021($exc);
      }
    } else {
      // foreach ($asesores as $value) {
      //   $cv                 = getCv($value->id, $this->palabraClave);
      //   $value->link        = site_url("asesores/mantenimiento/$value->id/$cv");
      //   $data["asesores"][] = $value;
      // }
      // $data["tipo_usuario"] = ucfirst($this->tipo_usuario);

      $content = $this->load->view("asesores/listado_asesores", null, true);
      $datos["content"] = $content;
      $datos["action"] = "Juez";
      $datos["method"] = "Listado";
      $this->template->Admin($datos);
    }
  }

  public function Mantenimiento($id = NULL, $cv = NULL)
  {

    try {
      $get                  = $this->input->get();
      $data["tipo_usuario"] = ucfirst($this->tipo_usuario);
      $this->load->model("asesores/asesores_model");
      if ($id == NULL) {
        $data["asesor"]          = $this->asesores_model;
        $data["url"]             = site_url("asesores/registrar");
        $data["tipo_formulario"] = "Registrar";
      } else {
        // Validar todo el GetCV
        $this->ValidarRutaGetCv($id, $cv);

        // Buscar Asesor
        $asesor_id    = $id;
        $buscarAsesor = $this->asesores_model->FindAll(array("ases.id" => $asesor_id));
        if (empty($buscarAsesor)) {
          throw new Exception("El asesor que esta buscando no esta aun registrado");
        }

        $data["asesor"]               = $buscarAsesor[0];
        $data["url"]                  = site_url("asesores/actualizar/$id/$cv");
        $data["tipo_formulario"]      = "Actualizar";
        $data["tiene_usuario_creado"] = $buscarAsesor[0]->usuario_id == '' ? 'No' : 'Si';
        $this->session->asesor        = $data["asesor"];
      }

      $datos["content"] = $this->MantenimientoView($data);
      $this->template->Admin($datos);
    } catch (Exception $exc) {
      $this->session->set_flashdata(
        array(
          "estado"  => "error",
          "mensaje" => $exc->getMessage()
        )
      );
      redirect("asesores/listado");
    }
  }

  function MantenimientoView($data = array())
  {


    $this->load->model("territorio/territorio_model");
    $this->load->model("parametros/parametros_model");

    $data["paises"]      = $this->territorio_model->GetPaises();
    $data["estados"]     = $this->parametros_model->FindAll(array("tipo" => "ESTADO"));
    $data["sexos"]       = $this->parametros_model->FindAll(array("tipo" => "SEXO"));
    $data["tipo_asesor"]       = $this->parametros_model->FindAll(array("tipo" => "ASES"));
    //$data["tipo_asesor"] = $this->parametros_model->getTiposUsuarios();
    return $this->load->view("asesores/mantenimiento", $data, true);
  }

  public function Actualizar($id, $cv)
  {
    try {

      $this->ValidarRutaGetCv($id, $cv);
      // Para recibir la informacion
      $post = $this->input->post();

      if (empty($post)) {
        throw new Exception("Los campos estan vacios");
      }

      if ($post["tipo_formulario"] == "informacion_basica") {
        $dataPersonales = $post;
        $dataPersonales["id"] = $id;
        unset($dataPersonales["tipo_formulario"]);
        $resultadoActualizacionAsesor = $this->asesores_model->ActualizarDatosPersonales($dataPersonales);
        //$this->asesores_model->ActualizarDatosPersonales($dataPersonales);
        echo json_encode($resultadoActualizacionAsesor);
      } elseif ($post["tipo_formulario"] == "credenciales") {
        //Buscar asesor
        $asesor      = $this->session->asesor;
        unset($this->session->asesor);
        $asesorModel = $this->asesores_model->FindAll(array("ases.id" => $asesor->id));

        if (empty($asesorModel)) {
          throw new Exception("Fallo la busqueda del asesor");
        }

        //Cuando no tiene usuario y es administrador
        if ($asesorModel[0]->usuario_id == "") {
          //throw new Exception("El Asesor $asesor->nombre_corto , no 222 tiene usuario creado");
          //unset($post["repetir_password"]);
          $parametros = array(
            "usuario"      => $post["usuario"],
            "password"     => md5($post["password"]),
            "persona_id"   => $asesorModel[0]->persona_id,
            "tipo_usuario" => "ASES",
          );
          $this->load->model("usuarios/usuarios_model");
          $this->usuarios_model->Registrar($parametros);

          $datos = array(
            "estado"  => "ok",
            "mensaje" => "Se registro el usuario y el password provisional."
          );
          echo json_encode($datos);

          // Cuando si existe usuario y es el dueño de la cuenta  
        } else {

          // El mismo Asesor actualiza sus credenciales
          if (isset($post["repetir_password"])) {
            if ($post["password"] != $post["repetir_password"]) {
              throw new Exception("Las contraseñas no son iguales");
            }
            unset($post["repetir_password"]);
          }

          $parametros = array(
            "usuario"  => $post["usuario"],
            "password" => md5($post["password"]),
          );
          $this->asesores_model->ActualizarCredencialesAsesor(array("id" => $asesor->usuario_id), $parametros);
        }
      } else {
        throw new Exception("No esta definido el tipo de formulario");
      }
    } catch (Exception $exc) {
      $datos = array(
        "estado"  => "error",
        "mensaje" => $exc->getMessage()
      );
      echo json_encode($datos);
    }
  }

  public function Registrar()
  {
    try {
      $post = $this->input->post();

      if (empty($post)) {
        throw new Exception("Los campos estan vacios");
      }
      $correo        = strtolower($post["correo"]);
      // Buscar Persona
      $buscarPersona = $this->asesores_model->BuscarPersona(NULL, array("pers.correo" => $correo));
      if (!empty($buscarPersona)) {
        throw new Exception("El correo electrónico '" . $post["correo"] . "' ya existe en nuestros registros.");
      }

      // Buscar Usuario
      $this->load->model("usuarios/usuarios_model");
      $buscarCredencial = $this->usuarios_model->BuscarUsuario(array("usuario" => $post["usuario"]));
      if (!empty($buscarCredencial)) {
        throw new Exception("Las credenciales 'usuario' ya estan asignadas a otra persona.");
      }
      // Asignar Asesor mediante una cita a los pacientes
      unset($post["tipo_formulario"]);

      $post["correo"] = $correo; // Correo electronico con minusculas
      $this->asesores_model->RegistrarAsesor($post);
    } catch (Exception $exc) {
      $datos = array(
        "estado"  => "error",
        "mensaje" => $exc->getMessage()
      );
      echo json_encode($datos);
    }
  }
}