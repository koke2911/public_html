<?php

namespace App\Controllers;

use App\Models\Configuracion\Md_usuarios;
use App\Models\Configuracion\Md_usuario_traza;
use App\Models\Configuracion\Md_permisos_usuario;

class Ctrl_menu extends BaseController {

  protected $sesión;
  protected $permisos_usuario;
  protected $usuarios;
  protected $usuario_traza;
  protected $db;

  public function __construct() {
    $this->sesión           = session();
    $this->permisos_usuario = new Md_permisos_usuario();
    $this->usuarios         = new Md_usuarios();
    $this->usuario_traza    = new Md_usuario_traza();
    $this->db               = \Config\Database::connect();
  }

  public function permisos_usuario() {
    $this->validar_sesion();
    $id_usuario = $this->sesión->id_usuario_ses;

    echo $this->permisos_usuario->permisos_usuario($this->db, $id_usuario);
  }

  public function actualizar_clave() {
    $clave_actual = $this->request->getPost("clave_actual");
    $clave_nueva  = $this->request->getPost("clave_nueva");
    $repetir      = $this->request->getPost("repetir");
    $id_usuario   = $this->sesión->id_usuario_ses;

    $datosUsuario = $this->usuarios->where("id", $id_usuario)
                                   ->first();

    if (password_verify($clave_actual, $datosUsuario["clave"])) {
      define("ACTUALIZAR_CLAVE", 9);
      define("OK", 1);

      $fecha        = date("Y-m-d H:i:s");
      $clave_hash   = password_hash($repetir, PASSWORD_DEFAULT);
      $estado_traza = ACTUALIZAR_CLAVE;

      $datosUsuSave = [
       "id"         => $id_usuario,
       "clave"      => $clave_hash,
       "id_usuario" => $id_usuario,
       "fecha"      => $fecha
      ];

      if ($this->usuarios->save($datosUsuSave)) {
        echo OK;
        $this->guardar_traza($id_usuario, $estado_traza, "");
      } else {
        echo "Error al activar la clave";
      }
    } else {
      echo "Clave actual incorrecta";
    }
  }

  public function guardar_traza($id_usuario, $estado) {
    $fecha = date("Y-m-d H:i:s");

    $datosTraza = [
     "id_usuario"  => $id_usuario,
     "estado"      => $estado,
     "usuario_reg" => $id_usuario,
     "fecha"       => $fecha
    ];

    if (!$this->usuario_traza->save($datosTraza)) {
      echo "Falló al guardar la traza";
    }
  }

  public function dashboard() {
    $this->validar_sesion();
    echo view('dashboard');
  }

  public function usuarios() {
    $this->validar_sesion();
    echo view('Configuracion/usuarios');
  }

  public function apr() {
    $this->validar_sesion();
    echo view('Configuracion/apr');
  }

  public function costo_metros() {
    $this->validar_sesion();
    echo view('Configuracion/costo_metros');
  }

  public function socios() {
    $this->validar_sesion();
    echo view('Formularios/socios');
  }

  public function arranques() {
    $this->validar_sesion();
    echo view('Formularios/arranques');
  }

  public function sectores() {
    $this->validar_sesion();
    echo view('Formularios/sectores');
  }

  public function subsidios() {
    $this->validar_sesion();
    echo view('Formularios/subsidios');
  }

  public function convenios() {
    $this->validar_sesion();
    echo view('Formularios/convenios');
  }

  public function medidores() {
    $this->validar_sesion();
    echo view('Formularios/medidores');
  }

  public function metros() {
    $this->validar_sesion();
    echo view('Consumo/metros');
  }

  public function caja() {
    $this->validar_sesion();
    echo view('Pagos/caja');
  }

  public function historial_pagos() {
    $this->validar_sesion();
    echo view('Pagos/historial_pagos');
  }

  public function boleta_electronica() {
    $this->validar_sesion();
    echo view('Pagos/boleta_electronica');
  }

  public function informe_socios() {
    $this->validar_sesion();
    echo view('Informes/informe_socios');
  }

  public function informe_arranques() {
    $this->validar_sesion();
    echo view('Informes/informe_arranques');
  }

  public function informe_pagos_diarios() {
    $this->validar_sesion();
    echo view('Informes/informe_pagos_diarios');
  }

  public function informe_subsidios() {
    $this->validar_sesion();
    echo view('Informes/informe_subsidios');
  }

  public function informe_arqueo() {
    $this->validar_sesion();
    echo view('Informes/informe_arqueo');
  }

  public function informe_balance() {
    $this->validar_sesion();
    echo view('Informes/informe_balance');
  }

  public function informe_afecto_corte() {
    $this->validar_sesion();
    echo view('Informes/informe_afecto_corte');
  }

  public function informe_mensualidad() {
    $this->validar_sesion();
    echo view('Informes/informe_mensualidad');
  }

  public function informe_municipalidad_subsidios() {
    $this->validar_sesion();
    echo view('Informes/informe_municipalidad_subsidios');
  }

  public function finanzas_ingresos() {
    $this->validar_sesion();
    echo view('Finanzas/ingresos');
  }

  public function finanzas_egresos() {
    $this->validar_sesion();
    echo view('Finanzas/egresos');
  }

  public function proveedores() {
    $this->validar_sesion();
    echo view('Finanzas/proveedores');
  }

  public function cuentas() {
    $this->validar_sesion();
    echo view('Finanzas/cuentas');
  }

  public function motivos() {
    $this->validar_sesion();
    echo view('Finanzas/motivos');
  }

  public function funcionarios() {
    $this->validar_sesion();
    echo view('Finanzas/funcionarios');
  }

  public function tipo_ingreso() {
    $this->validar_sesion();
    echo view('Finanzas/tipo_ingreso');
  }

  public function tipo_egreso() {
    $this->validar_sesion();
    echo view('Finanzas/tipo_egreso');
  }

  public function compras() {
    $this->validar_sesion();
    echo view('Finanzas/compras');
  }

  public function historial_egresos() {
    $this->validar_sesion();
    echo view('Finanzas/historial_egresos');
  }

  public function productos() {
    $this->validar_sesion();
    echo view('Inventario/productos');
  }

  public function estados_producto() {
    $this->validar_sesion();
    echo view('Inventario/estados_producto');
  }

  public function ubicaciones_producto() {
    $this->validar_sesion();
    echo view('Inventario/ubicaciones_producto');
  }

  public function informe_ingresos() {
    $this->validar_sesion();
    echo view('Informes/informe_ingresos');
  }

  public function informe_egresos_simples() {
    $this->validar_sesion();
    echo view('Informes/informe_egresos_simples');
  }

  public function informe_compras() {
    $this->validar_sesion();
    echo view('Informes/informe_compras');
  }

  public function informe_inventario() {
    $this->validar_sesion();
    echo view('Informes/informe_inventario');
  }

  public function llenado_agua() {
    $this->validar_sesion();
    echo view('Inventario_agua/llenado_agua');
  }

  public function informe_llenado_agua() {
    $this->validar_sesion();
    echo view('Informes/informe_llenado_agua');
  }

  public function informe_consumo_agua() {
    $this->validar_sesion();
    echo view('Informes/informe_consumo_agua');
  }

  public function informe_cuadratura_agua() {
    $this->validar_sesion();
    echo view('Informes/informe_cuadratura_agua');
  }

  public function servicios() {
    $this->validar_sesion();
    echo view('Formularios/servicios');
  }

  public function informe_lecturas_sector() {
    $this->validar_sesion();
    echo view('Informes/informe_lecturas_sector');
  }

  public function cambio_medidor() {
    $this->validar_sesion();
    echo view('Formularios/cambio_medidor');
  }

  public function abonar() {
    $this->validar_sesion();
    echo view('Pagos/abonar');
  }

  public function repactar() {
    $this->validar_sesion();
    echo view('Formularios/repactar');
  }

  public function informe_historico_socio() {
    $this->validar_sesion();
    echo view('Informes/informe_historico_socio');
  }

  public function imprimir_facturas_muni() {
    $this->validar_sesion();
    echo view('Pagos/imprimir_facturas_muni');
  }

  public function informe_deudores() {
    $this->validar_sesion();
    echo view('Informes/informe_deudores');
  }

  public function lectura_sector() {
    $this->validar_sesion();
    echo view('Consumo/lectura_sector');
  }

  public function observaciones_dte() {
    $this->validar_sesion();
    echo view('Configuracion/observaciones_dte');
  }

  public function registro_ventas() {
    $this->validar_sesion();
    echo view('Finanzas/registro_ventas');
  }

  public function carga_masiva() {
    $this->validar_sesion();
    echo view('Configuracion/carga_masiva');
  }

  public function multas() {
    $this->validar_sesion();
    echo view('Finanzas/multas');
  }

  public function lectura_planilla() {
    $this->validar_sesion();
    echo view('Consumo/lectura_planilla');
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function desinfeccion() {
    $this->validar_sesion();
    echo view('Formularios/desinfeccion');
  }
}