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

function converdatos($losbytes, $opcion, $decimal)
{
  $eltipo = "GB";
  $result = $losbytes / 1073741824;

  if ($opcion == 0) {
    $result = strval(round($result, $decimal));
    return $result;
  } elseif ($opcion == 1) {
    $result = strval(round($result, $decimal)) . " " . $eltipo;
    return $result;
  }
}

function obtenersizecarpeta($dir)
{
  $iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir)
  );

  $totalSize = 0;
  try {
    foreach ($iterator as $file) {
      $totalSize += $file->getSize();
    }
  } catch (Throwable $t) {
    //VACIO
  }
  return $totalSize;
}

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
  $_SESSION['VALIDADO'] = "NO";
  $_SESSION['KEYSECRETA'] = "0";
}

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

  if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1) {
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pcompilarspigot', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pcompilarspigot'] == 1) {

      if (isset($_POST['action']) && !empty($_POST['action'])) {
        $retorno = "";
        $elerror = 0;

        $limitmine = CONFIGFOLDERMINECRAFTSIZE;
        $reccarpmine = CONFIGDIRECTORIO;
        $rutacarpetamine = "";
        $getgigasmine = "";

        $laaction = test_input($_POST['action']);

        $carpraiz = dirname(getcwd()) . PHP_EOL;
        $carpraiz = trim($carpraiz);

        $carpcompilar = $carpraiz . "/compilar";
        $elssh = $carpcompilar . "/compilar.sh";
        $elbuildtools = $carpcompilar . "/BuildTools.jar";
        $archivolog = $carpcompilar . "/BuildTools.log.txt";

        //VERIFICAR LECTURA CARPETA RAIZ
        if ($elerror == 0) {
          clearstatcache();
          if (!is_readable($carpraiz)) {
            $retorno = "noreadraiz";
            $elerror = 1;
          }
        }

        //VERIFICAR ESCRITURA CARPETA RAIZ
        if ($elerror == 0) {
          clearstatcache();
          if (!is_writable($carpraiz)) {
            $retorno = "nowriteraiz";
            $elerror = 1;
          }
        }

        if ($elerror == 0) {
          if ($laaction == "compilar") {

            //LIMITE ALMACENAMIENTO
            if ($elerror == 0) {
              if ($_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {

                //MIRAR SI ES ILIMITADO
                if ($limitmine >= 1) {

                  //OBTENER CARPETA SERVIDOR MINECRAFT
                  $rutacarpetamine = dirname(getcwd()) . PHP_EOL;
                  $rutacarpetamine = trim($rutacarpetamine);
                  $rutacarpetamine .= "/" . $reccarpmine;

                  $getgigasmine = converdatos(obtenersizecarpeta($rutacarpetamine), 0, 2);

                  if (!is_numeric($getgigasmine)) {
                    $retorno = "ERRORGETSIZE";
                    $elerror = 1;
                  }

                  if ($elerror == 0) {
                    if ($getgigasmine > $limitmine) {
                      $retorno = "OUTGIGAS";
                      $elerror = 1;
                    }
                  }
                }
              }
            }

            if ($elerror == 0) {
              //SABER SI ESTA EN EJECUCION
              $elcomando = "";
              $nombresession = str_replace("/", "", $carpcompilar);
              $elcomando = "screen -ls | gawk '/\." . $nombresession . "\t/ {print strtonum($1)'}";
              $elpid = shell_exec($elcomando);

              if ($elpid == "") {
                $elerror = 0;
              } else {
                $elerror = 1;
                $retorno = "yaenmarcha";
              }
            }

            if ($elerror == 0) {

              $recjavaselect = CONFIGJAVASELECT;
              $recjavaname = CONFIGJAVANAME;
              $recjavamanual = CONFIGJAVAMANUAL;

              $javaruta = "";

              //OBTENER VERSION AJAX
              $version = test_input($_POST['laversion']);

              //OBTENER CARPETA SERVIDOR MINECRAFT
              $elnombredirectorio = $carpraiz . "/" . CONFIGDIRECTORIO . "/";

              //OBTENER FECHA
              $t = date("Y-m-d-G-i-s");

              //BORRAR CARPETA COMPILAR (SI EXISTIERA)
              clearstatcache();
              if (file_exists($carpcompilar)) {
                if (is_writable($carpcompilar)) {
                  $comando = "cd " . $carpraiz . " && rm -R compilar";
                  exec($comando);
                } else {
                  $retorno = "nowritecompilar";
                  $elerror = 1;
                }
              }
            }

            if ($elerror == 0) {
              //CONFIRMAR VERSION A DESCARGAR CON EL LISTADO

              $url = "https://hub.spigotmc.org/nexus/content/repositories/snapshots/org/spigotmc/spigot-api/maven-metadata.xml";

              $context = stream_context_create(
                array(
                  "http" => array(
                    "timeout" => 10,
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                  )
                )
              );

              $contenido = @file_get_contents($url, false, $context);

              if ($contenido === false) {
                $elerror = 1;
                $retorno = "timeout";
              } else {

                $contenido = htmlentities($contenido);

                $elarray = explode(" ", $contenido);

                for ($i = 0; $i < count($elarray); $i++) {

                  $test = substr_count($elarray[$i], "R0.1-SNAPSHOT");

                  if ($test >= 1) {

                    $test = substr_count($elarray[$i], "pre");

                    if ($test == 0) {

                      $test = substr_count($elarray[$i], "latest");

                      if ($test == 0) {
                        $linea = trim($elarray[$i]);
                        $linea = substr($linea, 0, -30);
                        $linea = substr($linea, 15);
                        $versiones[] = test_input($linea);
                      }
                    }
                  }
                }

                $versiones = array_reverse($versiones);
              }
            }

            //COMPROBAR SI EXISTE LA VERSION SELECCIONADA
            if ($elerror == 0) {

              $verencontrado = 0;

              for ($i = 0; $i < count($versiones); $i++) {
                if ($version == $versiones[$i]) {
                  $verencontrado = 1;
                }
              }

              if ($verencontrado == 0) {
                $elerror = 1;
                $retorno = "noverfound";
              }
            }

            if ($elerror == 0) {

              //CREAR CARPETA
              mkdir($carpcompilar, 0700);

              //FORZAR .htaccess
              $rutahta = $carpcompilar . "/.htaccess";
              $file = fopen($rutahta, "w");
              fwrite($file, "deny from all" . PHP_EOL);
              fclose($file);

              //CREACION DEL SSH
              $file = fopen($elssh, "w");
              fwrite($file, "#!/bin/bash" . PHP_EOL);
              fwrite($file, "wget https://hub.spigotmc.org/jenkins/job/BuildTools/lastSuccessfulBuild/artifact/target/BuildTools.jar" . PHP_EOL);
              fclose($file);

              $comando = "cd " . $carpcompilar . " && chmod +x compilar.sh && sh compilar.sh";
              exec($comando);

              //COMPRUEBA LA DESCARGA DE BUILDTOOLS
              clearstatcache();
              if (!file_exists($elbuildtools)) {
                $retorno = "nobuildtools";
                $elerror = 1;
              }
            }

            //COMPROBAR MEMORIA RAM
            if ($elerror == 0) {
              $totalramsys = shell_exec("free -g | grep Mem | gawk '{ print $2 }'");
              $getramavaliable = shell_exec("free -g | grep Mem | gawk '{ print $7 }'");

              if ($totalramsys != "" && $getramavaliable != "") {

                $totalramsys = trim($totalramsys);
                $totalramsys = intval($totalramsys);

                $getramavaliable = trim($getramavaliable);
                $getramavaliable = intval($getramavaliable);

                //COMPRUEBA SI AL MENOS SE TIENE 1GB
                if ($totalramsys == 0) {
                  $elerror = 1;
                  $retorno = "rammenoragiga";
                }

                if ($totalramsys >= 1) {
                  //COMPRUEBA QUE HAYA AL MENOS 1GB DE MEMORIA DISPONIBLE
                  if ($getramavaliable < 2) {
                    $elerror = 1;
                    $retorno = "ramavaiableout";
                  }
                }
              } else {
                $elerror = 1;
                $retorno = "ramnooutput";
              }
            }

            //INICIAR VARIABLE JAVARUTA Y COMPROBAR SI EXISTE
            if ($elerror == 0) {
              if ($recjavaselect == "0") {
                $javaruta = "java";
              } elseif ($recjavaselect == "1") {
                $javaruta = $recjavaname;
                clearstatcache();
                if (!file_exists($javaruta)) {
                  $retorno = "nojavaenruta";
                  $elerror = 1;
                }
              } elseif ($recjavaselect == "2") {
                $javaruta = $recjavamanual . "/bin/java";
                clearstatcache();
                if (!file_exists($javaruta)) {
                  $retorno = "nojavaenruta";
                  $elerror = 1;
                }
              }
            }

            if ($elerror == 0) {
              $file = fopen($elssh, "w");
              fwrite($file, "#!/bin/bash" . PHP_EOL);
              fwrite($file, "chmod +x BuildTools.jar" . PHP_EOL);
              fwrite($file, "export HOME=" . $carpcompilar . PHP_EOL);
              fwrite($file, "export XDG_CONFIG_HOME=" . $carpcompilar . "/.config" . PHP_EOL);
              fwrite($file, "export M2_HOME=" . $carpcompilar . "/.m2" . PHP_EOL);
              fwrite($file, "git config --global --unset core.autocrlf" . PHP_EOL);
              fwrite($file, $javaruta . " -Xmx2048M -jar " . $carpcompilar . "/BuildTools.jar --rev " . $version . PHP_EOL);
              fwrite($file, "mv spigot-" . $version . ".jar spigot-" . $version . "-" . $t . ".jar" . PHP_EOL);
              fwrite($file, "mv spigot-" . $version . "-" . $t . ".jar " . $elnombredirectorio . "spigot-" . $version . "-" . $t . ".jar" . PHP_EOL);
              fclose($file);

              //DAR PERMISOS AL SH
              $comando = "cd " . $carpcompilar . " && chmod +x compilar.sh";
              exec($comando);

              //GENERAR UNA SESSION PARA EL SCREEN, QUITANDO LAS / DE LA RUTA AL NO ESTAR SOPORTADO
              $nombresession = str_replace("/", "", $carpcompilar);

              //INICIAR SCREEN
              $comando = "cd " . $carpcompilar . " && umask 002 && screen -dmS '" . $nombresession . "' sh compilar.sh";
              exec($comando);
              $retorno = "OK";
            }
          } elseif ($laaction == "consola") {

            //COMPROBAR SI EXISTE LA RUTA
            clearstatcache();
            if (file_exists($archivolog)) {
              //COMPROBAR SI SE PUEDE LEER
              clearstatcache();
              if (is_readable($archivolog)) {
                //LEER ARCHIVO
                $retorno = test_input(file_get_contents($archivolog));
              } else {
                $retorno = "No se puede leer el archivo";
              }
            } else {
              $retorno = "";
            }
          } elseif ($laaction == "estado") {
            //SABER SI ESTA EN EJECUCION
            $elcomando = "";
            $nombresession = str_replace("/", "", $carpcompilar);
            $elcomando = "screen -ls | gawk '/\." . $nombresession . "\t/ {print strtonum($1)'}";
            $elpid = shell_exec($elcomando);

            if ($elpid == "") {
              $retorno = "OFF";
            } else {
              $retorno = "ON";
            }
          } elseif ($laaction == "matarcompilar") {

            //SABER SI ESTA EN EJECUCION
            $elcomando = "";
            $nombresession = str_replace("/", "", $carpcompilar);
            $elcomando = "screen -ls | gawk '/\." . $nombresession . "\t/ {print strtonum($1)'}";
            $elpid = shell_exec($elcomando);

            if ($elpid == "") {
              $retorno = "OFF";
            } else {

              //OBTENER PID COMPILADOR BUILDTOOLS
              $tipserver = trim(exec('whoami'));
              $elpid = "ps au | grep '" . $tipserver . "' | grep '" . $carpcompilar . "/BuildTools.jar' | gawk '{print $2}'";
              $elpid = shell_exec($elpid);
              if ($elpid != "") {
                $elpid = trim($elpid);

                //COMPROBAR QUE SEA NUMERICO
                if (is_numeric($elpid)) {
                  $elcomando = "kill -9 " . $elpid;
                  $elcomando = trim($elcomando);
                  exec($elcomando);
                  $retorno = "OK";
                } else {
                  $retorno = "OFF";
                }
              }
            }
          } elseif ($laaction == "borrarcache") {

            //SABER SI ESTA EN EJECUCION
            $elcomando = "";
            $nombresession = str_replace("/", "", $carpcompilar);
            $elcomando = "screen -ls | gawk '/\." . $nombresession . "\t/ {print strtonum($1)'}";
            $elpid = shell_exec($elcomando);

            if ($elpid == "") {
              //BORRA CONTENIDO CARPETA Y ARCHIVOS OCULTOS EXCEPTO .HTACCESS
              $borracache = "find " . $carpcompilar . " -mindepth 1 ! -name '.htaccess' -exec rm -rf {} +";
              exec($borracache);
              $retorno = "OK";
            } else {
              $retorno = "ON";
            }
          }
        }

        echo $retorno;
      }
    }
  }
}
