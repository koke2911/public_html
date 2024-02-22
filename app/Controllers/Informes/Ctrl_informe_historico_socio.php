<?php

namespace App\Controllers\Informes;

use App\Models\Pagos\Md_caja;
use App\Models\Pagos\Md_abonos;
use App\Models\Consumo\Md_metros;
use App\Controllers\BaseController;
use App\Models\Formularios\Md_socios;
use App\Models\Formularios\Md_subsidios;
use App\Models\Formularios\Md_convenios;
use App\Models\Formularios\Md_repactaciones;
use App\Models\Formularios\Md_cambio_medidor;
use App\Models\Formularios\Md_convenio_detalle;
use App\Models\Formularios\Md_repactaciones_detalle;

class Ctrl_informe_historico_socio extends BaseController {

  protected $socios;
  protected $subsidios;
  protected $convenios;
  protected $convenios_detalle;
  protected $repactaciones;
  protected $repactaciones_detalle;
  protected $cambio_medidor;
  protected $metros;
  protected $caja;
  protected $abonos;
  protected $sesión;
  protected $mpdf;
  protected $db;

  public function __construct() {
    $this->socios                = new Md_socios();
    $this->subsidios             = new Md_subsidios();
    $this->convenios             = new Md_convenios();
    $this->convenios_detalle     = new Md_convenio_detalle();
    $this->repactaciones         = new Md_repactaciones();
    $this->repactaciones_detalle = new Md_repactaciones_detalle();
    $this->cambio_medidor        = new Md_cambio_medidor();
    $this->metros                = new Md_metros();
    $this->caja                  = new Md_caja();
    $this->abonos                = new Md_abonos();
    $this->sesión                = session();
    $this->db                    = \Config\Database::connect();
    $this->mpdf                  = new \Mpdf\Mpdf([
                                                   'mode'    => 'utf-8',
                                                   'format'  => 'letter',
                                                   'tempDir' => $_SERVER["DOCUMENT_ROOT"] . '/writable/cache'
                                                  ]);
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function imprimir_historico($id_socio) {
    $this->validar_sesion();
    define("ACTIVO", 1);

    $datosSocio = $this->socios
     ->select("socios.rol")
     ->select("concat(socios.rut, '-', socios.dv) as rut")
     ->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre")
     ->select("date_format(socios.fecha_entrada, '%d-%m-%Y') as fecha_entrada")
     ->select("ifnull(date_format(socios.fecha_nacimiento, '%d-%m-%Y'), 'NO REGISTRADA') as fecha_nacimiento")
     ->select("ifnull(elt(field(socios.id_sexo, 1, 2), 'MASCULINO', 'FEMENINO'), 'NO REGISTRADO') as sexo")
     ->select("ifnull(socios.calle, 'NO REGISTRADA') as calle")
     ->select("ifnull(socios.numero, 'NO REGISTRADO') as numero")
     ->select("ifnull(socios.resto_direccion, 'NO REGISTRADA') as resto_direccion")
     ->select("regiones.nombre as region")
     ->select("provincias.nombre as provincia")
     ->select("comunas.nombre as comuna")
     ->select("medidores.numero as numero_medidor")
     ->select("diametro.glosa as diametro")
     ->select("ifnull(medidores.marca, 'NO REGISTRADA') as marca_medidor")
     ->select("ifnull(medidores.tipo, 'NO REGISTRADO') as tipo_medidor")
     ->select("sectores.nombre as sector")
     ->select("tipo_documento.glosa as tipo_documento")
     ->select("socios.abono")
     ->join("comunas", "socios.id_comuna = comunas.id")
     ->join("provincias", "comunas.id_provincia = provincias.id")
     ->join("regiones", "provincias.id_region = regiones.id")
     ->join("arranques", "arranques.id_socio = socios.id")
     ->join("medidores", "arranques.id_medidor = medidores.id")
     ->join("diametro", "medidores.id_diametro = diametro.id")
     ->join("sectores", "arranques.id_sector = sectores.id")
     ->join("tipo_documento", "arranques.id_tipo_documento = tipo_documento.id")
     ->where("socios.id", $id_socio)
     ->first();

    $this->mpdf->SetTitle("Informe Histórico Socio " . $datosSocio["rol"]);
    $html = ('
				<div style="width: 20%; position: absolute; top: 10px; right: 0;">
					<img src="' . $this->sesión->id_apr_ses . '.png" />
				</div>
		    	<div style="width: 60%; float: left; font-size: 150%; margin-left: 20%;">
                    <center><b>Informe Histórico de Socio ' . $datosSocio["rol"] . '</b></center>
                </div>
				<div style="width: 40%; float: left; font-size: 110%; margin-left: 30%;">
                    <b>' . $this->sesión->apr_ses . '</b>
				</div>					
                <br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Datos del Socio</b>
                    </div>
                </div>
                <div style="width: 50%; float: left;">
                    <ul>
                        <li style="list-style:none; font-size: 80%;"><b>ROL:</b> ' . $datosSocio["rol"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>RUN:</b> ' . $datosSocio["rut"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Nombre Completo:</b> ' . $datosSocio["nombre"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Fecha Nacimiento:</b> ' . $datosSocio["fecha_nacimiento"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Fecha Entrada:</b> ' . $datosSocio["fecha_entrada"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Sexo:</b> ' . $datosSocio["sexo"] . '</li>
						<li style="list-style:none; font-size: 80%;"><b>Documento Pago:</b> ' . $datosSocio["tipo_documento"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>N° Medidor:</b> ' . $datosSocio["numero_medidor"] . '</li>
						<li style="list-style:none; font-size: 80%;"><b>Diámetro Medidor:</b> ' . $datosSocio["diametro"] . '</li>
						<li style="list-style:none; font-size: 80%;"><b>Marca Medidor:</b> ' . $datosSocio["marca_medidor"] . '</li>
						<li style="list-style:none; font-size: 80%;"><b>Tipo Medidor</b> ' . $datosSocio["tipo_medidor"] . '</li>
                    </ul>
                </div>
                <div style="width: 50%; float: left;">
                    <ul>
                        <li style="list-style:none; font-size: 80%;"><b>Calle:</b> ' . $datosSocio["calle"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Número:</b> ' . $datosSocio["numero"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Resto Dirección.:</b> ' . $datosSocio["resto_direccion"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Región:</b> ' . $datosSocio["region"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Provincia:</b> ' . $datosSocio["provincia"] . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Comuna:</b> ' . strtoupper($datosSocio["comuna"]) . '</li>
                        <li style="list-style:none; font-size: 80%;"><b>Sector:</b> ' . $datosSocio["sector"] . '</li>
                    </ul>
                </div>
                <br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Subsidios</b>
                    </div>
                </div>
		    ');

    $datosSubsidios = $this->subsidios
     ->select("ifnull(subsidios.numero_decreto, 'NO REGISTRADO') as numero_decreto")
     ->select("ifnull(date_format(subsidios.fecha_decreto, '%d-%m-%Y'), 'NO REGISTRADA') as fecha_decreto")
     ->select("ifnull(date_format(subsidios.fecha_caducidad, '%d-%m-%Y'), 'NO REGISTRADA') as fecha_caducidad")
     ->select("porcentajes.glosa as porcentaje")
     ->select("ifnull(date_format(subsidios.fecha_encuesta, '%d-%m-%Y'), 'NO REGISTRADA') as fecha_encuesta")
     ->select("ifnull(subsidios.puntaje, 'NO REGISTRADO') as puntaje")
     ->select("ifnull(subsidios.numero_unico, 'NO REGISTRADO') as numero_unico")
     ->select("ifnull(subsidios.digito_unico, 'NO REGISTRADO') as digito_unico")
     ->join("porcentajes", "subsidios.id_porcentaje = porcentajes.id")
     ->where("subsidios.id_socio", $id_socio)
     ->findAll();

    if ($datosSubsidios != NULL) {
      $html .= ('
		    		<br>
		    		<table style="border: 1px solid; font-size: 80%; width: 100%;">
		                <thead>
		                    <tr>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">N° Decreto</th>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Fecha Decreto</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Fecha Caducidad</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Porcentaje</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Fecha Encuesta</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Puntaje</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">N° Único</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Dig Único</th>
		                    </tr>
		                </thead>
		                <tbody>
		    	');

      foreach ($datosSubsidios as $key) {
        $html .= ('
	                    <tr>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["numero_decreto"] . '</th>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["fecha_decreto"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["fecha_caducidad"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["porcentaje"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["fecha_encuesta"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["puntaje"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["numero_unico"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["digito_unico"] . '</th>
	                    </tr>
			    	');
      }

      $html .= ('
		    			</tbody>
		            </table>
		        ');
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene subsidios asociados.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $html .= ('
		    	<br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Convenios</b>
                    </div>
                </div>
            ');

    $datosConvenios = $this->convenios
     ->select("convenios.id as id_convenio")
     ->select("servicios.glosa as servicio")
     ->select("ifnull(convenios.detalle_servicio, 'NO REGISTRADO') as detalle_servicio")
     ->select("date_format(convenios.fecha_servicio, '%d-%m-%Y') as fecha_servicio")
     ->select("convenios.numero_cuotas")
     ->select("date_format(convenios.fecha_pago, '%m-%Y') as fecha_pago")
     ->select("convenios.costo_servicio")
     ->join("servicios", "convenios.id_servicio = servicios.id")
     ->where("convenios.id_socio", $id_socio)
     ->findAll();

    if ($datosConvenios != NULL) {
      foreach ($datosConvenios as $key) {
        $html .= ('
			    		<div style="width: 100%; float: left;">
		                    <ul>
		                        <li style="list-style:none; font-size: 80%;"><b>Servicio:</b> ' . $key["servicio"] . '</li>
		                        <li style="list-style:none; font-size: 80%;"><b>Detalles del Servicio:</b> ' . $key["detalle_servicio"] . '</li>
		                        <li style="list-style:none; font-size: 80%;"><b>Fecha del Servicio:</b> ' . $key["fecha_servicio"] . '</li>
		                        <li style="list-style:none; font-size: 80%;"><b>N° de Cuotas:</b> ' . $key["numero_cuotas"] . '</li>
		                        <li style="list-style:none; font-size: 80%;"><b>Mes Primer Pago:</b> ' . $key["fecha_pago"] . '</li>
								<li style="list-style:none; font-size: 80%;"><b>Costo del Servicio:</b> $' . number_format($key["costo_servicio"], 0, ',', '.') . '</li>
		                    </ul>
		                </div>

		                <div style="width: 100%; float: left;">
		                    <div style="font-size: 110%;">
		                        <b>Cuotas del Convenios</b>
		                    </div>
		                </div>
			    	');

        $datosConveniosDetalle = $this->convenios_detalle
         ->select("date_format(fecha_pago, '%m-%Y') mes_pago")
         ->select("numero_cuota")
         ->select("valor_cuota")
         ->select("case when pagado = 1 then 'SI' else 'NO' end as pagado")
         ->where("id_convenio", $key["id_convenio"])
         ->findAll();

        $html .= ('
			    		<br>
			    		<table style="border: 1px solid; font-size: 80%; width: 100%;">
			                <thead>
			                    <tr>
			                    	<th style="border: 1px solid; background-color: #17057F; color: white;">N° Cuota</th>
			                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Mes Pago</th>
			                        <th style="border: 1px solid; background-color: #17057F; color: white;">Valor Cuota</th>
			                        <th style="border: 1px solid; background-color: #17057F; color: white;">Pagado</th>
			                    </tr>
			                </thead>
			                <tbody>
			    	');

        foreach ($datosConveniosDetalle as $cuotas) {
          $html .= ('
		                    <tr>
		                    	<th style="border: 1px solid; font-weight: normal;">' . $cuotas["numero_cuota"] . '</th>
		                    	<th style="border: 1px solid; font-weight: normal;">' . $cuotas["mes_pago"] . '</th>
		                        <th style="border: 1px solid; font-weight: normal;">$' . number_format($cuotas["valor_cuota"], 0, ',', '.') . '</th>
		                        <th style="border: 1px solid; font-weight: normal;">' . $cuotas["pagado"] . '</th>
		                    </tr>
				    	');
        }

        $html .= ('
			    			</tbody>
			            </table>
			        ');
      }
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene convenios asociados.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $html .= ('
		    	<br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Repactaciones</b>
                    </div>
                </div>
            ');

    $datosRepactaciones = $this->repactaciones
     ->select("id as id_repactacion")
     ->select("deuda_vigente")
     ->select("n_cuotas")
     ->select("date_format(fecha_pago, '%m-%Y') as fecha_pago")
     ->where("id_socio", $id_socio)
     ->where("estado", ACTIVO)
     ->findAll();

    if ($datosRepactaciones != NULL) {
      foreach ($datosRepactaciones as $key) {
        $html .= ('
			    		<div style="width: 100%; float: left;">
		                    <ul>
								<li style="list-style:none; font-size: 80%;"><b>Deuda Repactada:</b> $' . number_format($key["deuda_vigente"], 0, ',', '.') . '</li>
		                        <li style="list-style:none; font-size: 80%;"><b>N° de Cuotas:</b> ' . $key["n_cuotas"] . '</li>
		                        <li style="list-style:none; font-size: 80%;"><b>Mes Primer Pago:</b> ' . $key["fecha_pago"] . '</li>
		                    </ul>
		                </div>

		                <div style="width: 100%; float: left;">
		                    <div style="font-size: 110%;">
		                        <b>Cuotas de la Repactación</b>
		                    </div>
		                </div>
			    	');

        $datosRepactacionesDetalle = $this->repactaciones_detalle
         ->select("date_format(fecha_pago, '%m-%Y') mes_pago")
         ->select("numero_cuota")
         ->select("valor_cuota")
         ->select("case when pagado = 1 then 'SI' else 'NO' end as pagado")
         ->where("id_repactacion", $key["id_repactacion"])
         ->findAll();

        $html .= ('
			    		<br>
			    		<table style="border: 1px solid; font-size: 80%; width: 100%;">
			                <thead>
			                    <tr>
			                    	<th style="border: 1px solid; background-color: #17057F; color: white;">N° Cuota</th>
			                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Mes Pago</th>
			                        <th style="border: 1px solid; background-color: #17057F; color: white;">Valor Cuota</th>
			                        <th style="border: 1px solid; background-color: #17057F; color: white;">Pagado</th>
			                    </tr>
			                </thead>
			                <tbody>
			    	');

        foreach ($datosRepactacionesDetalle as $cuotas) {
          $html .= ('
		                    <tr>
		                    	<th style="border: 1px solid; font-weight: normal;">' . $cuotas["numero_cuota"] . '</th>
		                    	<th style="border: 1px solid; font-weight: normal;">' . $cuotas["mes_pago"] . '</th>
		                        <th style="border: 1px solid; font-weight: normal;">$' . number_format($cuotas["valor_cuota"], 0, ',', '.') . '</th>
		                        <th style="border: 1px solid; font-weight: normal;">' . $cuotas["pagado"] . '</th>
		                    </tr>
				    	');
        }

        $html .= ('
			    			</tbody>
			            </table>
			        ');
      }
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene repactaciones asociadas.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $html .= ('
		    	<br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Cambios de Medidor</b>
                    </div>
                </div>
            ');

    $datosCambiosMedidor = $this->cambio_medidor
     ->select("case when funcionarios.rut is not null then concat(funcionarios.rut, '-', funcionarios.dv) else 'NO REGISTRADO' end as rut_funcionario")
     ->select("concat(funcionarios.nombres, ' ', funcionarios.ape_pat, ' ', funcionarios.ape_mat) as nombre_funcionario")
     ->select("cambio_medidor.motivo_cambio")
     ->select("date_format(cambio_medidor.fecha_cambio, '%d-%m-%Y') as fecha_cambio")
     ->join("funcionarios", "cambio_medidor.id_funcionario = funcionarios.id")
     ->where("cambio_medidor.id_socio", $id_socio)
     ->findAll();

    if ($datosCambiosMedidor != NULL) {
      $html .= ('
		    		<br>
		    		<table style="border: 1px solid; font-size: 80%; width: 100%;">
		                <thead>
		                    <tr>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">RUT Func.</th>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Nombre Func.</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Motivo del Cambio</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Fecha del Cambio</th>
		                    </tr>
		                </thead>
		                <tbody>
		    	');

      foreach ($datosCambiosMedidor as $key) {
        $html .= ('
	                    <tr>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["rut_funcionario"] . '</th>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["nombre_funcionario"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["motivo_cambio"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["fecha_cambio"] . '</th>
	                    </tr>
			    	');
      }

      $html .= ('
		    			</tbody>
		            </table>
		        ');
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene cambios de medidor asociados.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $html .= ('
		    	<br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Consumo</b>
                    </div>
                </div>
            ');

    $datosMetros = $this->metros
     ->select("metros.folio_bolect")
     ->select("metros.consumo_actual as metros")
     ->select("metros.metros as metros_consumidos")
     ->select("metros.subtotal")
     ->select("metros.monto_subsidio")
     ->select("metros.multa")
     ->select("metros.total_servicios")
     ->select("metros.cuota_repactacion")
     ->select("metros.total_mes")
     ->select("date_format(metros.fecha_ingreso, '%d-%m-%Y') as fecha_toma_lectura")
     ->select("date_format(metros.fecha_vencimiento, '%d-%m-%Y') as fecha_vencimiento_pago")
     ->select("metros_estados.glosa as estado")
     ->join("metros_estados", "metros.estado = metros_estados.id")
     ->where("metros.id_socio", $id_socio)
     ->orderBy("metros.id", "DESC")
     ->findAll();

    if ($datosMetros != NULL) {
      $html .= ('
		    		<br>
		    		<table style="border: 1px solid; font-size: 80%; width: 100%;">
		                <thead>
		                    <tr>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Folio DTE.</th>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Lectura m<sup>3</sup></th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Consumo m<sup>3</sup></th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Subtotal</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Monto Subsidio</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Multa</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Total Servicios</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Cuota Repactación</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Total Mes</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Fecha Toma Lect.</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Fecha Vencimiento Pago</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Estado</th>
		                    </tr>
		                </thead>
		                <tbody>
		    	');

     // echo $datosMetros['cuota_repactacion']);

      foreach ($datosMetros as $key) {

        

        // if($key["cuota_repactacion"]==""){
        //     $key["cuota_repactacion"]=0;
        // }

        // echo '--->'.$key["cuota_repactacion"].'<-->';

        $html .= ('
	                    <tr>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["folio_bolect"] . '</th>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["metros"] . 'm<sup>3</sup></th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["metros_consumidos"] . 'm<sup>3</sup></th>
	                        
	                        <th style="border: 1px solid; font-weight: normal;">' . number_format($key["monto_subsidio"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . number_format($key["multa"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . number_format($key["total_servicios"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . number_format($key["cuota_repactacion"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . number_format($key["total_mes"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["fecha_toma_lectura"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["fecha_vencimiento_pago"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["estado"] . '</th>
	                    </tr>
			    	');
      }

  

      $html .= ('
		    			</tbody>
		            </table>
		        ');
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene consumos de agua asociados.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $html .= ('
		    	<br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Pagos</b>
                    </div>
                </div>
            ');

    $datosCaja = $this->caja
     ->select("caja.total_pagar")
     ->select("caja.entregado")
     ->select("caja.vuelto")
     ->select("forma_pago.glosa as forma_pago")
     ->select("caja.numero_transaccion")
     ->select("concat(usuarios.nombres, ' ', usuarios.ape_paterno, ' ', usuarios.ape_materno) as usuario")
     ->select("date_format(caja.fecha, '%d-%m-%Y %H:%m:%s') as fecha")
    ->select(" case when caja.estado=1 then 'PAGADO' else 'ANULADO' end as estado")
     ->join("usuarios", "caja.id_usuario = usuarios.id")
     ->join("forma_pago", "caja.id_forma_pago = forma_pago.id")
     ->where("caja.id_socio", $id_socio)
     ->orderBy("caja.id", "DESC")
     ->findAll();

//rint_r($datosCaja);
//exit();
    if ($datosCaja != NULL) {
      $html .= ('
		    		<br>
		    		<table style="border: 1px solid; font-size: 80%; width: 100%;">
		                <thead>
		                    <tr>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Total Pagado</th>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Entregado</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Vuelto</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Forma Pago</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">N° Transacción</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Usuario Recibe Pago</th>
		                        <th style="border: 1px solid; background-color: #17057F; color: white;">Fecha</th>
                                <th style="border: 1px solid; background-color: #17057F; color: white;">Estado</th>
		                    </tr>
		                </thead>
		                <tbody>
		    	');

      foreach ($datosCaja as $key) {
        $html .= ('
	                    <tr>
	                    	<th style="border: 1px solid; font-weight: normal;">' . number_format($key["total_pagar"], 0, ',', '.') . '</th>
	                    	<th style="border: 1px solid; font-weight: normal;">' . number_format($key["entregado"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . number_format($key["vuelto"], 0, ',', '.') . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["forma_pago"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["numero_transaccion"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["usuario"] . '</th>
	                        <th style="border: 1px solid; font-weight: normal;">' . $key["fecha"] . '</th>
                            <th style="border: 1px solid; font-weight: normal;">' . $key["estado"] . '</th>
	                    </tr>
			    	');
      }

      $html .= ('
		    			</tbody>
		            </table>
		        ');
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene pagos asociados.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $html .= ('
		    	<br><br>
                <div style="width: 100%; float: left;">
                    <div style="font-size: 110%;">
                        <b>Abonos</b>
                    </div>
                </div>
            ');

    $datosAbonos = $this->abonos
     ->select("abono")
     ->select("date_format(fecha, '%d-%m-%Y %H:%m:%s') as fecha")
     ->where("id_socio", $id_socio)
     ->orderBy("id", "DESC")
     ->findAll();

    if ($datosAbonos != NULL) {
      $html .= ('
		    		<br>
		    		<table style="border: 1px solid; font-size: 80%; width: 50%;">
		                <thead>
		                    <tr>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Abono</th>
		                    	<th style="border: 1px solid; background-color: #17057F; color: white;">Fecha</th>
		                    </tr>
		                </thead>
		                <tbody>
		    	');

      foreach ($datosAbonos as $key) {
        $html .= ('
	                    <tr>
	                    	<th style="border: 1px solid; font-weight: normal;">' . number_format($key["abono"], 0, ',', '.') . '</th>
	                    	<th style="border: 1px solid; font-weight: normal;">' . $key["fecha"] . '</th>
	                    </tr>
			    	');
      }

      $html .= ('
		    			</tbody>
		            </table>
		        ');

      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>Monto de abonos disponible:</b> ' . number_format($datosSocio["abono"], 0, ',', '.') . '</li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    } else {
      $html .= ('
		    		<div style="width: 100%; float: left;">
	                    <div style="font-size: 110%;">
	                        <ul>
	                        	<li style="list-style:none;"><b>No tiene Abonos asociados.</b></li>
	                        </ul>
	                    </div>
	                </div>
		    	');
    }

    $verificar_dispositivo = $this->verificar_dispositivo();

    $this->mpdf->writeHtml($html);

    return $this->mpdf->Output("Informe Histórico Socio " . $datosSocio["rol"] . ".pdf", "D");

    if ($verificar_dispositivo == "mobil" or $verificar_dispositivo == "tablet") {
      return $this->mpdf->Output("Informe Histórico Socio " . $datosSocio["rol"] . ".pdf", "D");
    } else {
      return redirect()->to($this->mpdf->Output("Informe Histórico Socio " . $datosSocio["rol"] . ".pdf", "I"));
    }
  }

  public function verificar_dispositivo() {
    $tablet_browser = 0;
    $mobile_browser = 0;
    $body_class     = 'desktop';

    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
      $tablet_browser ++;
      $body_class = "tablet";
    }

    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
      $mobile_browser ++;
      $body_class = "mobile";
    }

    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
      $mobile_browser ++;
      $body_class = "mobile";
    }

    $mobile_ua     = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = [
     'w3c ',
     'acs-',
     'alav',
     'alca',
     'amoi',
     'audi',
     'avan',
     'benq',
     'bird',
     'blac',
     'blaz',
     'brew',
     'cell',
     'cldc',
     'cmd-',
     'dang',
     'doco',
     'eric',
     'hipt',
     'inno',
     'ipaq',
     'java',
     'jigs',
     'kddi',
     'keji',
     'leno',
     'lg-c',
     'lg-d',
     'lg-g',
     'lge-',
     'maui',
     'maxo',
     'midp',
     'mits',
     'mmef',
     'mobi',
     'mot-',
     'moto',
     'mwbp',
     'nec-',
     'newt',
     'noki',
     'palm',
     'pana',
     'pant',
     'phil',
     'play',
     'port',
     'prox',
     'qwap',
     'sage',
     'sams',
     'sany',
     'sch-',
     'sec-',
     'send',
     'seri',
     'sgh-',
     'shar',
     'sie-',
     'siem',
     'smal',
     'smar',
     'sony',
     'sph-',
     'symb',
     't-mo',
     'teli',
     'tim-',
     'tosh',
     'tsm-',
     'upg1',
     'upsi',
     'vk-v',
     'voda',
     'wap-',
     'wapa',
     'wapi',
     'wapp',
     'wapr',
     'webc',
     'winw',
     'winw',
     'xda ',
     'xda-'
    ];

    if (in_array($mobile_ua, $mobile_agents)) {
      $mobile_browser ++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
      $mobile_browser ++;
      //Check for tablets on opera mini alternative headers
      $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
      if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
        $tablet_browser ++;
      }
    }
    if ($tablet_browser > 0) {
      // Si es tablet has lo que necesites
      return 'tablet';
    } else {
      if ($mobile_browser > 0) {
        // Si es dispositivo mobil has lo que necesites
        return 'mobil';
      } else {
        // Si es ordenador de escritorio has lo que necesites
        return 'ordenador';
      }
    }
  }
}