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

    class Multicanal extends BaseController {
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
    public function CONSULTA($id_apr){
       
        function phpRule_ValidarRut($rut) {

            // Verifica que no esté vacio y que el string sea de tamaño mayor a 3 carácteres(1-9)        
            if ((empty($rut)) || strlen($rut) < 3) {
                return 'RUT vacío o con menos de 3 caracteres.';
            }

            // Quitar los últimos 2 valores (el guión y el dígito verificador) y luego verificar que sólo sea
            // numérico
            $parteNumerica = str_replace(substr($rut, -2, 2), '', $rut);

            if (!preg_match("/^[0-9]*$/", $parteNumerica)) {
                return 'La parte numérica del RUT sólo debe contener números.';
            }

            $guionYVerificador = substr($rut, -2, 2);
            // Verifica que el guion y dígito verificador tengan un largo de 2.
            if (strlen($guionYVerificador) != 2) {
                return 'Error en el largo del dígito verificador.';
            }

            // obliga a que el dígito verificador tenga la forma -[0-9] o -[kK]
            if (!preg_match('/(^[-]{1}+[0-9kK]).{0}$/', $guionYVerificador)) {
                return 'El dígito verificador no cuenta con el patrón requerido';
            }

            // Valida que sólo sean números, excepto el último dígito que pueda ser k
            if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
                return 'Error al digitar el RUT';
            }

            $rutV = preg_replace('/[\.\-]/i', '', $rut);
            $dv = substr($rutV, -1);
            $numero = substr($rutV, 0, strlen($rutV) - 1);
            $i = 2;
            $suma = 0;
            foreach (array_reverse(str_split($numero)) as $v) {
                if ($i == 8) {
                    $i = 2;
                }
                $suma += $v * $i;
                ++$i;
            }
            $dvr = 11 - ($suma % 11);
            if ($dvr == 11) {
                $dvr = 0;
            }
            if ($dvr == 10) {
                $dvr = 'K';
            }
            if ($dvr == strtoupper($dv)) {
                return 'OK';
            } else {
                return 'El RUT ingresado no es válido.';
            }
        }

    if($this->request->getMethod() == "post"){
          $contenido=$this->request->getBody();
        
        $xml = simplexml_load_string($contenido);
        if($xml === false) {
            echo 'Error: el contenido no es un xml válido';            
            header("HTTP/1.1 406 Not Aceptable");
            exit();
        }
        $namespaces = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('soap', $namespaces['soapenv']);
        $xml->registerXPathNamespace('urn', $namespaces['urn']);
        $nodes = $xml->xpath('//soapenv:Body/urn:consulta_deuda/IDCLIENTE');

        if(empty($nodes)) {
            echo 'Error: el xml no tiene un nodo IDCLIENTE';
            header("HTTP/1.1 406 Not Aceptable");
            exit();
        }

        $IDCLIENTE = (string) $nodes[0];
        $this->db = \Config\Database::connect();

        
         $valida=phpRule_ValidarRut($IDCLIENTE);
         if ($valida != 'OK') {
             echo $valida;
             header("HTTP/1.1 406 Not Aceptable");
             exit();
         }
         
         // BUSCA DATOS SOCIO
            $consulta="SELECT * FROM socios where rut = '$IDCLIENTE' and id_apr=$id_apr";
            $query = $this->db->query($consulta);
            $result_socio  = $query->getResultArray();

            $mensaje=
            '<TMENSAJE>
                <CODIGOERROR>0</CODIGOERROR>
                <MENSAJE>OK</MENSAJE>
                </TMENSAJE>';

            if(empty($result_socio)){
                $mensaje=
                '<TMENSAJE>
                <CODIGOERROR>2</CODIGOERROR>
                <MENSAJE>CLIENTE NO EXISTE</MENSAJE>
                </TMENSAJE>';

                $rut='';
                

            }else{
                $rut=$result_socio[0]['rut'].'-'.$result_socio[0]['dv'];                
                $nombre_cliente=$result_socio[0]['nombres'].' '.$result_socio[0]['ape_pat'].' '.$result_socio[0]['ape_mat'];
                $direccion=$result_socio[0]['calle'].' '.$result_socio[0]['numero'].' '.$result_socio[0]['resto_direccion'];
                $fono=$result_socio[0]['email'];
            }
            
            
            $id_socios='';
            foreach ($result_socio as $row) {
                if(!empty($id_socios)){
                    $id_socios.=",".$row['id'];
                }else{
                    $id_socios=$row['id'];
                }
            }
            //BUSCA DATOS DEUDA
            $consultaDeuda="SELECT count(*) as deudas FROM metros where id_socio in ($id_socios) and estado=1 and id_apr=$id_apr";
            $query = $this->db->query($consultaDeuda);
            $result  = $query->getResultArray();
         
           

            if($result[0]['deudas']==0 && !empty($result_socio)){
                 $cant_deuda=0;
                        $deudas=
                        '<DEUDA>
                        <NUMEROCUOTA></NUMEROCUOTA>
                        <FECHA_VENCIMIENTO></FECHA_VENCIMIENTO>
                        <TIPO_DEUDA></TIPO_DEUDA>
                        <INTERES></INTERES>
                        <MONTO></MONTO>
                        <IDDOCUMENTO></IDDOCUMENTO>
                        </DEUDA>';

                 $mensaje=
                        '<TMENSAJE>
                            <CODIGOERROR>0</CODIGOERROR>
                            <MENSAJE>CLIENTE SIN DEUDA</MENSAJE>
                            </TMENSAJE>';
            }else{

                $cant_deuda=$result[0]['deudas'];

                 $consultaDeuda = "SELECT 
                            m.id as iddocumento,
							socios.id as id_socio,   
                            socios.id_apr as id_apr,                         
                            m.total_mes as monto,                          
                            DATE_FORMAT(m.fecha_vencimiento, '%Y%m%d') as fecha_vencimiento,
                            concat('CUOTA MES ', DATE_FORMAT(m.fecha_vencimiento, '%m-%Y')) as tipo_deuda
                            FROM socios
                            JOIN metros m ON m.id_socio = socios.id
                            JOIN apr ON m.id_apr = apr.id
                            WHERE socios.id in ($id_socios)  
                            AND socios.estado = 1
                            AND m.estado = 1";

                $query = $this->db->query($consultaDeuda);
                $result  = $query->getResultArray();

                $deudas='';

                foreach($result as $deuda){
                        $deudas.= 
                '<DEUDA>
                <NUMEROCUOTA></NUMEROCUOTA>
                <FECHA_VENCIMIENTO>'.$deuda['fecha_vencimiento'].'</FECHA_VENCIMIENTO>
                <TIPO_DEUDA>'.$deuda['tipo_deuda'].'</TIPO_DEUDA>
                <INTERES></INTERES>
                <MONTO>'.$deuda['monto'].'</MONTO>
                <IDDOCUMENTO>'.$deuda['iddocumento'].'</IDDOCUMENTO>
                </DEUDA>';
                }
            }
                $ResponseBase= '<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAPENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/">
                <SOAP-ENV:Body>
                <ns1:consulta_deudaResponse xmlns:ns1="urn:consulta_deuda">
                <IDCONSULTA>1</IDCONSULTA>
                <IDCLIENTE>'.$rut.'</IDCLIENTE>
                <DATOLIBRE1>'.$nombre_cliente.'</DATOLIBRE1>
                <DATOLIBRE2>'.$direccion.'</DATOLIBRE2>
                <DATOLIBRE3>'.$fono.'</DATOLIBRE3>
                <DEUDAS>
                <CANT>'.$cant_deuda.'</CANT>
                '.$deudas.'
                </DEUDAS>
                '.$mensaje.'
                </ns1:consulta_deudaResponse>
                </SOAP-ENV:Body>
                </SOAP-ENV:Envelope>';

                echo $ResponseBase;            
                
                
        }else{
             echo $valida;
             header("HTTP/1.1 406 Not Aceptable Method");
             exit();
        }
    }


    public function NOTIFICACION($id_apr){

        if($this->request->getMethod() == "post"){
            $contenido=$this->request->getBody();
            
            $xml = simplexml_load_string($contenido);
            if($xml === false) {
                echo 'Error: el contenido no es un xml válido';            
                header("HTTP/1.1 406 Not Aceptable");
                exit();
            }

            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('soap', $namespaces['soapenv']);
            $xml->registerXPathNamespace('urn', $namespaces['urn']);
            $TPAGO_nodes = $xml->xpath('//soapenv:Body/urn:notifica_pago/TPAGO');
           
            $IDCONSULTA = (string) $TPAGO_nodes[0]->IDCONSULTA;
            $IDCLIENTE = (string) $TPAGO_nodes[0]->IDCLIENTE;
            $MONTOPAGADO = (string) $TPAGO_nodes[0]->MONTOPAGADO;
            $TIPOMONTO = (string) $TPAGO_nodes[0]->TIPOMONTO;
            $IDTRXREC = (string) $TPAGO_nodes[0]->IDTRXREC;
            $IDDOCUMENTO = (string) $TPAGO_nodes[0]->IDDOCUMENTO;
            $FECHAPAGO = (string) $TPAGO_nodes[0]->FECHAPAGO;
            $FECHACONTABLE = (string) $TPAGO_nodes[0]->FECHACONTABLE;            

            $consulta="SELECT * FROM socios where rut = '$IDCLIENTE' and id_apr=$id_apr";
            $query = $this->db->query($consulta);
            $result_socio  = $query->getResultArray();

            $id_socio = $result_socio[0]['id'];

            $forma_pago = 7;
            $id_usuario = 90;
            $fecha = date("Y-m-d H:i:s");
            $estado_pago = 1;
            
           
           $datosPago = [
                "total_pagar" => $MONTOPAGADO,
                "entregado" => 0,
                "vuelto" => 0,
                "id_forma_pago" => $forma_pago,
                "id_socio" => $id_socio,
                "id_usuario" => $id_usuario,
                "fecha" => $fecha,
                "id_apr" => $id_apr,
                "estado" => $estado_pago
            ];           

            if($this->caja->save($datosPago)){

                $datosPagoBuscar = $this->caja->select("max(id) as id_caja")->first();
                $id_caja = $datosPagoBuscar["id_caja"];

                $datosPagoTraza = [
                    "id_caja" => $id_caja,
                    "estado" => 1,
                    "observacion" => "Pago ingresado efectuado, por concepto de Multicanal Banco Estado",
                    "id_usuario" => $id_usuario,
                    "fecha" => $fecha
                ];

                if($this->caja_traza->save($datosPagoTraza)){

                        $datosPagoDetalle = [
                            "id_caja" => $id_caja,
                            "id_metros" => $IDDOCUMENTO
                        ];

                        if($this->caja_detalle->save($datosPagoDetalle)){

                            $datosMetros = [
                                "id" => $IDDOCUMENTO,
                                "estado" => 2,
                                "id_usuario" => $id_usuario,
                                "fecha" => $fecha
                            ];

                            if($this->metros->save($datosMetros)){
                                
                                $datosMetrosTraza = [
                                    "id_metros" => $IDDOCUMENTO,
                                    "estado" => 5,
                                    "id_usuario" => $id_usuario,
                                    "fecha" => $fecha
                                ];

                                if($this->metros_traza->save($datosMetrosTraza)){
                                    $Error= 0;
                                    $mensaje='OK-PAGO';
                                }else{
                                    $Error= 1;
                                    $mensaje='ERROR AL REGISTRAR PAGO EN METROS_TRAZA';
                                }
                            }else{
                                $Error= 1;
                                $mensaje='ERROR AL REGISTRAR PAGO EN METROS';
                            }
                        }else{
                            $Error= 1;
                            $mensaje='ERROR AL REGISTRAR PAGO EN CAJA_DETALLE'; 
                        }
                }else{
                    $Error= 1;
                    $mensaje='ERROR AL REGISTRAR PAGO EN CAJA_TRAZA';
                }
            }else{
                $Error= 1;
                $mensaje='ERROR AL REGISTRAR PAGO EN CAJA';
            }


            $ResponseBase= '<SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAPENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/">
                <SOAP-ENV:Body>
                <ns1:notifica_pagoResponse xmlns:ns1="urn:notifica_pago">
                <TMENSAJE>
                    <CODIGOERROR>'.$Error.'</CODIGO_ERROR>
                    <MENSAJE>'.$mensaje.'</MENSAJE></TMENSAJE>
                </ns1:notifica_pagoResponse>
                </SOAP-ENV:Body>
                </SOAP-ENV:Envelope>';

                echo $ResponseBase;   
        }
    }
    
}
?>


