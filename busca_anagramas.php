<?php

$input = $argv[1];

$palabras = file_get_contents('diccionarios/diccionario.txt');
$palabras = explode("\n", $palabras);

foreach ($palabras as $palabra) {
  if ($palabra === $input) {
    continue;
  }
  if (strlen($input) != strlen($palabra)) {
    continue;
  }
  $palabra_array = str_split($palabra);
  $input_array = str_split($input);

  foreach ($palabra_array as $j => $palabra_letter) {
    $found = false;
    foreach($input_array as $k => $input_letter) {
      if ($palabra_letter === $input_letter) {
        $found = true;
        unset($input_array[$k]);
      }
    }
    if (!$found) {
      continue(2);
    }
  }
  if (!$input_array) {
    echo "He encontrado el siguiente anagrama: " . $palabra . "\n";
  }
}
