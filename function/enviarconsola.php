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

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

  if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconsolaread', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconsolaread'] == 1) {

    if (isset($_POST['action']) && !empty($_POST['action'])) {
      $devolucion = "";
      $rutaarchivo = "";
      $linea = "";
      $elerror = 0;

      //OBTENER LINEAS CONSOLA
      if (!defined('CONFIGLINEASCONSOLA')) {
        $recnumerolineaconsola = 100;
      } else {
        $recnumerolineaconsola = CONFIGLINEASCONSOLA;
      }

      //OBTENER TIPO CONSOLA
      if (!defined('CONFIGCONSOLETYPE')) {
        $recconsoletype = 2;
      } else {
        $recconsoletype = CONFIGCONSOLETYPE;
      }

      //DESACTIVAR SESSION
      session_write_close();

      //OBTENER RUTA LOG MINECRAFT
      $elnombredirectorio = CONFIGDIRECTORIO;
      $rectiposerv = CONFIGTIPOSERVER;
      $rutaarchivo = dirname(getcwd()) . PHP_EOL;
      $rutaarchivo = trim($rutaarchivo);
      $rutaarchivo .= "/" . $elnombredirectorio . "/logs/screen.log";

      //COMPROBAR SI EXISTE LA RUTA
      clearstatcache();
      if (!file_exists($rutaarchivo)) {
        $devolucion = "";
        $elerror = 1;
      }

      //COMPROBAR SI SE PUEDE LEER
      if ($elerror == 0) {
        clearstatcache();
        if (!is_readable($rutaarchivo)) {
          $devolucion = "No se puede leer el archivo";
          $elerror = 1;
        }
      }

      if ($elerror == 0) {

        if ($recnumerolineaconsola == "0") {
          //OBTENER TODO EL LOG
          $laconsola = file_get_contents($rutaarchivo);
        } else {
          //OBTENER X LINEAS DEL LOG
          $elcomando = "tail -n " . $recnumerolineaconsola . " " . $rutaarchivo;
          $laconsola = shell_exec($elcomando);
        }

        if ($laconsola != "") {
          $laconsola = test_input($laconsola);
          //CONVERTIR STRING EN ARRAY
          $arr = preg_split('/\n/', $laconsola);

          if ($recconsoletype == 0) {
            for ($i = 0; $i < count($arr); $i++) {
              $linea = $arr[$i];
              $linea = $linea . "<br>";
              $devolucion .= $linea;
            }
          } elseif ($recconsoletype == 1) {
            //OPCION SIN COLOR
            for ($i = 0; $i < count($arr); $i++) {
              $linea = $arr[$i];

              $elprimer = htmlspecialchars_decode(substr($linea, 0, 4));
              $elsecond = htmlspecialchars_decode(substr($linea, 0, 3));

              if ($elprimer == ">") {
                $linea = substr($linea, 1);
                $linea = ltrim($linea, 'gt');
                $linea = ltrim($linea, ';');
              }

              if ($elsecond == "=&g") {
                $linea = substr($linea, 5);
              }

              $linea = str_replace("\e[?1l&gt;\e[?1000l\e[?2004l\e[?1h=\e[?2004h&gt;", "", $linea);
              $linea = str_replace("\e[?1h=\e[?2004h&gt;", "", $linea);
              $linea = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $linea);
              $linea = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $linea);
              $linea = ltrim($linea, '>');
              $linea = $linea . "<br>";
              $devolucion .= $linea;
            }
          } elseif ($recconsoletype == 2) {
            //OPCION COLOR
            for ($i = 0; $i < count($arr); $i++) {
              $ignore = 0;
              $linea = $arr[$i];
              $uncoded = htmlspecialchars_decode($linea);

              $recorte = substr($uncoded, 0, 4);

              if ($recorte == "> [" || $recorte == "> /") {
                $ignore = 1;
              }

              if ($ignore == 0) {

                $elprimer = htmlspecialchars_decode(substr($linea, 0, 4));
                $elsecond = htmlspecialchars_decode(substr($linea, 0, 3));

                if ($elprimer == ">") {
                  $linea = substr($linea, 1);
                  $linea = ltrim($linea, 'gt');
                  $linea = ltrim($linea, ';');
                }

                if ($elsecond == "=&g") {
                  $linea = substr($linea, 5);
                }

                $linea = str_replace("\e[?1l&gt;\e[?1000l\e[?2004l\e[?1h=\e[?2004h&gt;", "", $linea);
                $linea = str_replace("\e[?1h=\e[?2004h&gt;", "", $linea);

                //añade color base
                $linea = str_replace("\e[K", "<span class='colbase textreset'>", $linea);

                //color negro terminal
                $linea = str_replace("\e[30m", "<span class='colnegro'>", $linea);
                $linea = str_replace("\e[30;1m", "<span class='colnegroclaro textnegrita'>", $linea);
                $linea = str_replace("\e[30;2m", "<span class='colnegrooscuro'>", $linea);
                $linea = str_replace("\e[30;4m", "<span class='colnegro textsubrayado'>", $linea);

                //color negro rgb
                $linea = str_replace("\e[0;30m", "<span class='colnegro'>", $linea);
                $linea = str_replace("\e[0;30;1m", "<span class='colnegroclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;30;2m", "<span class='colnegrooscuro'>", $linea);
                $linea = str_replace("\e[0;30;4m", "<span class='colnegro textnegrita'>", $linea);

                //color rojo terminal
                $linea = str_replace("\e[31m", "<span class='colrojo'>", $linea);
                $linea = str_replace("\e[31;1m", "<span class='colrojoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[31;2m", "<span class='colrojooscuro'>", $linea);
                $linea = str_replace("\e[31;4m", "<span class='colrojo textsubrayado'>", $linea);

                //color rojo rgb
                $linea = str_replace("\e[0;31m", "<span class='colrojo'>", $linea);
                $linea = str_replace("\e[0;31;1m", "<span class='colrojoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;31;2m", "<span class='colrojooscuro'>", $linea);
                $linea = str_replace("\e[0;31;4m", "<span class='colrojo textnegrita'>", $linea);

                //color verde terminal
                $linea = str_replace("\e[32m", "<span class='colverde'>", $linea);
                $linea = str_replace("\e[32;1m", "<span class='colverdeclaro textnegrita'>", $linea);
                $linea = str_replace("\e[32;2m", "<span class='colverdeoscuro'>", $linea);
                $linea = str_replace("\e[32;4m", "<span class='colverde textsubrayado'>", $linea);

                //color verde terminal
                $linea = str_replace("\e[0;32m", "<span class='colverde'>", $linea);
                $linea = str_replace("\e[0;32;1m", "<span class='colverdeclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;32;2m", "<span class='colverdeoscuro'>", $linea);
                $linea = str_replace("\e[0;32;4m", "<span class='colverde textnegrita'>", $linea);

                //color amarillo terminal
                $linea = str_replace("\e[33m", "<span class='colamarillo'>", $linea);
                $linea = str_replace("\e[33;1m", "<span class='colamarilloclaro textnegrita'>", $linea);
                $linea = str_replace("\e[33;2m", "<span class='colamarilloscuro'>", $linea);
                $linea = str_replace("\e[33;4m", "<span class='colamarillo textsubrayado'>", $linea);

                //color amarillo rgb
                $linea = str_replace("\e[0;33m", "<span class='colamarillo'>", $linea);
                $linea = str_replace("\e[0;33;1m", "<span class='colamarilloclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;33;2m", "<span class='colamarilloscuro'>", $linea);
                $linea = str_replace("\e[0;33;4m", "<span class='colamarillo textnegrita'>", $linea);
                $linea = str_replace("\e[0;33;22m", "<span class='colamarilloscuro'>", $linea);

                //color azul terminal
                $linea = str_replace("\e[34m", "<span class='colazul'>", $linea);
                $linea = str_replace("\e[34;1m", "<span class='colazulclaro textnegrita'>", $linea);
                $linea = str_replace("\e[34;2m", "<span class='colazuloscuro'>", $linea);
                $linea = str_replace("\e[34;4m", "<span class='colazul textsubrayado'>", $linea);

                //color azul rgb
                $linea = str_replace("\e[0;34m", "<span class='colazul'>", $linea);
                $linea = str_replace("\e[0;34;1m", "<span class='colazulclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;34;2m", "<span class='colazuloscuro'>", $linea);
                $linea = str_replace("\e[0;34;4m", "<span class='colazul textnegrita'>", $linea);

                //color magenta terminal
                $linea = str_replace("\e[35m", "<span class='colmagenta'>", $linea);
                $linea = str_replace("\e[35;1m", "<span class='colmagentaclaro textnegrita'>", $linea);
                $linea = str_replace("\e[35;2m", "<span class='colmagentaoscuro'>", $linea);
                $linea = str_replace("\e[35;4m", "<span class='colmagenta textsubrayado'>", $linea);

                //color magenta rgb
                $linea = str_replace("\e[0;35m", "<span class='colmagenta'>", $linea);
                $linea = str_replace("\e[0;35;1m", "<span class='colmagentaclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;35;2m", "<span class='colmagentaoscuro'>", $linea);
                $linea = str_replace("\e[0;35;4m", "<span class='colmagenta textnegrita'>", $linea);

                //color cyan terminal
                $linea = str_replace("\e[36m", "<span class='colcyan'>", $linea);
                $linea = str_replace("\e[36;1m", "<span class='colcyanclaro textnegrita'>", $linea);
                $linea = str_replace("\e[36;2m", "<span class='colcyanoscuro'>", $linea);
                $linea = str_replace("\e[36;4m", "<span class='colcyan textsubrayado'>", $linea);

                //color cyan rgb
                $linea = str_replace("\e[0;36m", "<span class='colcyan'>", $linea);
                $linea = str_replace("\e[0;36;1m", "<span class='colcyanclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;36;2m", "<span class='colcyanoscuro'>", $linea);
                $linea = str_replace("\e[0;36;4m", "<span class='colcyan textnegrita'>", $linea);

                //color blanco terminal
                $linea = str_replace("\e[37m", "<span class='colblanco'>", $linea);
                $linea = str_replace("\e[37;1m", "<span class='colblancoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[37;2m", "<span class='colblancooscuro'>", $linea);
                $linea = str_replace("\e[37;4m", "<span class='colblanco textsubrayado'>", $linea);

                //color blanco rgb
                $linea = str_replace("\e[0;37m", "<span class='colblanco'>", $linea);
                $linea = str_replace("\e[0;37;1m", "<span class='colblancoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;37;2m", "<span class='colblancooscuro'>", $linea);
                $linea = str_replace("\e[0;37;4m", "<span class='colblanco textsubrayado'>", $linea);

                //color negro brillante terminal
                $linea = str_replace("\e[90m", "<span class='colbrillantenegro'>", $linea);
                $linea = str_replace("\e[90;1m", "<span class='colbrillantenegroclaro textnegrita'>", $linea);
                $linea = str_replace("\e[90;2m", "<span class='colbrillantenegrooscuro'>", $linea);
                $linea = str_replace("\e[90;4m", "<span class='colbrillantenegro textsubrayado'>", $linea);

                //color negro brillante rgb
                $linea = str_replace("\e[0;90m", "<span class='colbrillantenegro'>", $linea);
                $linea = str_replace("\e[0;90;1m", "<span class='colbrillantenegroclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;90;2m", "<span class='colbrillantenegrooscuro'>", $linea);
                $linea = str_replace("\e[0;90;4m", "<span class='colbrillantenegro textsubrayado'>", $linea);

                //color rojo brillante terminal
                $linea = str_replace("\e[91m", "<span class='colbrillanterojo'>", $linea);
                $linea = str_replace("\e[91;1m", "<span class='colbrillanterojoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[91;2m", "<span class='colbrillanterojooscuro'>", $linea);
                $linea = str_replace("\e[91;4m", "<span class='colbrillanterojo textsubrayado'>", $linea);

                //color rojo brillante rgb
                $linea = str_replace("\e[0;91m", "<span class='colbrillanterojo'>", $linea);
                $linea = str_replace("\e[0;91;1m", "<span class='colbrillanterojoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;91;2m", "<span class='colbrillanterojooscuro'>", $linea);
                $linea = str_replace("\e[0;91;4m", "<span class='colbrillanterojo textsubrayado'>", $linea);

                //color verde brillante terminal
                $linea = str_replace("\e[92m", "<span class='colbrillanteverde'>", $linea);
                $linea = str_replace("\e[92;1m", "<span class='colbrillanteverdeclaro textnegrita'>", $linea);
                $linea = str_replace("\e[92;2m", "<span class='colbrillanteverdeoscuro'>", $linea);
                $linea = str_replace("\e[92;4m", "<span class='colbrillanteverde textsubrayado'>", $linea);

                //color verde brillante rgb
                $linea = str_replace("\e[0;92m", "<span class='colbrillanteverde'>", $linea);
                $linea = str_replace("\e[0;92;1m", "<span class='colbrillanteverdeclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;92;2m", "<span class='colbrillanteverdeoscuro'>", $linea);
                $linea = str_replace("\e[0;92;4m", "<span class='colbrillanteverde textsubrayado'>", $linea);

                //color amarillo brillante terminal
                $linea = str_replace("\e[93m", "<span class='colbrillanteamarillo'>", $linea);
                $linea = str_replace("\e[93;1m", "<span class='colbrillanteamarilloclaro textnegrita'>", $linea);
                $linea = str_replace("\e[93;2m", "<span class='colbrillanteamarillooscuro'>", $linea);
                $linea = str_replace("\e[93;4m", "<span class='colbrillanteamarillo textsubrayado'>", $linea);

                //color amarillo brillante rgb
                $linea = str_replace("\e[0;93m", "<span class='colbrillanteamarillo'>", $linea);
                $linea = str_replace("\e[0;93;1m", "<span class='colbrillanteamarilloclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;93;2m", "<span class='colbrillanteamarillooscuro'>", $linea);
                $linea = str_replace("\e[0;93;4m", "<span class='colbrillanteamarillo textsubrayado'>", $linea);

                //color azul brillante terminal
                $linea = str_replace("\e[94m", "<span class='colbrillanteazul'>", $linea);
                $linea = str_replace("\e[94;1m", "<span class='colbrillanteazulclaro textnegrita'>", $linea);
                $linea = str_replace("\e[94;2m", "<span class='colbrillanteazuloscuro'>", $linea);
                $linea = str_replace("\e[94;4m", "<span class='colbrillanteazul textsubrayado'>", $linea);

                //color azul brillante rgb
                $linea = str_replace("\e[0;94m", "<span class='colbrillanteazul'>", $linea);
                $linea = str_replace("\e[0;94;1m", "<span class='colbrillanteazulclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;94;2m", "<span class='colbrillanteazuloscuro'>", $linea);
                $linea = str_replace("\e[0;94;4m", "<span class='colbrillanteazul textsubrayado'>", $linea);

                //color magenta brillante terminal
                $linea = str_replace("\e[95m", "<span class='colbrillantemagenta'>", $linea);
                $linea = str_replace("\e[95;1m", "<span class='colbrillantemagentaclaro textnegrita'>", $linea);
                $linea = str_replace("\e[95;2m", "<span class='colbrillantemagentaoscuro'>", $linea);
                $linea = str_replace("\e[95;4m", "<span class='colbrillantemagenta textsubrayado'>", $linea);

                //color magenta brillante rgb
                $linea = str_replace("\e[0;95m", "<span class='colbrillantemagenta'>", $linea);
                $linea = str_replace("\e[0;95;1m", "<span class='colbrillantemagentaclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;95;2m", "<span class='colbrillantemagentaoscuro'>", $linea);
                $linea = str_replace("\e[0;95;4m", "<span class='colbrillantemagenta textsubrayado'>", $linea);

                //color cyan brillante terminal
                $linea = str_replace("\e[96m", "<span class='colbrillantecyan'>", $linea);
                $linea = str_replace("\e[96;1m", "<span class='colbrillantecyanclaro textnegrita'>", $linea);
                $linea = str_replace("\e[96;2m", "<span class='colbrillantecyanoscuro'>", $linea);
                $linea = str_replace("\e[96;4m", "<span class='colbrillantecyan textsubrayado'>", $linea);

                //color cyan brillante rgb
                $linea = str_replace("\e[0;96m", "<span class='colbrillantecyan'>", $linea);
                $linea = str_replace("\e[0;96;1m", "<span class='colbrillantecyanclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;96;2m", "<span class='colbrillantecyanoscuro'>", $linea);
                $linea = str_replace("\e[0;96;4m", "<span class='colbrillantecyan textsubrayado'>", $linea);

                //color blanco brillante terminal
                $linea = str_replace("\e[97m", "<span class='colbrillanteblanco'>", $linea);
                $linea = str_replace("\e[97;1m", "<span class='colbrillanteblancoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[97;2m", "<span class='colbrillanteblancooscuro'>", $linea);
                $linea = str_replace("\e[97;4m", "<span class='colbrillanteblanco textsubrayado'>", $linea);

                //color blanco brillante rgb
                $linea = str_replace("\e[0;97m", "<span class='colbrillanteblanco'>", $linea);
                $linea = str_replace("\e[0;97;1m", "<span class='colbrillanteblancoclaro textnegrita'>", $linea);
                $linea = str_replace("\e[0;97;2m", "<span class='colbrillanteblancooscuro'>", $linea);
                $linea = str_replace("\e[0;97;4m", "<span class='colbrillanteblanco textsubrayado'>", $linea);

                //LIMPIA TEXTO RESTANTE
                $linea = preg_replace('#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $linea);
                $linea = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $linea);

                $linea = $linea . "<span class='colbase textreset'><br>";
                $devolucion .= $linea;
              }
            }
          }
        }
      }
      echo $devolucion;
    }
  }
}
