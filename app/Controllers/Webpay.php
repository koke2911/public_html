<?php 
	namespace App\Controllers;
	use App\Models\Consumo\Md_metros;
	use App\Models\Consumo\Md_metros_traza;
	use App\Models\Pagos\Md_caja;
	use App\Models\Pagos\Md_caja_detalle;
	use App\Models\Pagos\Md_caja_traza;
	use App\Models\Formularios\Md_socios;
	use App\Models\Pagos\Md_webpay;
	use App\Models\Pagos\Md_caja_webpay;
	use App\Models\Configuracion\Md_apr;

	class Webpay extends Auth {
		protected $metros;
		protected $metros_traza;
		protected $caja;
		protected $caja_detalle;
		protected $caja_traza;
		protected $socios;
		protected $webpay;
		protected $caja_webpay;
		protected $apr;
		protected $db;

		public function __construct() {
			$this->metros = new Md_metros();
			$this->metros_traza = new Md_metros_traza();
			$this->caja = new Md_caja();
			$this->caja_detalle = new Md_caja_detalle();
			$this->caja_traza = new Md_caja_traza();
			$this->socios = new Md_socios();
			$this->webpay = new Md_webpay();
			$this->caja_webpay = new Md_caja_webpay();
			$this->apr = new Md_apr();
			$this->db = \Config\Database::connect();
		}

		public function crear_folio_webpay() {
			$token = ($this->request->getHeader("Authorization")!=null)?$this->request->getHeader("Authorization")->getValue():"";
			if ($this->validateToken($token) == true) {
				if ($this->request->getMethod() == "post") {
					define("WEBPAY", 4);
					define("WEBPAY_USER", 9);
					define("ANULADO", 0);
					
					$forma_pago = WEBPAY;
					$id_usuario = WEBPAY_USER;
					$fecha = date("Y-m-d H:i:s");
					$estado_pago = ANULADO;
					
					$arr_datos = json_decode(file_get_contents("php://input")); 
					// return $this->respond(["message" => $arr_datos], 401);

					$this->webpay->save(["vci" => null]);
					$datosWebpay = $this->webpay->select("max(id_webpay) as id_webpay")->first();
					$id_webpay = $datosWebpay["id_webpay"];

					$data_webpay = [
						"orden_compra" => $id_webpay
					];

					$a = 0;
					foreach ($arr_datos as $datos => $datosPago) {
						$e = 0;
						foreach ($datosPago as $key => $value) {
							switch ($e) {
								case 0:
									$id_socio = $value;
									break;
								case 1:
									$id_apr = $value;
									break;
								case 2:
									$arr_ids_metros = $value;
									break;
								case 3:
									$arr_total_pagar = $value;
									break;
							}
							$e++;
						}

						$total_pagar = 0;
						
						foreach ($arr_total_pagar as $key => $value) {
							$total_pagar = $total_pagar + $value;
						}

						$datosPago = [
							"total_pagar" => $total_pagar,
							"entregado" => 0,
							"vuelto" => 0,
							"id_forma_pago" => $forma_pago,
							"id_socio" => $id_socio,
							"id_usuario" => $id_usuario,
							"fecha" => $fecha,
							"id_apr" => $id_apr,
							"estado" => $estado_pago
						];

						$this->caja->save($datosPago);
						$datosPagoBuscar = $this->caja->select("max(id) as id_caja")->first();
						$id_caja = $datosPagoBuscar["id_caja"];

						$datosPagoTraza = [
							"id_caja" => $id_caja,
							"estado" => 1,
							"observacion" => "Pago ingresado anulado, por concepto de WebPay",
							"id_usuario" => $id_usuario,
							"fecha" => $fecha
						];

						$this->caja_traza->save($datosPagoTraza);

						$datosCajaWebpay = [
							"id_caja" => $id_caja,
							"id_webpay" => $id_webpay
						];

						$this->caja_webpay->save($datosCajaWebpay);

						$datosApr = $this->apr->select("codigo_comercio")->where("id", $id_apr)->first();
						$codigo_comercio = $datosApr["codigo_comercio"];
						
						$childs = [
							"child" => $id_caja,
							"total_pagar" => $total_pagar,
							"codigo_comercio" => $codigo_comercio
						];

						$data_webpay["childs"][$a] = $childs;

						foreach ($arr_ids_metros as $key => $id_metro) {
							$datosPagoDetalle = [
								"id_caja" => $id_caja,
								"id_metros" => $id_metro
							];

							$this->caja_detalle->save($datosPagoDetalle);
						}

						$a++;
					}

					$respuesta = [
						"message" => "Datos guardados con éxito",
						"estado" => "exito",
						"data" => $data_webpay
					];

					return $this->respond($respuesta, 200);
				} else {
					$respuesta = [
						"message" => "No hay datos enviados por post",
						"estado" => "error",
						"folio" => ""
					];

					return $this->respond($respuesta, 401);
				}
			} else {
				$respuesta = [
					"message" => "Token Inválido",
					"estado" => "error",
					"folio" => ""
				];

				return $this->respond($respuesta, 401);
			}
		}

		public function consulta_deuda() {
			$token = ($this->request->getHeader("Authorization")!=null)?$this->request->getHeader("Authorization")->getValue():"";
			if ($this->validateToken($token) == true) {
				if ($this->request->getMethod() == "post") {
					$rut = $this->request->getPost('rut');
					define("ACTIVO", 1);

					$datosSocios = $this->socios
					->select("socios.id as id_socio")
					->select("socios.rol")
					->select("concat(socios.nombres, ' ', socios.ape_pat, ' ', socios.ape_mat) as nombre")
					->select("m.total_mes as total_pagar")
					->select("socios.id_apr")
					->select("m.id as id_metros")
					->select("apr.nombre as apr")
					->select("apr.codigo_comercio")
					->select("date_format(m.fecha_ingreso, '%m/%Y') as mes_consumo")
					->join("metros m", "m.id_socio = socios.id")
					->join("apr", "m.id_apr = apr.id")
					->where("socios.rut", $rut)
					->where("socios.estado", ACTIVO)
					->where("m.estado", ACTIVO)
					->findAll();

					$salida = array('data' => $datosSocios);
					return $this->respond($salida, 200);
				} else {
					$respuesta = [
						"message" => "No hay datos enviados por post",
						"estado" => "error",
						"folio" => ""
					];

					return $this->respond($respuesta, 401);
				}
			} else {
				$respuesta = [
					"message" => "Token Inválido",
					"estado" => "error",
					"folio" => ""
				];

				return $this->respond($respuesta, 401);
			}
		}

		public function anular_pago() {
			$token = ($this->request->getHeader("Authorization")!=null)?$this->request->getHeader("Authorization")->getValue():"";
			if ($this->validateToken($token) == true) {
				if ($this->request->getMethod() == "post") {
					define("PUNTO_BLUE", 9);
					define("ANULADO", 0);
					define("ANULADO_TRAZA", 2);
					define("PENDIENTE", 1);
					define("PAGO_ANULADO", 6);

					$id_webpay = $this->request->getPost('orden_compra');
					$fecha = date("Y-m-d H:i:s");
					$id_usuario = PUNTO_BLUE;

					$datosCajaWebpay = $this->caja_webpay->select("id_caja")->where("id_webpay", $id_webpay)->findAll();

					foreach ($datosCajaWebpay as $key) {
						$id_caja = $key["id_caja"];	

						$datosPago = [
							"id" => $id_caja,
							"estado" => ANULADO,
							"id_usuario" => $id_usuario,
							"fecha" => $fecha
						];

						$this->caja->save($datosPago);

						$datosPagoTraza = [
							"id_caja" => $id_caja,
							"estado" => ANULADO_TRAZA,
							"id_usuario" => $id_usuario,
							"fecha" => $fecha
						];

						$this->caja_traza->save($datosPagoTraza);

						$datosPagoDetalle = $this->caja_detalle->select("*")->where("id_caja", $id_caja)->findAll();
						
						foreach ($datosPagoDetalle as $key) {
							$datosMetros = [
								"id" => $key["id_metros"],
								"estado" => PENDIENTE,
								"id_usuario" => $id_usuario,
								"fecha" => $fecha
							];

							$this->metros->save($datosMetros);
							
							$datosMetrosTraza = [
								"id_metros" => $key["id_metros"],
								"estado" => PAGO_ANULADO,
								"id_usuario" => $id_usuario,
								"fecha" => $fecha
							];

							$this->metros_traza->save($datosMetrosTraza);
						}
					}

					$respuesta = [
						"message" => "Pago anulado con éxito",
						"estado" => "exito",
						"folio" => ""
					];

					return $this->respond($respuesta, 200);
				}else {
					$respuesta = [
						"message" => "No hay datos enviados por post",
						"estado" => "error",
						"folio" => ""
					];

					return $this->respond($respuesta, 401);
				}
			} else {
				$respuesta = [
					"message" => "Token Inválido",
					"estado" => "error",
					"folio" => ""
				];

				return $this->respond($respuesta, 401);
			}
		}

		public function confirmar_pago() {
			$token = ($this->request->getHeader("Authorization")!=null)?$this->request->getHeader("Authorization")->getValue():"";
			if ($this->validateToken($token) == true) {
				if ($this->request->getMethod() == "post") {
					$datosWebpay = json_decode(file_get_contents("php://input")); 

					if ($this->webpay->save($datosWebpay)) {
						define("PAGADO", 2);
						define("PAGADO_TRAZA", 5);
						define("ACTIVO", 1);
						define("PAGADO_TRAZA_CAJA", 1);
						define("PUNTO_BLUE", 9);

						$estado = PAGADO;
						$estado_pago = ACTIVO;
						$fecha = date("Y-m-d H:i:s");
						$id_usuario = PUNTO_BLUE;
						
						$respuesta = [
							"message" => "Datos de transacción guardados con éxito",
							"estado" => "exito",
							"folio" => ""
						];

						$id_webpay = $datosWebpay->id_webpay;
						$datosCajaWebpay = $this->caja_webpay->select("id_caja")->where("id_webpay", $id_webpay)->findAll();

						foreach ($datosCajaWebpay as $key) {
							$id_caja = $key["id_caja"];
							
							$datosCaja = [
								"id" => $id_caja,
								"estado" => $estado_pago
							];

							$this->caja->save($datosCaja);

							$datosCajaTraza = [
								"id_caja" => $id_caja,
								"estado" => PAGADO_TRAZA_CAJA,
								"observacion" => "Pago confirmado, por concepto de WebPay",
								"id_usuario" => $id_usuario,
								"fecha" => $fecha
							];

							$this->caja_traza->save($datosCajaTraza);

							$datosCajaDetalle = $this->caja_detalle->select("id_metros")->where("id_caja", $id_caja)->findAll();

							foreach ($datosCajaDetalle as $key) {
								$id_metro = $key["id_metros"];

								$datosMetros = [
									"id" => $id_metro,
									"estado" => $estado,
									"id_usuario" => $id_usuario,
									"fecha" => $fecha
								];

								$this->metros->save($datosMetros);
									
								$datosMetrosTraza = [
									"id_metros" => $id_metro,
									"estado" => PAGADO_TRAZA,
									"id_usuario" => $id_usuario,
									"fecha" => $fecha
								];

								$this->metros_traza->save($datosMetrosTraza);
							}
						}

						return $this->respond($respuesta, 200);
					} else {
						$respuesta = [
							"message" => "Error al guardar datos de transacción",
							"estado" => "error",
							"folio" => ""
						];

						return $this->respond($respuesta, 401);
					}
				} else {
					$respuesta = [
						"message" => "No hay datos enviados por post",
						"estado" => "error",
						"folio" => ""
					];

					return $this->respond($respuesta, 401);
				}
			} else {
				$respuesta = [
					"message" => "Token Inválido",
					"estado" => "error",
					"folio" => ""
				];

				return $this->respond($respuesta, 401);
			}
		}
	}
?>