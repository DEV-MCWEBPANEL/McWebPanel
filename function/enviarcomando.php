<?php

/*
This file is part of McWebPanel.
Copyright (C) 2020-2024 DEV-MCWEBPANEL

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

function test_input($data)
{
  if (isset($data)) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
}

function test_input2($data)
{
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
  $_SESSION['VALIDADO'] = "NO";
  $_SESSION['KEYSECRETA'] = "0";
}

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

  if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconsolaenviar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconsolaenviar'] == 1) {

    if (isset($_POST['action']) && !empty($_POST['action'])) {

      $retorno = "";
      $elcomando = "";
      $dirconfig = "";
      $elnombrescreen = "";
      $elpid = "";
      $laejecucion = "";
      $paraejecutar = "";
      $permcomando = "";
      $elerror = 0;

      $carpraiz = dirname(getcwd()) . PHP_EOL;
      $carpraiz = trim($carpraiz);

      $carpconfig = $carpraiz . "/config";
      $archbuffer = $carpconfig . "/buffer.json";

      $paraejecutar = addslashes($_POST['action']);

      //OBTENER PID SABER SI ESTA EN EJECUCION
      $elnombrescreen = CONFIGDIRECTORIO;
      $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
      $elpid = shell_exec($elcomando);

      if ($elerror == 0) {
        if (strlen($paraejecutar) > 4096) {
          $elerror = 1;
          $retorno = "lenmax";
        }
      }

      if ($elerror == 0) {
        $buscar = preg_match('/[\^][a-zA-Z]/', $paraejecutar);
        if ($buscar >= 1) {
          $retorno = "badchars";
          $elerror = 1;
        }
      }


      if ($elerror == 0) {
        //SI ESTA EN EJECUCION ENVIAR COMANDO
        if (!$elpid == "") {
          $laejecucion = 'screen -S ' . $elnombrescreen . ' -X stuff "' . trim($paraejecutar) . '^M"';
          exec($laejecucion);
          $retorno = "ok";

          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbuffer', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbuffer'] == 1) {

            if (defined('CONFIGBUFFERLIMIT')) {
              //VERIFICAR ESCRITURA CARPETA CONFIG
              if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($carpconfig)) {
                  //$retorno = "nowritecarpconfig";
                  $elerror = 1;
                }
              }

              if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($archbuffer)) {
                  $array[0]['comando'] = strval($paraejecutar);

                  //GUARDAR ARRAY EN ARCHIVO
                  $serialized = serialize($array);
                  file_put_contents($archbuffer, $serialized);
                } else {

                  //COMPROBAR SI SE PUEDE LEER EL JSON
                  if ($elerror == 0) {
                    clearstatcache();
                    if (!is_readable($archbuffer)) {
                      //$retorno = "errjsonnoread";
                      $elerror = 1;
                    }
                  }

                  //COMPROBAR SI SE PUEDE ESCRIBIR EL JSON
                  if ($elerror == 0) {
                    clearstatcache();
                    if (!is_writable($archbuffer)) {
                      //$retorno = "errjsonnowrite";
                      $elerror = 1;
                    }
                  }

                  $bufflimite = CONFIGBUFFERLIMIT;

                  if ($elerror == 0) {

                    if ($bufflimite >= 1) {

                      $getarray = file_get_contents($archbuffer);
                      $arrayobtenido = unserialize($getarray);

                      $elindice = count($arrayobtenido);

                      if ($elindice < $bufflimite) {
                        //CUANDO NO SE SUPERA EL LIMITE
                        $arrayobtenido[$elindice]['comando'] = test_input2($_POST['action']);
                        $serialized = serialize($arrayobtenido);
                        file_put_contents($archbuffer, $serialized);
                      } else {
                        //CUANDO SE SUPERA EL LIMITE
                        $elcontador = 0;
                        for ($i = 1; $i < $elindice; $i++) {
                          $auxarray[$elcontador]['comando'] = test_input2($_POST['action']);
                          $elcontador++;
                        }
                        $elindice = count($auxarray);
                        $auxarray[$elindice]['comando'] = test_input2($_POST['action']);
                        $serialized = serialize($auxarray);
                        file_put_contents($archbuffer, $serialized);
                      }
                    }
                  }
                }
              }
            }
          }
        } else {
          $retorno = "off";
        }
      }
      echo $retorno;
    }
  }
}
