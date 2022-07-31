<?php

function get_palabras() {
  $palabras = file_get_contents('diccionarios/diccionario.txt');
  $palabras = explode("\n", $palabras);
  return $palabras;
}

function get_anagramas($input) {
  $palabras = get_palabras();

  if (!in_array($input, $palabras)) {
    return FALSE;
  }

  $result = [];
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
      $result[] = $palabra;
    }
  }
  return $result;
}

function get_anagramas_list() {
  $index = unserialize(file_get_contents('index.serialized'));
  return $index;
}

function build_index() {
  echo "Building index...\n";
  $palabras = get_palabras();
  $result = [];
  foreach ($palabras as $palabra) {
    echo $palabra . "\n";
    $anagramas = get_anagramas($palabra);
    if ($anagramas) {
      $result[$palabra] = $anagramas;
    }
  }
  echo "Writing index...\n";
  file_put_contents('index.serialized', serialize($result));
  return $result;
}

function clear_dictionary() {
  $palabras = file_get_contents('diccionarios/diccionario.txt');
  $palabras = explode("\n", $palabras);
  foreach ($palabras as $k => $v) {
    if (is_numeric($v)) {
      unset($palabras[$k]);
    }
  }
  $palabras = implode("\n", $palabras);
  file_put_contents('diccionarios/diccionario.txt', $palabras);
}

function help() {
  echo "Operations: get, list, build-index, clear-dictionary\n";
  echo "\n";
  echo "- get <word> (gets anagramas for a given word)\n";
  echo "- list (list all anagramas\n";
  echo "- build-index (builds an index of all anagramas)\n";
  echo "- clear-dictionary (internal use)\n";
  echo "\n";
  echo "Usage: php busca_anagramas.php <operation> [arguments]\n";
}

if (isset($argv[1])) {
  $op = $argv[1];
}
else {
  help();
  exit;
}

switch($op) {
  case 'get':
    $input = $argv[2];
    $anagramas = get_anagramas($input);
    if ($anagramas === FALSE) {
      echo "$input no es una palabra válida\n";
      break;
    }
    foreach ($anagramas as $anagrama) {
      echo "He encontrado el siguiente anagrama: " . $anagrama . "\n";
    }
    break;
  case 'list':
    $anagramas_list = get_anagramas_list();
    foreach ($anagramas_list as $palabra => $anagramas) {
      if (!$anagramas) {
        continue;
      }
      echo $palabra . " (" . count($anagramas) . "):\n";
      foreach ($anagramas as $anagrama) {
        echo "- " . $anagrama . "\n";      
      }
    }
    break;
  case 'build-index':
    build_index();
    break;
  case 'clear-dictionary':
    clear_dictionary();
    break;

  default:
    echo "Unkown operation.\n";
}

