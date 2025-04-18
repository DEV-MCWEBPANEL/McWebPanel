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

  if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('psystemconf', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconf'] == 1) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      if (isset($_POST['action']) && $_POST['action'] === 'submit') {

        $elerror = 0;
        $test = 0;

        $reccarpmine = CONFIGDIRECTORIO;

        //VARIABLE RUTA SERVIDOR MINECRAFT
        $rutacarpetamine = dirname(getcwd()) . PHP_EOL;
        $rutacarpetamine = trim($rutacarpetamine);
        $rutacarpetamine .= "/" . $reccarpmine;

        //OBTENER RUTA RAIZ
        $dirraiz = dirname(getcwd()) . PHP_EOL;
        $dirraiz = trim($dirraiz);


        //INPUT LISTADO JARS
        if (isset($_POST["listadojars"])) {
          $ellistadojars = test_input($_POST["listadojars"]);

          //COMPOBAR SI HAY ".." "..."
          $verificar = array('..', '...', '/.', '~', '../', './', ';', ':', '>', '<', '/', '\\', '&&', '#', "|", '$', '%', '!', '`', '&', '*', '{', '}', '?', '=', '@', "'", '"', "'\'");

          for ($i = 0; $i < count($verificar); $i++) {

            $test = substr_count($ellistadojars, $verificar[$i]);

            if ($test >= 1) {
              $retorno = "novalidoname";
              $elerror = 1;
            }
          }

          //VERIFICAR SI EXISTE REALMENTE
          if ($elerror == 0) {
            $rutajar = $rutacarpetamine . "/" . $ellistadojars;

            clearstatcache();
            if (!file_exists($rutajar)) {
              $elerror = 1;
              $retorno = "noexistejar";
            }
          }

          //COMPROBAR SI ES REALMENTE ARCHIVO JAVA
          if ($elerror == 0) {
            $tipovalido = 0;
            $eltipoapplication = mime_content_type($rutajar);

            switch ($eltipoapplication) {
              case "application/java-archive":
                $tipovalido = 1;
                break;
              case "application/zip":
                $tipovalido = 1;
                break;
            }

            if ($tipovalido == 0) {
              $retorno = "notipovalido";
              $elerror = 1;
            }
          }
        } else {
          $ellistadojars = "";
        }

        //INPUT PUERTO
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfpuerto', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfpuerto'] == 1) {

            if (isset($_POST["elport"])) {
              $elpuerto = test_input($_POST["elport"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($elpuerto)) {
                  $retorno = "portnonumerico";
                  $elerror = 1;
                }
              }

              //RANGO
              if ($elerror == 0) {
                if ($elpuerto < 1024 || $elpuerto > 65535) {
                  $retorno = "portoutrango";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "portvacio";
              $elerror = 1;
            }
          } else {
            $elpuerto = CONFIGPUERTO;
          }
        }

        //INPUT XMS RAM
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfmemoria', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfmemoria'] == 1) {
            if (isset($_POST['elraminicial'])) {
              $laramxms = test_input($_POST["elraminicial"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($laramxms)) {
                  $retorno = "ramnonumerico";
                  $elerror = 1;
                }
              }

              $salida = shell_exec("free -m | grep Mem | gawk '{ print $2 }'");
              $salida2 = shell_exec("free -g | grep Mem | gawk '{ print $2 }'");

              if ($salida != "" && $salida2 != "") {
                $totalram = trim($salida);
                $salida2 = trim($salida2);

                if ($totalram <= 0) {
                  $retorno = "raminsuficiente";
                  $elerror = 1;
                } else {
                  if ($laramxms > $totalram) {
                    $retorno = "ramxmsoutrange";
                    $elerror = 1;
                  }
                }
              } else {
                $retorno = "ramnooutput";
                $elerror = 1;
              }

              //COMPROBAR SI LA XMS NO ES MANIPULADA
              if ($elerror == 0) {
                $test = 0;

                $validoxms = array(128, 256, 512);

                for ($i = 1; $i <= $salida2; $i++) {
                  array_push($validoxms, 1024 * $i);
                }

                for ($i = 0; $i < count($validoxms); $i++) {
                  if ($validoxms[$i] == $laramxms) {
                    $test = 1;
                  }
                }

                if ($test == 0) {
                  $retorno = "xmsmodexternal";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "ramvacia";
              $elerror = 1;
            }
          } else {

            if (!defined('CONFIGXMSRAM')) {
              $laramxms  = 1024;
            } else {
              $laramxms = CONFIGXMSRAM;
            }
          }
        }

        //INPUT RAM
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfmemoria', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfmemoria'] == 1) {
            if (isset($_POST["elram"])) {
              $laram = test_input($_POST["elram"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($laram)) {
                  $retorno = "ramnonumerico";
                  $elerror = 1;
                }
              }

              if ($elerror == 0) {
                $salida = "";
                $salida2 = "";

                $salida = shell_exec("free -m | grep Mem | gawk '{ print $2 }'");
                $salida2 = shell_exec("free -g | grep Mem | gawk '{ print $2 }'");

                if ($salida != "" && $salida2 != "") {
                  $totalram = trim($salida);
                  $salida2 = trim($salida2);

                  if ($totalram <= 0) {
                    $retorno = "raminsuficiente";
                    $elerror = 1;
                  } else {
                    if ($laram > $totalram) {
                      $retorno = "ramoutrange";
                      $elerror = 1;
                    }
                  }
                } else {
                  $retorno = "ramnooutput";
                  $elerror = 1;
                }
              }

              //COMPROBAR SI LA XMS NO ES MANIPULADA
              if ($elerror == 0) {
                $test = 0;

                $validoxmx = array(125, 256, 512);

                for ($i = 1; $i <= $salida2; $i++) {
                  array_push($validoxmx, 1024 * $i);
                }

                for ($i = 0; $i < count($validoxmx); $i++) {
                  if ($validoxmx[$i] == $laram) {
                    $test = 1;
                  }
                }

                if ($test == 0) {
                  $retorno = "xmxmodexternal";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "ramvacia";
              $elerror = 1;
            }
          } else {
            $laram = CONFIGRAM;
          }
        }

        //VERIFICAR SI XMS ES SUPERIOR A XMX
        if ($elerror == 0) {
          if ($laramxms > $laram) {
            $retorno = "xmsuperiorram";
            $elerror = 1;
          }
        }

        //INPUT TIPO SERVIDOR
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconftipo', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconftipo'] == 1) {
            if (isset($_POST["eltipserv"])) {
              $eltiposerver = test_input($_POST["eltipserv"]);
              $opcionesserver = array('vanilla', 'spigot', 'paper', 'forge old', 'forge new', 'NeoForge', 'magma', 'otros');

              if ($elerror == 0) {
                //COMPROBAR SI EL TIPO SERVER ES CORRECTO
                if (!in_array($eltiposerver, $opcionesserver)) {
                  $retorno = "badtipserv";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "tipservvacio";
              $elerror = 1;
            }
          } else {
            $eltiposerver = CONFIGTIPOSERVER;
          }
        }

        //INPUT SUBIDA MAXIMA FICHEROS
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfsubida', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfsubida'] == 1) {
            if (isset($_POST["elmaxupload"])) {
              $eluploadmax = test_input($_POST["elmaxupload"]);
              $opcionesserver = array('128', '256', '386', '512', '640', '768', '896', '1024', '2048', '3072', '4096', '5120');
              if ($elerror == 0) {
                if (!in_array($eluploadmax, $opcionesserver)) {
                  $retorno = "badmaxupload";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "maxuploadvacio";
              $elerror = 1;
            }
          } else {
            $eluploadmax = CONFIGMAXUPLOAD;
          }
        }

        //INPUT NOMBRE SERVIDOR
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfnombre', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfnombre'] == 1) {
            if (isset($_POST["elnomserv"])) {
              $elnombreservidor = test_input($_POST["elnomserv"]);
            } else {
              $retorno = "nomservvacio";
              $elerror = 1;
            }
          } else {
            $elnombreservidor = CONFIGNOMBRESERVER;
          }
        }

        //INPUT BOOTCONFIG
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemstartonboot', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemstartonboot'] == 1) {

            if (isset($_POST["elbootconf"])) {
              $elbootconfig = test_input($_POST["elbootconf"]);
              if ($elbootconfig != "SI") {
                $elbootconfig = "NO";
              }
            } else {
              $retorno = "bootconfvacio";
              $elerror = 1;
            }
          } else {
            $elbootconfig = CONFIGBOOTSYSTEM;
          }
        }

        //LINEAS CONSOLA
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconflinconsole', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconflinconsole'] == 1) {
            if (isset($_POST["linconsola"])) {
              $elnumerolineaconsola = test_input($_POST["linconsola"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (is_numeric($elnumerolineaconsola)) {
                  $elnumerolineaconsola = intval($elnumerolineaconsola);
                } else {
                  $retorno = "lineasconsolanonumerico";
                  $elerror = 1;
                }
              }

              //RANGO
              if ($elerror == 0) {
                if ($elnumerolineaconsola < 0 || $elnumerolineaconsola > 1000) {
                  $retorno = "lineasconsolaoutrango";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "linconsolavacio";
              $elerror = 1;
            }
          } else {
            $elnumerolineaconsola = intval(CONFIGLINEASCONSOLA);
          }
        }

        //LINEAS BUFFER
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbuffer', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbuffer'] == 1) {
            if (isset($_POST["bufferlimit"])) {
              $elbufferlimit = test_input($_POST["bufferlimit"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (is_numeric($elbufferlimit)) {
                  $elbufferlimit = intval($elbufferlimit);
                } else {
                  $retorno = "buffernonumerico";
                  $elerror = 1;
                }
              }

              //RANGO
              if ($elerror == 0) {
                if ($elbufferlimit < 0 || $elbufferlimit > 500) {
                  $retorno = "bufferoutrango";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "buffervacio";
              $elerror = 1;
            }
          } else {
            $elbufferlimit = intval(CONFIGBUFFERLIMIT);
          }
        }

        //TIPO CONSOLA
        if ($elerror == 0) {

          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconftypeconsole', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconftypeconsole'] == 1) {
            if (isset($_POST["eltipoconsola"])) {
              $eltypeconsola = test_input($_POST["eltipoconsola"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($eltypeconsola)) {
                  $retorno = "typenonumero";
                  $elerror = 1;
                }
              }

              //RANGO
              if ($elerror == 0) {
                if ($eltypeconsola < 0 || $eltypeconsola > 2) {
                  $retorno = "typeoutrango";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "typeconsolavacio";
              $elerror = 1;
            }
          } else {
            if (!defined('CONFIGCONSOLETYPE')) {
              $eltypeconsola = 2;
            } else {
              $eltypeconsola = CONFIGCONSOLETYPE;
            }
          }
        }

        //EXTRAS TAMAÑO CARPETAS
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2) {

            if (isset($_POST['gestorshowsizefolder'])) {
              $elmostrarsizecarpeta = test_input($_POST["gestorshowsizefolder"]);

              if ($elmostrarsizecarpeta != 1) {
                $elmostrarsizecarpeta = "";
              }
            } else {
              $elmostrarsizecarpeta = "";
            }
          } else {
            $elmostrarsizecarpeta = CONFIGSHOWSIZEFOLDERS;
          }
        }

        //EXTRA IGNORAR LIMITE RAM
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfignoreramlimit', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfignoreramlimit'] == 1) {

            if (isset($_POST['gestorignoreram'])) {
              $elignorarlimitram = test_input($_POST["gestorignoreram"]);

              if ($elignorarlimitram != 1) {
                $elignorarlimitram = "";
              }
            } else {
              $elignorarlimitram = "";
            }
          } else {
            $elignorarlimitram = CONFIGIGNORERAMLIMIT;
          }
        }

        //ARGUMENTOS JAVA
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemcustomarg', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemcustomarg'] == 1) {
            $cogercheck = 0;
            $checkarg = array('..', '...', '~', '../', './', ';', '>', '<', '\\', '&&', '#', "|", '$', '%', '!', '`', '&', '*', '{', '}', '?', '@', "'", '"', "'\'", '-Xms', '-Xmx', '-port', 'Dfile.encoding=UTF8', '-jar', 'java', 'cd ..');

            //ARGUMENTO INICIO
            if (isset($_POST['argmanualinicio'])) {
              $elargmanualinicio = $_POST["argmanualinicio"];
              $elargmanualinicio = addslashes($elargmanualinicio);
              $elargmanualinicio = test_input($elargmanualinicio);

              for ($i = 0; $i < count($checkarg); $i++) {

                $cogercheck = substr_count(strtolower($elargmanualinicio), strtolower($checkarg[$i]));

                if ($cogercheck >= 1) {
                  $retorno = "elargmanuininovalid";
                  $elerror = 1;
                }
              }
            } else {
              $elargmanualinicio = CONFIGARGMANUALINI;
            }

            if ($elerror == 0) {
              $cogercheck = 0;
              //ARGUMENTO FINAL
              if (isset($_POST["argmanualfinal"])) {
                $elargmanualfinal = $_POST["argmanualfinal"];
                $elargmanualfinal = addslashes($elargmanualfinal);
                $elargmanualfinal = test_input($elargmanualfinal);

                for ($i = 0; $i < count($checkarg); $i++) {

                  $cogercheck = substr_count(strtolower($elargmanualfinal), strtolower($checkarg[$i]));

                  if ($cogercheck >= 1) {
                    $retorno = "elargmanufinalnovalid";
                    $elerror = 1;
                  }
                }
              } else {
                $elargmanualfinal = CONFIGARGMANUALFINAL;
              }
            }
          } else {
            $elargmanualinicio = CONFIGARGMANUALINI;
            $elargmanualfinal = CONFIGARGMANUALFINAL;
          }
        }

        //MODO MANTENIMIENTO
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1) {

            if (isset($_POST['modomantenimiento'])) {
              $elmantenimiento = test_input($_POST["modomantenimiento"]);
              $opcmantenimiento = array('Desactivado', 'Activado');

              if (!in_array($elmantenimiento, $opcmantenimiento)) {
                $elmantenimiento = CONFIGMANTENIMIENTO;
              }
            } else {
              $elmantenimiento = CONFIGMANTENIMIENTO;
            }
          } else {
            $elmantenimiento = CONFIGMANTENIMIENTO;
          }
        }

        //PARAMETROS AVANZADOS
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfavanzados', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfavanzados'] == 1) {

            //RECOLECTOR DE BASURA
            if (isset($_POST['recbasura'])) {
              $elgarbagecolector = test_input($_POST["recbasura"]);
            } else {
              $elgarbagecolector = CONFIGOPTIONGARBAGE;
            }

            //OPCIÓN FORCE UPGRADE
            if (isset($_POST['opforceupgrade'])) {
              $elforseupgrade = test_input($_POST["opforceupgrade"]);

              //MIRA SI NO ES NUMERICO
              if (!is_numeric($elforseupgrade)) {
                $elforseupgrade = CONFIGOPTIONFORCEUPGRADE;
              } else {
                //CONVIERTES A INT
                $elforseupgrade = (int) $elforseupgrade;

                //COMPRUEBAS SI NO ES 1
                if ($elforseupgrade != 1) {
                  $elforseupgrade = 0;
                }
              }
            } else {
              $elforseupgrade = 0;
            }

            //OPCIÓN ERASE CACHE
            if (isset($_POST['operasecache'])) {
              $elerasecache = test_input($_POST["operasecache"]);

              //MIRA SI NO ES NUMERICO
              if (!is_numeric($elerasecache)) {
                $elerasecache = CONFIGOPTIONERASECACHE;
              } else {
                //CONVIERTES A INT
                $elerasecache = (int) $elerasecache;

                //COMPRUEBAS SI NO ES 1
                if ($elerasecache != 1) {
                  $elerasecache = 0;
                }
              }
            } else {
              $elerasecache = 0;
            }

            //OPCIÓN CREATE REGION FILES
            if (isset($_POST['oprecreateregionfiles'])) {
              $elrecreateregionfiles = test_input($_POST["oprecreateregionfiles"]);

              //MIRA SI NO ES NUMERICO
              if (!is_numeric($elrecreateregionfiles)) {

                if (!defined('CONFIGOPTIONRECREATEREGIONFILES')) {
                  $elrecreateregionfiles = 0;
                } else {
                  $elrecreateregionfiles = CONFIGOPTIONRECREATEREGIONFILES;
                }
              } else {
                //CONVIERTES A INT
                $elrecreateregionfiles = (int) $elrecreateregionfiles;

                //COMPRUEBAS SI NO ES 1
                if ($elrecreateregionfiles != 1) {
                  $elrecreateregionfiles = 0;
                }
              }
            } else {
              $elrecreateregionfiles = 0;
            }

            //OPCIÓN RENDER DEBUG LABELS
            if (isset($_POST['oprenderdebuglabels'])) {
              $elrenderdebuglabels = test_input($_POST["oprenderdebuglabels"]);

              //MIRA SI NO ES NUMERICO
              if (!is_numeric($elrenderdebuglabels)) {

                if (!defined('CONFIGOPTIONRENDERDEBUGLABELS')) {
                  $elrenderdebuglabels = 0;
                } else {
                  $elrenderdebuglabels = CONFIGOPTIONRENDERDEBUGLABELS;
                }
              } else {
                //CONVIERTES A INT
                $elrenderdebuglabels = (int) $elrenderdebuglabels;

                //COMPRUEBAS SI NO ES 1
                if ($elrenderdebuglabels != 1) {
                  $elrenderdebuglabels = 0;
                }
              }
            } else {
              $elrenderdebuglabels = 0;
            }
          } else {
            //SI ES UN USUARIO SIN PERMISO
            $elgarbagecolector = CONFIGOPTIONGARBAGE;
            $elforseupgrade = CONFIGOPTIONFORCEUPGRADE;
            $elerasecache = CONFIGOPTIONERASECACHE;

            if (!defined('CONFIGOPTIONRECREATEREGIONFILES')) {
              $elrecreateregionfiles = 0;
            } else {
              $elrecreateregionfiles = CONFIGOPTIONRECREATEREGIONFILES;
            }

            if (!defined('CONFIGOPTIONRENDERDEBUGLABELS')) {
              $elrenderdebuglabels = 0;
            } else {
              $elrenderdebuglabels = CONFIGOPTIONRENDERDEBUGLABELS;
            }
          }
        }

        //SELECTOR JAVA
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfjavaselect', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfjavaselect'] == 1) {
            $eljavaname = "0";
            $eljavamanual = "";
            $eljavaselect = "";

            if (isset($_POST['configjavaselect'])) {
              $eljavaselect = test_input($_POST["configjavaselect"]);
            }

            if ($eljavaselect == "0") {
              //PRIMERA OPCION
              $eljavaname = "0";
              $eljavamanual = "";
            } elseif ($eljavaselect == "1") {
              //SEGUNDA OPCION
              if (isset($_POST['selectedjavaver'])) {
                $eljavaname = test_input($_POST["selectedjavaver"]);

                //OBTENER DIRECTORIOS JAVA
                $existjavaruta = shell_exec("update-java-alternatives -l | gawk '{ print $3 }'");
                if ($existjavaruta != "") {
                  $existjavaruta = trim($existjavaruta);
                  $existjavaruta = (explode("\n", $existjavaruta));
                  $sijavaexist = 0;

                  //COMPROBAR SI EXISTA LA RUTA INTRODUCIDA
                  for ($i = 0; $i < count($existjavaruta); $i++) {
                    if ($existjavaruta[$i] == $eljavaname) {
                      $sijavaexist = 1;
                    }
                  }

                  //SI EXISTE COMPROBAR SI ESTA JAVA DENTRO
                  if ($sijavaexist == 1) {
                    $eljavaname .= "/bin/java";
                    clearstatcache();
                    if (!file_exists($eljavaname)) {
                      $retorno = "nojavaenruta";
                      $elerror = 1;
                    }
                  } else {
                    $retorno = "nojavaencontrado";
                    $elerror = 1;
                  }
                } else {
                  $retorno = "nojavaoutput";
                  $elerror = 1;
                }
              }
            } elseif ($eljavaselect == "2") {
              //TERCERA OPCION
              if ($_SESSION['CONFIGUSER']['rango'] == 1) {
                if (isset($_POST['javamanual'])) {
                  $eljavamanual = test_input($_POST["javamanual"]);
                  $existjavaruta = trim($eljavamanual);
                  $existjavaruta .= "/bin/java";
                  $test = 0;

                  //COMPOBAR SI HAY ".." "..."
                  if ($elerror == 0) {

                    $verificar = array('..', '...', '/.', '~', '../', './', ';', ':', '>', '<', '\\', '&&', '#', "|", '$', '%', '!', '`', '&', '*', '{', '}', '?', '=', '@', "'", '"', "'\'");

                    for ($i = 0; $i < count($verificar); $i++) {

                      $test = substr_count($existjavaruta, $verificar[$i]);

                      if ($test >= 1) {
                        $retorno = "novalido";
                        $elerror = 1;
                      }
                    }
                  }

                  //COMPROBAR QUE NO ESTE DENTRO DE LA CARPETA RAIZ
                  if ($elerror == 0) {
                    $rutacheck = trim(dirname(getcwd()));
                    $rutajavacheck = substr($existjavaruta, 0, strlen($rutacheck));

                    if ($rutajavacheck == $rutacheck) {
                      $elerror = 1;
                      $retorno = "inpanel";
                    }
                  }

                  //COMPROBAR SI ESTA JAVA EN LA RUTA
                  clearstatcache();
                  if ($elerror == 0) {
                    if (!file_exists($existjavaruta)) {
                      $retorno = "nojavaenruta";
                      $elerror = 1;
                    }
                  }
                }
              } else {
                $eljavaselect = "0";
                $eljavaname = "0";
                $eljavamanual = "";
              }
            } else {
              $eljavaselect = "2";
              $eljavaname = "0";
              $eljavamanual = CONFIGJAVAMANUAL;
            }
          } else {
            //SI NO TIENE PERMISOS SE ASIGNA LOS QUE YA TIENE
            $eljavaselect = CONFIGJAVASELECT;
            $eljavaname = CONFIGJAVANAME;
            $eljavamanual = CONFIGJAVAMANUAL;
          }
        }

        //LIMITE ALMACENAMIENTO
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconffoldersize', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconffoldersize'] == 1) {
            if (isset($_POST["limitbackupgb"])) {
              //OBTENER INPUT LIMITE BACKUPS GIGAS
              $ellimitebackupgb = test_input($_POST["limitbackupgb"]);

              //MIRAR SI ES NUMERICO
              if (is_numeric($ellimitebackupgb)) {
                $ellimitebackupgb = intval($ellimitebackupgb);
                //MIRAR SI SUPERA EL LIMITE PERMITIDO
                if ($ellimitebackupgb > 100) {
                  $elerror = 1;
                  $retorno = "datolimitebacksuperior";
                }
              } else {
                $elerror = 1;
                $retorno = "valornonumerico";
              }
            } else {
              $ellimitebackupgb = intval(CONFIGFOLDERBACKUPSIZE);
            }

            if (isset($_POST["limitminecraftgb"])) {
              //OBTENER INPUT LIMITE MINECRAF GIGAS
              $ellimiteminecraftgb = test_input($_POST["limitminecraftgb"]);

              //MIRAR SI ES NUMERICO
              if (is_numeric($ellimiteminecraftgb)) {
                $ellimiteminecraftgb = intval($ellimiteminecraftgb);
                //MIRAR SI SUPERA EL LIMITE PERMITIDO
                if ($ellimiteminecraftgb > 100) {
                  $elerror = 1;
                  $retorno = "datolimiteminesuperior";
                }
              } else {
                $elerror = 1;
                $retorno = "valornonumerico";
              }
            } else {
              $ellimiteminecraftgb = intval(CONFIGFOLDERMINECRAFTSIZE);
            }
          } else {
            $ellimitebackupgb = intval(CONFIGFOLDERBACKUPSIZE);
            $ellimiteminecraftgb = intval(CONFIGFOLDERMINECRAFTSIZE);
          }
        }

        //CONFIG BACKUPS TIPO BACKUP
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbackup', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbackup'] == 1) {


            if (isset($_POST["backupmulti"])) {
              $elbackupmulti = test_input($_POST["backupmulti"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($elbackupmulti)) {
                  $retorno = "backupmultinonumerico";
                  $elerror = 1;
                }
              }

              //RANGO
              if ($elerror == 0) {
                if ($elbackupmulti < 1 || $elbackupmulti > 2) {
                  $retorno = "backupmultioutrango";
                  $elerror = 1;
                }
              }

              //COMPRUEBA SI PIGZ ESTA INSTALADO
              if ($elerror == 0) {
                if ($elbackupmulti == 2) {
                  $comreq = shell_exec('command -v pigz >/dev/null && echo "yes" || echo "no"');
                  $comreq = trim($comreq);
                  if ($comreq == "no") {
                    $retorno = "backupmultinopigz";
                    $elerror = 1;
                  }
                }
              }
            } else {
              $retorno = "backupmultivacio";
              $elerror = 1;
            }
          } else {
            $elbackupmulti = CONFIGBACKUPMULTI;
          }
        }

        //CONFIG BACKUPS COMPRESION
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbackup', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbackup'] == 1) {

            if (isset($_POST["backupcompress"])) {
              $elbackupcompress = test_input($_POST["backupcompress"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($elbackupcompress)) {
                  $retorno = "backupcompressnonumerico";
                  $elerror = 1;
                }
              }

              //RANGO
              if ($elerror == 0) {
                if ($elbackupcompress < 0 || $elbackupcompress > 9) {
                  $retorno = "backupcompressoutrango";
                  $elerror = 1;
                }
              }
            } else {
              $retorno = "backupcompressvacio";
              $elerror = 1;
            }
          } else {
            $elbackupcompress = CONFIGBACKUPHILOS;
          }
        }

        //CONFIG BACKUPS HILOS
        if ($elerror == 0) {
          if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbackup', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbackup'] == 1) {

            if (isset($_POST["backuphilos"])) {
              $elbackuphilos = test_input($_POST["backuphilos"]);

              //ES NUMERICO
              if ($elerror == 0) {
                if (!is_numeric($elbackuphilos)) {
                  $retorno = "backuphilosnonumerico";
                  $elerror = 1;
                }
              }

              //RANGO INFERIOR A 1
              if ($elerror == 0) {
                if ($elbackuphilos <= 0) {
                  $retorno = "backuphilosoutrango";
                  $elerror = 1;
                }
              }

              //COMPROBAR SI EL TOTAL DE HILOS ES SUPERIOR AL DEL SERVIDOR
              if ($elerror == 0) {
                $getallcores = shell_exec('grep -c processor /proc/cpuinfo');
                if ($getallcores != "") {
                  $getallcores = trim($getallcores);

                  if (is_numeric($getallcores)) {
                    if ($elbackuphilos > $getallcores) {
                      $retorno = "backuphilosexceddcores";
                      $elerror = 1;
                    }
                  } else {
                    //SI NO DEVUELVE LOS HILOS, SE COLOCA 1 HILO MANUALMENTE
                    $elbackuphilos = 1;
                  }
                } else {
                  $elbackuphilos = 1;
                }
              }
            } else {
              $retorno = "backuphilosvacio";
              $elerror = 1;
            }
          } else {
            $elbackuphilos = CONFIGBACKUPCOMPRESS;
          }
        }

        //CONFIG ROTACION BACKUPS
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbackup', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbackup'] == 1) {
          if (isset($_POST["backuprotate"])) {

            if (!defined('CONFIGBACKUROTATE')) {
              $recbackuprotate = 0;
            } else {
              $recbackuprotate = intval(CONFIGBACKUROTATE);
            }

            $elbackuprotate = test_input($_POST["backuprotate"]);

            //ES NUMERICO
            if ($elerror == 0) {
              if (is_numeric($elbackuprotate)) {
                $elbackuprotate = intval($elbackuprotate);
              } else {
                $retorno = "backuprotatenonumerico";
                $elerror = 1;
              }
            }

            //RANGO INFERIOR A 0 Y SUPERIOR A 1000
            if ($elerror == 0) {
              if ($elbackuprotate < 0 || $elbackuprotate > 1000) {
                $retorno = "backuprotatesoutrango";
                $elerror = 1;
              }
            }


            if ($elerror == 0) {

              //DECLARAR VARIABLES ROTATE
              $rotateindice = 0;
              $elauxiliar = 0;
              $arraylimpieza = array();

              //OBTENER RUTA ARCHIVO ROTACION
              $rutarotate = trim($dirraiz . "/config" . "/backuprotate.json" . PHP_EOL);

              //SI EL ROTATE LO PONES A 0 SE BORRA LA LISTA DE ROTATE
              if ($elbackuprotate == 0) {
                clearstatcache();
                if (is_writable($rutarotate)) {
                  //BORRAR LISTA
                  unlink($rutarotate);
                }
              } else {

                //SI EL NUMERO NUEVO DE ROTACIONES ES MENOR AL GUARDADO
                if ($elbackuprotate < $recbackuprotate) {
                  clearstatcache();
                  if (is_writable($rutarotate)) {

                    //LEER ARCHIVO
                    $getarrayrotate = file_get_contents($rutarotate);
                    $elarrayrotate = unserialize($getarrayrotate);
                    $rotateindice = count($elarrayrotate);

                    //ORDENAR POR FECHA MAS NUEVAS PRIMERO
                    $ordenarArray = array();

                    foreach ($elarrayrotate as $rotaciones) {
                      foreach ($rotaciones as $key => $value) {
                        if (!isset($ordenarArray[$key])) {
                          $ordenarArray[$key] = array();
                        }
                        $ordenarArray[$key][] = $value;
                      }
                    }

                    $ordenarpor = "fecha";

                    array_multisort($ordenarArray[$ordenarpor], SORT_DESC, $elarrayrotate);

                    if ($rotateindice > $elbackuprotate) {

                      //LIMPIAR LOS REGISTROS CON FECHA MAS ANTIGUA
                      for ($elbucle = 0; $elbucle < $elbackuprotate; $elbucle++) {
                        $arraylimpieza[$elauxiliar]['archivo'] = $elarrayrotate[$elbucle]['archivo'];
                        $arraylimpieza[$elauxiliar]['fecha'] = $elarrayrotate[$elbucle]['fecha'];
                        $elauxiliar = $elauxiliar + 1;
                      }

                      //ORDENAR POR FECHA ANTIGUAS PRIMERO
                      $ordenarArray = array();

                      foreach ($arraylimpieza as $rotaciones2) {
                        foreach ($rotaciones2 as $key => $value) {
                          if (!isset($ordenarArray[$key])) {
                            $ordenarArray[$key] = array();
                          }
                          $ordenarArray[$key][] = $value;
                        }
                      }

                      $ordenarpor = "fecha";

                      array_multisort($ordenarArray[$ordenarpor], SORT_ASC, $arraylimpieza);

                      //GUARDAR LISTADO ROTATE
                      $serializedlimpia = serialize($arraylimpieza);
                      file_put_contents($rutarotate, $serializedlimpia);
                    }
                  }
                }
              }
            }
          } else {
            $retorno = "backuprotatevacio";
            $elerror = 1;
          }
        } else {
          if (!defined('CONFIGBACKUROTATE')) {
            $elbackuprotate = 0;
          } else {
            $elbackuprotate = CONFIGBACKUROTATE;
          }
        }

        //CONFIG ZONA HORARIA
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfzonahoraria', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfzonahoraria'] == 1) {
          if (isset($_POST["zona_horaria"])) {
            $elzonahoraria = test_input($_POST["zona_horaria"]);

            //COMPROBAR SI LA ZONA HORARIA INTRODUCIDA EXISTE
            $listado_zonas_horarias = timezone_identifiers_list();
            $zonaencontrada = 0;

            foreach ($listado_zonas_horarias as $lazona) {
              if ($elzonahoraria == $lazona) {
                $zonaencontrada = 1;
              }
            }

            if ($zonaencontrada == 0) {
              $retorno = "zonanoenlista";
              $elerror = 1;
            }
          } else {
            if (!defined('CONFIGZONAHORARIA')) {
              $elzonahoraria = "UTC";
            } else {
              $elzonahoraria = CONFIGZONAHORARIA;
            }
          }
        } else {
          //SI ES UN USUARIO SIN PERMISO
          if (!defined('CONFIGZONAHORARIA')) {
            $elzonahoraria = "UTC";
          } else {
            $elzonahoraria = CONFIGZONAHORARIA;
          }
        }

        //OPCIONES QUE NO SE CAMBIAN DESDE GUARDARSYSCONF
        $lakey = CONFIGSESSIONKEY;
        $eldirectorio = CONFIGDIRECTORIO;
        $elpostmax = "";
        $eleulaminecraft = CONFIGEULAMINECRAFT;

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA CONFIG
        $dirconfig = "";
        $dirconfig = dirname(getcwd()) . PHP_EOL;
        $dirconfig = trim($dirconfig);
        $dirconfig .= "/config";

        //OBTENER RUTA RAIZ
        $rutaraiz = dirname(getcwd()) . PHP_EOL;
        $rutaraiz = trim($rutaraiz);

        //COMPROBAR SI EXISTE CARPETA CONF
        if ($elerror == 0) {
          clearstatcache();
          if (!file_exists($dirconfig)) {
            $retorno = "nocarpetaconf";
            $elerror = 1;
          }
        }

        //COMPROBAR SI SE PUEDE ESCRIBIR CARPETA CONF
        if ($elerror == 0) {
          clearstatcache();
          if (!is_writable($dirconfig)) {
            $retorno = "nowriteconf";
            $elerror = 1;
          }
        }

        //COMPROBAR SI SE PUEDE ESCRIBIR ARCHIVO .htaccess de la raiz
        if ($elerror == 0) {
          $rutaescribir = $rutaraiz;
          $rutaescribir .= "/.htaccess";

          clearstatcache();
          if (file_exists($rutaescribir)) {
            clearstatcache();
            if (!is_writable($rutaescribir)) {
              $retorno = "nowritehtaccess";
              $elerror = 1;
            }
          }
        }

        if ($elerror == 0) {
          //CREAR RUTA FICHERO .htaccess en config
          $rutaescribir = $dirconfig;
          $rutaescribir .= "/.htaccess";

          //GUARDAR FICHERO .htaccess en config
          $file = fopen($rutaescribir, "w");
          fwrite($file, "deny from all" . PHP_EOL);
          fclose($file);

          //CREAR RUTA FICHERO CONFOPCIONES.PHP
          $rutaescribir = $dirconfig;
          $rutaescribir .= "/confopciones.php";

          //GUARDAR FICHERO CONFOPCIONES.PHP
          $file = fopen($rutaescribir, "w");
          fwrite($file, "<?php " . PHP_EOL);
          fwrite($file, 'define("CONFIGSESSIONKEY", "' . $lakey . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGNOMBRESERVER", "' . $elnombreservidor . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGDIRECTORIO", "' . $eldirectorio . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGPUERTO", "' . $elpuerto . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGRAM", "' . $laram . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGTIPOSERVER", "' . $eltiposerver . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGARCHIVOJAR", "' . $ellistadojars . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGEULAMINECRAFT", "' . $eleulaminecraft . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGMAXUPLOAD", "' . $eluploadmax . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGOPTIONGARBAGE", "' . $elgarbagecolector . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGOPTIONFORCEUPGRADE", "' . $elforseupgrade . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGOPTIONERASECACHE", "' . $elerasecache . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGJAVASELECT", "' . $eljavaselect . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGJAVANAME", "' . $eljavaname . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGJAVAMANUAL", "' . $eljavamanual . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGFOLDERBACKUPSIZE", "' . $ellimitebackupgb . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGFOLDERMINECRAFTSIZE", "' . $ellimiteminecraftgb . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGLINEASCONSOLA", "' . $elnumerolineaconsola . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGSHOWSIZEFOLDERS", "' . $elmostrarsizecarpeta . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGBOOTSYSTEM", "' . $elbootconfig . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGIGNORERAMLIMIT", "' . $elignorarlimitram . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGMANTENIMIENTO", "' . $elmantenimiento . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGBUFFERLIMIT", "' . $elbufferlimit . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGARGMANUALINI", "' . $elargmanualinicio . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGARGMANUALFINAL", "' . $elargmanualfinal . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGCONSOLETYPE", "' . $eltypeconsola . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGXMSRAM", "' . $laramxms . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGBACKUPMULTI", "' . $elbackupmulti . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGBACKUPCOMPRESS", "' . $elbackupcompress . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGBACKUPHILOS", "' . $elbackuphilos . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGBACKUROTATE", "' . $elbackuprotate . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGZONAHORARIA", "' . $elzonahoraria . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGOPTIONRECREATEREGIONFILES", "' . $elrecreateregionfiles . '");' . PHP_EOL);
          fwrite($file, 'define("CONFIGOPTIONRENDERDEBUGLABELS", "' . $elrenderdebuglabels . '");' . PHP_EOL);
          fwrite($file, "?>" . PHP_EOL);
          fclose($file);

          $rutaescribir = $rutaraiz;
          $rutaescribir .= "/.htaccess";

          $elpostmax = $eluploadmax + 1;

          $linea1 = "php_value upload_max_filesize " . $eluploadmax . "M";
          $linea2 = "php_value post_max_size " . $elpostmax . "M";
          $linea3 = "php_value max_file_uploads 1";

          //GUARDAR FICHERO .HTACCESS EN RAIZ
          $file = fopen($rutaescribir, "w");
          fwrite($file, "<IfModule mod_php7.c>" . PHP_EOL);
          fwrite($file, $linea1 . PHP_EOL);
          fwrite($file, $linea2 . PHP_EOL);
          fwrite($file, $linea3 . PHP_EOL);
          fwrite($file, "php_value max_execution_time 600" . PHP_EOL);
          fwrite($file, "php_value max_input_time 600" . PHP_EOL);
          fwrite($file, "</IfModule>" . PHP_EOL);
          fwrite($file, "<IfModule mod_php.c>" . PHP_EOL);
          fwrite($file, $linea1 . PHP_EOL);
          fwrite($file, $linea2 . PHP_EOL);
          fwrite($file, $linea3 . PHP_EOL);
          fwrite($file, "php_value max_execution_time 600" . PHP_EOL);
          fwrite($file, "php_value max_input_time 600" . PHP_EOL);
          fwrite($file, "</IfModule>" . PHP_EOL);
          fclose($file);
          sleep(2);
          $retorno = "saveconf";
        }
        echo $retorno;
      }
    }
  }
}
