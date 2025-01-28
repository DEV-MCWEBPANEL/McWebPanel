<?php

/*
This file is part of McWebPanel.
Copyright (C) 2020-2025 DEV-MCWEBPANEL

    McWebPanel is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    McWebPanel is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with McWebPanel.  If not, see <https://www.gnu.org/licenses/>.
*/

require_once "../template/session.php";
require_once "../template/errorreport.php";
require_once "../config/confopciones.php";

$retorno = "";

function test_input($data)
{
    if (isset($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
  $_SESSION['VALIDADO'] = "NO";
  $_SESSION['KEYSECRETA'] = "0";
}

if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

  if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconfmine', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconfmine'] == 1) {

    if (isset($_POST['action']) && !empty($_POST['action'])) {

      function escribir($lakey, $elvalor)
      {

        //OBTENER CARPETA MINECRAFT
        $reccarpmine = CONFIGDIRECTORIO;

        //ASIGNAR RUTAS
        $rutacarpetamine = dirname(getcwd()) . PHP_EOL;
        $rutacarpetamine = trim($rutacarpetamine);
        $rutacarpetamine .= "/" . $reccarpmine;
        $rutatemp = $rutacarpetamine;
        $rutacarpetamine .="/server.properties";
        $rutatemp .= "/serverproperties.tmp";
        $contador = 0;

        clearstatcache();
        if (file_exists($rutacarpetamine)) {
          $gestor = @fopen($rutacarpetamine, "r");
          $file = fopen($rutatemp, "w");

          while (($búfer = fgets($gestor, 4096)) !== false) {
            $str = $búfer;
            $array = explode("=", $str);

            if ($array[0] == $lakey) {
              fwrite($file, $lakey . '=' . $elvalor . PHP_EOL);
              $contador = 1;
            } else {
              fwrite($file, $búfer);
            }
          }

          if ($contador == 0) {
            fwrite($file, $lakey . '=' . $elvalor . PHP_EOL);
          }

          if (!feof($gestor)) {
            echo "Error: fallo inesperado de fgets()\n";
            return 1;
          } else {
            fclose($gestor);
            fclose($file);
            rename($rutatemp, $rutacarpetamine);
            return 0;
          }
        }
      }

      session_write_close();

      $elaction = trim($_POST['action']);
      $elresultado = trim($_POST['valor']);

      $retorno = escribir($elaction, $elresultado);
    }

    echo $retorno;
  }
}
