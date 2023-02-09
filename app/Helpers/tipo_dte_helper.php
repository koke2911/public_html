<?php
function tipo_dte($tipo_documento) {
  switch ($tipo_documento) {
    case 1:
      return 41;
    case 2:
      return 34;
    case 3:
      return 39;
    case 4:
      return 33;
    default:
      return $tipo_documento;
  }
}