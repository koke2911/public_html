<?php

namespace App\Controllers\Finanzas;

use App\Controllers\BaseController;
use App\Models\Finanzas\Md_funcionarios;
use App\Models\Finanzas\Md_funcionarios_traza;

class Ctrl_funcionarios extends BaseController {

  protected $funcionarios;
  protected $funcionarios_traza;
  protected $sesión;
  protected $db;

  public function __construct() {
    $this->funcionarios       = new Md_funcionarios();
    $this->funcionarios_traza = new Md_funcionarios_traza();
    $this->sesión             = session();
    $this->db                 = \Config\Database::connect();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function datatable_funcionarios() {
    $this->validar_sesion();
    echo $this->funcionarios->datatable_funcionarios($this->db, $this->sesión->id_apr_ses);
  }

  public function guardar_funcionario() {
    $this->validar_sesion();
    define("CREAR_FUNCIONARIO", 1);
    define("MODIFICAR_FUNCIONARIO", 2);

    define("OK", 1);
    define("ACTIVO", 1);

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;
    $id_apr     = $this->sesión->id_apr_ses;
    $estado     = ACTIVO;

    $id_funcionario  = $this->request->getPost("id_funcionario");
    $rut             = $this->request->getPost("rut");
    $nombres         = $this->request->getPost("nombres");
    $ape_pat         = $this->request->getPost("ape_pat");
    $ape_mat         = $this->request->getPost("ape_mat");
    $id_sexo         = $this->request->getPost("id_sexo");
    $id_comuna       = $this->request->getPost("id_comuna");
    $calle           = $this->request->getPost("calle");
    $numero          = $this->request->getPost("numero");
    $resto_direccion = $this->request->getPost("resto_direccion");

    $prevision      = $this->request->getPost("prevision");
    $prev_porcentaje = $this->request->getPost("prev_porcentaje");
    $afp            = $this->request->getPost("afp");
    $afp_porcentaje = $this->request->getPost("afp_porcentaje");
    $sueldo_bruto   = $this->request->getPost("sueldo_bruto");
    $fecha_contrato = $this->request->getPost("fecha_contrato");
    $jornada        = $this->request->getPost("jornada");
    $vacaciones     = $this->request->getPost("vacaciones");

    if ($id_comuna == "") {
      $id_comuna = NULL;
    }

    $rut_completo = explode("-", $rut);
    $rut          = $rut_completo[0];
    $dv           = $rut_completo[1];

    if ($this->funcionarios->existe_funcionario($rut, $this->sesión->id_apr_ses) and $id_funcionario == "") {
      echo "Funcionario ya existe en el sistema";
      exit();
    }

    $datosFuncionario = [
     "rut"             => $rut,
     "dv"              => $dv,
     "nombres"         => $nombres,
     "ape_pat"         => $ape_pat,
     "ape_mat"         => $ape_mat,
     "id_sexo"         => $id_sexo,
     "calle"           => $calle,
     "numero"          => $numero,
     "resto_direccion" => $resto_direccion,
     "id_comuna"       => $id_comuna,
     "id_usuario"      => $id_usuario,
     "fecha"           => $fecha,
     "id_apr"          => $id_apr,
     "prevision"      =>$prevision,
     "prev_porcentaje"=>$prev_porcentaje,
     "afp"            =>$afp,
     "afp_porcentaje" =>$afp_porcentaje,
     "sueldo_bruto"   =>$sueldo_bruto,
     "fecha_contrato" =>date_format(date_create($fecha_contrato), 'Y-m-d'),
     "jornada"        =>$jornada,
     "vacaciones"     =>$vacaciones
    ];

    if ($id_funcionario != "") {
      $estado_traza           = MODIFICAR_FUNCIONARIO;
      $datosFuncionario["id"] = $id_funcionario;
    } else {
      $estado_traza               = CREAR_FUNCIONARIO;
      $datosFuncionario["estado"] = $estado;
    }

    if ($this->funcionarios->save($datosFuncionario)) {
      echo OK;

      if ($id_funcionario == "") {
        $obtener_id     = $this->funcionarios->select("max(id) as id_funcionario")
                                             ->first();
        $id_funcionario = $obtener_id["id_funcionario"];
      }

      $this->guardar_traza($id_funcionario, $estado_traza, "");
    } else {
      echo "Error al guardar los datos del funcionario";
    }
  }

  public function guardar_traza($id_funcionario, $estado, $observacion) {
    $this->validar_sesion();

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosTraza = [
     "id_funcionario" => $id_funcionario,
     "estado"         => $estado,
     "observacion"    => $observacion,
     "id_usuario"     => $id_usuario,
     "fecha"          => $fecha
    ];

    if (!$this->funcionarios_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function v_funcionario_traza() {
    $this->validar_sesion();
    echo view("Finanzas/funcionarios_traza");
  }

  public function datatable_funcionario_traza($id_funcionario) {
    $this->validar_sesion();
    echo $this->funcionarios_traza->datatable_funcionario_traza($this->db, $id_funcionario);
  }

  public function cambiar_estado_funcionario() {
    $this->validar_sesion();
    $id_funcionario = $this->request->getPost("id_funcionario");
    $estado         = $this->request->getPost("estado");
    $observacion    = $this->request->getPost("observacion");

    define("OK", 1);

    if ($estado == "eliminar") {
      $estado_traza = 3;
      $estado       = 0;
    } else {
      $estado_traza = 4;
      $estado       = 1;
    }

    $fecha      = date("Y-m-d H:i:s");
    $id_usuario = $this->sesión->id_usuario_ses;

    $datosFuncionario = [
     "id"         => $id_funcionario,
     "estado"     => $estado,
     "id_usuario" => $id_usuario,
     "fecha"      => $fecha
    ];

    if ($this->funcionarios->save($datosFuncionario)) {
      echo OK;
      $this->guardar_traza($id_funcionario, $estado_traza, $observacion);
    } else {
      echo "Error al guardar los datos del funcionario";
    }
  }

  public function v_funcionarios_reciclar() {
    $this->validar_sesion();
    echo view("Finanzas/funcionarios_reciclar");
  }

  public function datatable_funcionarios_reciclar() {
    $this->validar_sesion();
    echo $this->funcionarios->datatable_funcionarios_reciclar($this->db, $this->sesión->id_apr_ses);
  }
}

?>