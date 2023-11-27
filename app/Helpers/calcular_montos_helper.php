<?php
function calcular_montos(
 $lectura_anterior,
 $lectura_actual,
 $tope_subsidio,
 $datosCostoMetros,
 $cargo_fijo,
 $porcentaje,
 $total_servicios,
 $cuota_repactacion,
 $alcantarillado,
 $cuota_socio,
 $otros
) {
  $metros_consumidos = intval($lectura_actual) - intval($lectura_anterior);

  $cargo_fijoSub=0;
  if($porcentaje>0){
    $cargo_fijoSub= $cargo_fijo * $porcentaje / 100;
  }
  $subtotal = $cargo_fijo;  

  $total_subsidio    = 0;
  if ($metros_consumidos > 0) {
    for ($i = 1; $i <= $metros_consumidos; $i ++) {
      foreach ($datosCostoMetros as $key) {
        if ($i >= intval($key["desde"]) && $i <= intval($key["hasta"])) {
          $subtotal += intval($key["costo"]);
          if ($i <= intval($tope_subsidio)) {
            $total_subsidio = $subtotal-$cargo_fijo;
          }
        }
      }
    }
  }

  // echo $total_servicios;

  $subtotal=$subtotal-$cargo_fijoSub;

  $monto_subsidio   = $total_subsidio * $porcentaje / 100;
  $monto_facturable = intval($monto_subsidio) > intval($subtotal) ? $total_mes = 0 : intval($subtotal) - intval($monto_subsidio);
  $total_mes        = intval($monto_facturable) + intval($cuota_repactacion) + intval($total_servicios) + intval($alcantarillado) + intval($cuota_socio) + intval($otros);

  $respuesta = [
   "metros_consumidos" => $metros_consumidos,
   "subtotal"          => $subtotal,
   "monto_facturable"  => $monto_facturable,
   "monto_subsidio"    => $monto_subsidio,
   "total_mes"         => $total_mes
  ];

  // print_r($respuesta);
  // exit();
  return $respuesta;
}

?>