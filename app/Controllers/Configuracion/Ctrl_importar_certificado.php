<?php

namespace App\Controllers\Configuracion;

use App\ThirdParty\Touchef;
use App\Controllers\BaseController;
use App\Models\Configuracion\Md_apr;
use App\Models\Configuracion\Md_apr_traza;
use App\Models\Configuracion\Md_folios_timbrados;
use App\Models\Configuracion\Md_certificadosSii;

class Ctrl_importar_certificado extends BaseController {

  protected $sesión;
  protected $db;
  protected $apr_traza;
  protected $folios_timbrados;
  protected $certificados;

  public function __construct() {
    $this->sesión    = session();
    $this->db        = \Config\Database::connect();
    $this->apr_traza = new Md_apr_traza();
    $this->apr       = new Md_apr();
    $this->folios_timbrados  = new Md_folios_timbrados();
    $this->certificados  = new Md_certificadosSii();
  }

  public function validar_sesion() {
    if (!$this->sesión->has("id_usuario_ses")) {
      echo "La sesión expiró, actualice el sitio web con F5";
      exit();
    }
  }

  public function mostrar_certificado($id_apr) {

    $this->validar_sesion();
    // $id_apr=$this->sesión->id_apr_ses;
    $db=$this->db;
    
    $consulta="SELECT f.id,
                  f.nombre_documento,
                  f.folio_desde,
                  f.folio_hasta,
                  f.total_folios,
                  f.fecha_autorizacion,
                  f.total_folios,
                  f.estado,
                  f.folio_hasta-(a.ultimo_folio) as disponibles

    FROM folios_timbrados f
              inner join apr a on a.id=f.id_apr
              where f.id_apr=$id_apr  order by id desc limit 2";
    $query = $db->query($consulta);
    $result  = $query->getResultArray();

     foreach ($result as $key) {
      // $disponibles= $key["folio_hasta"] - $key["ultimo_folio"];
      $row = [
       "id"      => $key["id"],
       "archivo" => $key["nombre_documento"],
       "desde"   => $key["folio_desde"],
       "hasta"   => $key["folio_hasta"],
       "total"   => $key["total_folios"],
       "fecha"   => $key["fecha_autorizacion"],
       "disponibles"   => $key["disponibles"],
       "estado"        => $key["estado"]       
      ];

      $data[] = $row;
    }

    
    rsort($data);
    
    if (isset($data)) {
      $salida = ["data" => $data];

      return json_encode($salida);
    } else {
      return "{ \"data\": [] }";
    }
    
}

  public function importar_folios($id_apr){
    $this->validar_sesion();

    if ($this->request->getMethod() == "post") {
      $file     = $this->request->getFile("folios");
      $password = $this->request->getPost('password');

      if (!$file->isValid()) {
        throw new RuntimeException($file->getErrorString() . "(" . $file->getError() . ")");
      } else {

      $content = file_get_contents($file->getPathname());
      
      $xml = simplexml_load_string($content);

      $re = (string)$xml->CAF->DA->RE;
      $rs = (string)$xml->CAF->DA->RS;
      $td = (string)$xml->CAF->DA->TD;
      $rng_d = (string)$xml->CAF->DA->RNG->D;
      $rng_h = (string)$xml->CAF->DA->RNG->H;
      $fa = (string)$xml->CAF->DA->FA;
      $rsapk_m = (string)$xml->CAF->DA->RSAPK->M;
      $rsapk_e = (string)$xml->CAF->DA->RSAPK->E;
      $idk = (string)$xml->CAF->DA->IDK;
      $frma = (string)$xml->CAF->FRMA;
      $rsask = (string)$xml->RSASK;
      $rsapubk = (string)$xml->RSAPUBK;

      // $rsask = str_replace("-----BEGIN RSA PRIVATE KEY-----", "", $rsask);
      // $rsask = str_replace("-----END RSA PRIVATE KEY-----", "", $rsask);
      
      // $rsapubk = str_replace("-----BEGIN PUBLIC KEY-----", "", $rsapubk);
      // $rsapubk = str_replace("-----END PUBLIC KEY-----", "", $rsapubk);

     $nombre=$file->getName();

      $datos_folios = [        
        "sw_ambiente" => 1, //PRODUCCION
        "estado" => 1,
        "rut_emisor" => $re,
        "razonsocial_emisor" => $rs,
        "folio_timbraje" => (int)$rng_h-(int)$rng_d,
        "tipo_documento" => (int)$td,
        "folio_desde" => (int)$rng_d,
        "nombre_documento" => $nombre,
        "folio_hasta" => (int)$rng_h,
        "total_folios" => (int)$rng_h-(int)$rng_d,
        "folios_disponibles" => (int)$rng_h-(int)$rng_d,
        "fecha_autorizacion" => $fa,
        "modulo_folios" => $rsapk_m,
        "exponente" => $rsapk_e,
        "indice" => $idk,
        "firma_folios" => $frma,
        "llave_privadafolios" => $rsask,
        "llave_publicafolios" => $rsapubk,
        "siguiente_folio" => (int)$rng_d,
        "id_apr" => $id_apr
      ];

      $ultimo_folio = $this->folios_timbrados->where('id_apr', $id_apr)->where('estado', 1)->orderBy('folio_hasta', 'DESC')->first();
      $id_ultimo=$ultimo_folio['id'];
     
      

      if($this->folios_timbrados->save($datos_folios)){
          
           $datos_folios = [
            "id" => $id_ultimo,
            "estado"=> 0
          ];

          if($this->folios_timbrados->save($datos_folios)){
           
            $datos_apr=[
              "id" => $id_apr,
              "ultimo_folio"=> (int)$rng_d - 1,
            ];

            if($this->apr->save($datos_apr)){
                echo 0;
            }else{
              echo "Error al modificar siguiente Folio";
            }
            
          }else{
            echo "Error al modificar estado Folio anterior";
          }

      }else{
        echo 'Error al guardar los folios';
      }
      }
    } else {
      echo "No se han recibido los datos del certificado, favor actualice el sitio he intente nuevamente";
    }
  }

  public function importar_certificado($id_apr) {

   ini_set('allow_url_fopen', 'On');
$this->validar_sesion();

$db = $this->db;

if ($this->request->getMethod() == "post") {
    $file = $this->request->getFile("certificate");
    $password = $this->request->getPost('password');

    if (!$file->isValid()) {
        echo 'ERROR: Archivo no válido.';
    } else {
        $content = file_get_contents($file->getPathname());

        if ($content === false) {
            die('No se pudo leer el archivo PFX.');
        }

        $certificados = [];

        if (openssl_pkcs12_read($content, $certificados, $password)) {
            // Certificado X509
            $x509Cert = $certificados['cert'];

            // Clave privada
            $privateKey = $certificados['pkey'];
          
            // $fp = fopen('clave_privada.pem', 'w');
            // fwrite($fp, $privateKey);
            // fclose($fp);

            // $protected_key_file='clave_privada.pem';

            // $command_unprotect = "openssl rsa -in {$protected_key_file}";
            // $unprotected_key = shell_exec($command_unprotect);

            // unlink('clave_privada.pem');
            // Crear un proceso para OpenSSL y quitar la protección sin crear archivos
            $descriptorspec = array(
                0 => array("pipe", "r"),  // stdin
                1 => array("pipe", "w"),  // stdout
                2 => array("pipe", "w")   // stderr
            );

            // Abre un proceso de OpenSSL para quitar la protección de la clave
            $process = proc_open('openssl rsa', $descriptorspec, $pipes);

            if (is_resource($process)) {
                // Enviar la clave protegida al proceso
                fwrite($pipes[0], $privateKey);
                fclose($pipes[0]);

                // Leer la clave privada sin protección de la salida estándar (stdout)
                $private_key_unprotected = stream_get_contents($pipes[1]);
                fclose($pipes[1]);

                // Leer cualquier error (stderr)
                $errors = stream_get_contents($pipes[2]);
                fclose($pipes[2]);

                // Cerrar el proceso
                $return_value = proc_close($process);

                // Mostrar el resultado
                if ($return_value != 0) {
                    // echo "Clave privada sin protección:\n" . $private_key_unprotected . "\n";
                    echo "Error al quitar la protección de la clave:\n" . $errors;
                } 
            }
            // exit();
            $privateKeyResource = openssl_pkey_get_private($privateKey);

            // Asegúrate de que la clave privada es válida
            if ($privateKeyResource === false) {
                echo "Error al obtener la clave privada sin llave: " . openssl_error_string();
                exit();
            }           
            // Obtener los detalles del certificado
            $certDetails = openssl_x509_parse($x509Cert);

            // Eliminar los headers del certificado X509
            $x509Cert = preg_replace('/(-----BEGIN CERTIFICATE-----|-----END CERTIFICATE-----)/', '', $x509Cert);

            // echo trim($x509Cert).'---';

            
            // Modulus y Exponent
            $keyDetails = openssl_pkey_get_details($privateKeyResource);
            // print_r($keyDetails);
            if ($keyDetails && isset($keyDetails['rsa'])) {
                // Modulus en Base64
                $modulusBase64 = base64_encode($keyDetails['rsa']['n']);
                // Exponent en Base64
                $exponentBase64 = base64_encode($keyDetails['rsa']['e']);
            } else {
                echo "No se pudo obtener los detalles de la clave privada.\n";
                exit();
            }

            // Fecha de caducidad del certificado
            $expiryDate = isset($certDetails['validTo_time_t']) ? date('Y-m-d H:i:s', $certDetails['validTo_time_t']) : '--';

            // Consulta para actualizar el estado
            $consulta = "UPDATE certificadosii SET estado=0 WHERE id_apr=$id_apr";
            $db->query($consulta);

            // Salidas de depuración
            // echo "X509 Certificate:\n" . $x509Cert . "\n\n";
            // echo "Private Key: " . $privateKey . "\n";
            // // echo "Private Key (PEM):\n" . $privateKeyPemOut . "\n\n";
            // echo "Modulus (Base64):\n" . $modulusBase64 . "\n";
            // echo "Exponent (Base64):\n" . $exponentBase64 . "\n";
            // echo "Fecha de caducidad del certificado: " . $expiryDate . "\n";

            // Guardar los datos del certificado
            $rut_apr = $this->sesión->rut_apr_ses . "-" . $this->sesión->dv_apr_ses;


            $datos_certificado = [
                'rut_autorizado' => $rut_apr,
                'x509' => trim( $x509Cert),
                'modulo' => $modulusBase64,
                'llave_privada' => trim($privateKey),
                'llave_sin_clave'=> trim($private_key_unprotected),
                'id_apr' => $id_apr,
                'estado' => 1,
                'exponente' => $exponentBase64,
                'fecha_caducidad' => $expiryDate
            ];

          // print_r($datos_certificado);exit();

            if ($this->certificados->save($datos_certificado)) {
                echo 0; // Éxito
            } else {
                echo 'Imposible cargar el certificado: ' . $this->certificados->errors();
            }
        } else {
            echo 'Error al leer el archivo PFX. Asegúrate de que la contraseña es correcta: ' . openssl_error_string();
        }
    }
} else {
    echo "No se han recibido los datos del certificado, favor actualice el sitio e intente nuevamente";
}
  }
}