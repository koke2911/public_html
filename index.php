<?php
$array = scan('public/js', []);
$fp    = fopen('public/js/all.min.js', 'w');
foreach ($array as $line) {
  fwrite($fp, file_get_contents($line) . "\n");
}
fclose($fp);
function scan($path, $array) {
  $dir = scandir($path);
  foreach ($dir as $file) {
    if ($file == '.' || $file == '..') {
      continue;
    }
    if (is_dir($path . "/" . $file)) {
      $array = array_merge(scan($path . "/" . $file, $array), $array);
    }
    if (is_file($path . "/" . $file)) {
      $info = pathinfo($file);
      if ($info['extension'] == 'js' && $info['filename'] != 'all.min') {
        $array[] = $path . "/" . $file;
      }
    }
  }

  return $array;
}
