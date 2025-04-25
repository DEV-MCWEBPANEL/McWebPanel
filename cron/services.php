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

$retorno = "";
$elerror = 0;
$recbootconf = "";
$getsistemd = "";

$RUTAPRINCIPAL = $_SERVER['PHP_SELF'];
$RUTAPRINCIPAL = substr($RUTAPRINCIPAL, 0, -18);
$RUTACONFIG = $RUTAPRINCIPAL . "/config/confopciones.php";

//OBTENER RUTA CONFIG
$rutaarchivo = $RUTAPRINCIPAL;
$rutaarchivo = trim($rutaarchivo);
$rutaarchivo .= "/config";

//COMPROBAR SI EXISTE CARPETA CONFIG
if ($elerror == 0) {
  clearstatcache();
  if (!file_exists($rutaarchivo)) {
    $elerror = 1;
  }
}

//COMPROBAR SI CONFIG TIENE PERMISOS DE LECTURA
if ($elerror == 0) {
  clearstatcache();
  if (!is_readable($rutaarchivo)) {
    $retorno = "Error la carpeta config no tiene permisos de lectura.";
    $elerror = 1;
  }
}

//COMPROBAR SI CONFIG TIENE PERMISOS DE ESCRITURA
if ($elerror == 0) {
  clearstatcache();
  if (!is_writable($rutaarchivo)) {
    $retorno = "Error la carpeta config no tiene permisos de escritura.";
    $elerror = 1;
  }
}

//COMPROBAR SI EXISTE CARPETA CONFIG
if ($elerror == 0) {
  clearstatcache();
  if (!file_exists($rutaarchivo)) {
    $elerror = 1;
  }
}


//COMPROBAR SI EXISTE ARCHIVO CONFIGURACION
if ($elerror == 0) {
  clearstatcache();
  if (!file_exists($RUTACONFIG)) {
    $elerror = 1;
  }
}

//COMPROBAR SI ARCHIVO CONFIGURACION TIENE PERMISOS DE LECTURA
if ($elerror == 0) {
  clearstatcache();
  if (!is_readable($RUTACONFIG)) {
    $elerror = 1;
  }
}

//CARGAR ARCHIVO CONFIGURACION
if ($elerror == 0) {
  require_once $RUTACONFIG;
  if (defined('CONFIGBOOTSYSTEM')) {
    $recbootconf = CONFIGBOOTSYSTEM;
  } else {
    $elerror = 1;
  }
}

if ($elerror == 0) {
  if ($recbootconf == "SI") {

    if (!empty($argv[1])) {
      $getsistemd = $argv[1];

      switch ($getsistemd) {
        case "start":
          //INICIAR SERVIDOR

          //OBTENER PID SABER SI ESTA EN EJECUCION
          $elcomando = "";
          $elnombrescreen = CONFIGDIRECTORIO;
          $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
          $elpid = shell_exec($elcomando);

          if ($elpid == "") {

            function guardareinicio($rutaelsh, $elcom, $rutaarchivlog)
            {
              $rutaelsh .= "/start.sh";

              clearstatcache();
              if (file_exists($rutaelsh)) {
                clearstatcache();
                if (is_writable($rutaelsh)) {
                  $file = fopen($rutaelsh, "w");
                  fwrite($file, "#!/bin/sh" . PHP_EOL);
                  fwrite($file, "rm " . $rutaarchivlog . PHP_EOL);
                  fwrite($file, $elcom . PHP_EOL);
                  fclose($file);
                }
              } else {
                $file = fopen($rutaelsh, "w");
                fwrite($file, "#!/bin/sh" . PHP_EOL);
                fwrite($file, $elcom . PHP_EOL);
                fclose($file);
              }
            }

            $retorno = "";
            $elerror = 0;

            $reccarpmine = CONFIGDIRECTORIO;
            $recarchivojar = CONFIGARCHIVOJAR;
            $recram = CONFIGRAM;
            $rectiposerv = CONFIGTIPOSERVER;
            $receulaminecraft = CONFIGEULAMINECRAFT;
            $recpuerto = CONFIGPUERTO;

            $recgarbagecolector = CONFIGOPTIONGARBAGE;
            $recforseupgrade = CONFIGOPTIONFORCEUPGRADE;
            $recerasecache = CONFIGOPTIONERASECACHE;

            $recjavaselect = CONFIGJAVASELECT;
            $recjavaname = CONFIGJAVANAME;
            $recjavamanual = CONFIGJAVAMANUAL;

            $recignoreramlimit = CONFIGIGNORERAMLIMIT;

            $recargmanualinicio = CONFIGARGMANUALINI;
            $recargmanualfinal = CONFIGARGMANUALFINAL;

            if (!defined('CONFIGXMSRAM')) {
              $recxmsram = 1024;
            } else {
              $recxmsram = CONFIGXMSRAM;
            }

            if (!defined('CONFIGOPTIONRECREATEREGIONFILES')) {
              $recrecreateregionfiles = 0;
            } else {
              $recrecreateregionfiles = CONFIGOPTIONRECREATEREGIONFILES;
            }

            if (!defined('CONFIGOPTIONRENDERDEBUGLABELS')) {
              $recrenderdebuglabels = 0;
            } else {
              $recrenderdebuglabels = CONFIGOPTIONRENDERDEBUGLABELS;
            }

            $javaruta = "";
            $rutacarpetamine = "";

            //VARIABLE RUTA SERVIDOR MINECRAFT
            $rutacarpetamine = $RUTAPRINCIPAL;
            $rutacarpetamine = trim($rutacarpetamine);
            $rutacarpetamine .= "/" . $reccarpmine;

            $rutaminecraffijo = $rutacarpetamine;

            //VARIABLE RUTA SERVER.PROPERTIES
            $rutaconfigproperties = $rutaminecraffijo;
            $rutaconfigproperties .= "/server.properties";

            //VERIFICAR CARPETA MINECRAFT
            if ($elerror == 0) {
              clearstatcache();
              if (!file_exists($rutacarpetamine)) {
                $elerror = 1;
                $retorno = "Error Tarea Iniciar Servidor, no existe la carpeta Minecraft.";
              }
            }

            //VERIFICAR SI HAY PERMISOS DE LECTURA EN EL SERVIDOR MINECRAFT
            if ($elerror == 0) {
              clearstatcache();
              if (!is_readable($rutacarpetamine)) {
                $elerror = 1;
                $retorno = "Error Tarea Iniciar Servidor, no hay permisos de lectura en la carpeta Minecraft.";
              }
            }

            //VERIFICAR SI HAY ESCRITURA EN EL SERVIDOR MINECRAFT
            if ($elerror == 0) {
              clearstatcache();
              if (!is_writable($rutacarpetamine)) {
                $elerror = 1;
                $retorno = "Error Tarea Iniciar Servidor, no hay permisos de escritura en la carpeta Minecraft.";
              }
            }

            //VERIFICAR SI HAY PERMISOS DE EJECUCION EN EL SERVIDOR MINECRAFT
            if ($elerror == 0) {
              clearstatcache();
              if (!is_executable($rutacarpetamine)) {
                $elerror = 1;
                $retorno = "Error Tarea Iniciar Servidor, no hay permisos de ejecución en la carpeta Minecraft.";
              }
            }

            //VERIFICAR SI EXISTE LA CARPETA LIBRARIES
            if ($elerror == 0) {
              if ($rectiposerv == "forge old" || $rectiposerv == "forge new" || $rectiposerv == "NeoForge") {
                $libforge = $rutaminecraffijo . "/libraries";
                clearstatcache();
                if (!file_exists($libforge)) {
                  $retorno = "Error Tarea Iniciar Servidor, faltan las librerias necesarias para iniciar el servidor de Forge/NeoForge.";
                  $elerror = 1;
                }
              }
            }

            //VERIFICAR SI EXISTE CARPETA FORGE NEW
            if ($elerror == 0 && $rectiposerv == "forge new") {
              $forgescan = $rutaminecraffijo;
              $forgescan .= "/libraries/net/minecraftforge/forge/";

              clearstatcache();
              if (!file_exists($forgescan)) {
                $retorno = "Error Tarea Iniciar Servidor, No se encontro la carpeta /libraries/net/minecraftforge/forge/";
                $elerror = 1;
              }
            }

            //VERIFICAR SI EXISTE CARPETA NEOFORGE
            if ($elerror == 0 && $rectiposerv == "NeoForge") {
              $forgescan = $rutaminecraffijo;
              $forgescan .= "/libraries/net/neoforged/neoforge/";

              clearstatcache();
              if (!file_exists($forgescan)) {
                $retorno = "Error Tarea Iniciar Servidor, No se encontro la carpeta /libraries/net/neoforged/neoforge/";
                $elerror = 1;
              }
            }

            //VERIFICAR SI HAY MAS DE UN FORGE NEW/NEOFORGE PUESTO
            if ($elerror == 0 && $rectiposerv == "forge new" || $rectiposerv == "NeoForge") {
              $carpbuscar = scandir($forgescan);
              $contforge = count($carpbuscar);

              if ($contforge >= 4) {
                if ($rectiposerv == "forge new") {
                  $retorno = "Error Tarea Iniciar Servidor, Se ha encontrado más de una versión en /libraries/net/minecraftforge/forge/ revisa la carpeta y deja solamente la versión a utilizar.";
                } elseif ($rectiposerv == "NeoForge") {
                  $retorno = "Error Tarea Iniciar Servidor, Se ha encontrado más de una versión en /libraries/net/neoforged/neoforge/ revisa la carpeta y deja solamente la versión a utilizar.";
                }
                $elerror = 1;
              }
            }

            //VERIFICAR SI FORGE NEW O NEOFORGE EXISTE ARCHIVO CONFIG
            if ($elerror == 0 && $rectiposerv == "forge new" || $rectiposerv == "NeoForge") {

              if (isset($carpbuscar[2])) {
                $vercapforge = $carpbuscar[2];
                $forgeargsfile = $forgescan . $carpbuscar[2] . "/unix_args.txt";
                clearstatcache();
                if (!file_exists($forgeargsfile)) {
                  $retorno = "Error Tarea Iniciar Servidor, No se ha encontrado el archivo unix_args.txt con las librerías para poder iniciar forge/neoforge.";
                  $elerror = 1;
                }
              } else {
                $retorno = "Error Tarea Iniciar Servidor, No se ha podido obtener correctamente la versión de forge/neoforge.";
                $elerror = 1;
              }
            }

            //VERIFICAR EULA EN CONFIG
            if ($elerror == 0) {
              if ($receulaminecraft != "1") {
                $elerror = 1;
                $retorno = "Error Tarea Iniciar Servidor, no has aceptado el eula de Minecraft.";
              }
            }

            //CREAR EULA FORZADO
            if ($elerror == 0) {
              if ($receulaminecraft == "1") {
                $rutaescribir = $rutacarpetamine;
                $rutaescribir .= "/eula.txt";

                clearstatcache();
                if (file_exists($rutaescribir)) {
                  clearstatcache();
                  if (is_writable($rutaescribir)) {
                    $file = fopen($rutaescribir, "w");
                    fwrite($file, "eula=true" . PHP_EOL);
                    fclose($file);
                  } else {
                    $retorno = "Error Tarea Iniciar Servidor, no hay permisos de escritura en eula.txt";
                    $elerror = 1;
                  }
                } else {
                  $file = fopen($rutaescribir, "w");
                  fwrite($file, "eula=true" . PHP_EOL);
                  fclose($file);
                }

                //PERMISO EULA.TXT
                $elcommando = "cd " . $rutaminecraffijo . " && chmod 664 eula.txt";
                exec($elcommando);
              }
            }

            //VERIFICAR SI HAY NOMBRE.JAR
            if ($elerror == 0) {
              if ($recarchivojar == "") {
                if (!in_array($rectiposerv, ["forge new", "NeoForge"])) {
                  $elerror = 1;
                  $retorno = "Error Tarea Iniciar Servidor, no hay seleccionado un servidor .jar";
                }
              }
            }

            //VERIFICAR SI EXISTE REALMENTE
            if ($elerror == 0) {
              $rutajar = $rutacarpetamine . "/" . $recarchivojar;

              clearstatcache();
              if (!file_exists($rutajar)) {
                $elerror = 1;
                $retorno = "Error Tarea Iniciar Servidor, el .jar seleccionado no existe.";
              }
            }

            //COMPROBAR SI ES REALMENTE ARCHIVO JAVA
            if ($elerror == 0) {
              if (!in_array($rectiposerv, ["forge new", "NeoForge"])) {
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
                  $retorno = "Error Tarea Iniciar Servidor, el archivo no es válido.";
                  $elerror = 1;
                }
              }
            }

            //COMPROBAR PUERTO EN USO
            if ($elerror == 0) {
              $comandopuerto = "ss -tuln | grep :" . $recpuerto;
              $obtener = shell_exec($comandopuerto);
              if ($obtener != "") {
                $retorno = "Error Tarea Iniciar Servidor, puerto ya en uso.";
                $elerror = 1;
              }
            }

            //COMPROBAR IGNORAR LIMITE RAM
            if ($recignoreramlimit != 1) {
              //COMPROBAR MEMORIA RAM
              if ($elerror == 0) {
                $totalramsys = shell_exec("free -m | grep Mem | gawk '{ print $2 }'");
                $getramavaliable = shell_exec("free -m | grep Mem | gawk '{ print $7 }'");

                if ($totalramsys != "" && $getramavaliable != "") {
                  $totalramsys = trim($totalramsys);
                  $totalramsys = intval($totalramsys);

                  $getramavaliable = trim($getramavaliable);
                  $getramavaliable = intval($getramavaliable);

                  //COMPRUEBA SI AL MENOS SE TIENE 1GB
                  if ($totalramsys == 0) {
                    $elerror = 1;
                    $retorno = "Error Tarea Iniciar Servidor, Memoria Ram menor a 1 GB.";
                  } elseif ($totalramsys >= 1) {

                    //COMPRUEBA QUE LA RAM SELECCIONADA NO SEA MAYOR A LA DEL SISTEMA
                    if ($recram > $totalramsys) {
                      $elerror = 1;
                      $retorno = "Error Tarea Iniciar Servidor, la Ram seleccionada es superior a la del sistema.";
                    }

                    //COMPROBAR SI HAY MEMORIA SUFICIENTE PARA INICIAR CON RAM DISPONIBLE
                    if ($elerror == 0) {
                      if ($recram > $getramavaliable) {
                        $elerror = 1;
                        $retorno = "Error Tarea Iniciar Servidor, Memoria del sistema insuficiente para iniciar el servidor.";
                      }
                    }
                  }
                } else {
                  $elerror = 1;
                  $retorno = "Error Tarea Iniciar Servidor, No se pudo obtener la memoria del servidor.";
                }
              }
            }

            //COMPROBAR ESCRITURA SERVER.PROPERTIES
            if ($elerror == 0) {
              $rutatemp = $rutaminecraffijo;
              $rutafinal = $rutaminecraffijo;
              $rutatemp .= "/serverproperties.tmp";
              $rutafinal .= "/server.properties";
              $contador = 0;
              $secuprofile = 0;

              clearstatcache();
              if (file_exists($rutafinal)) {
                clearstatcache();
                if (!is_writable($rutafinal)) {
                  $elerror = 1;
                  $retorno = "Error Tarea Iniciar Servidor, no hay permisos de escritura en server.properties";
                }
              }
            }

            //AÑADIR PARAMETROS A SERVER.PROPERTIES
            if ($elerror == 0) {
              clearstatcache();
              if (file_exists($rutafinal)) {

                $gestor = @fopen($rutafinal, "r");
                $file = fopen($rutatemp, "w");

                while (($búfer = fgets($gestor, 4096)) !== false) {
                  $str = $búfer;
                  $array = explode("=", $str);

                  if ($array[0] == "server-port") {
                    fwrite($file, 'server-port=' . $recpuerto . PHP_EOL);
                    $contador = 1;
                  } else {
                    fwrite($file, $búfer);
                  }

                  if ($array[0] == "enforce-secure-profile") {
                    $secuprofile = 1;
                  }
                }

                if ($contador == 0) {
                  fwrite($file, "server-port=" . $recpuerto . PHP_EOL);
                }

                //AÑADIR enforce-secure-profile EN FALSE SI NO EXISTE
                if ($secuprofile == 0) {
                  fwrite($file, "enforce-secure-profile=false" . PHP_EOL);
                }

                fclose($gestor);
                fclose($file);
                rename($rutatemp, $rutafinal);

                //PERMISO SERVER.PROPERTIES
                $elcommando = "cd " . $rutaminecraffijo . " && chmod 664 server.properties";
                exec($elcommando);
              } else {
                //SI NO EXISTE POR CUALQUIER RAZON, SE GENERA UN ARCHIVO DE CONFIG MINIMA
                $file = fopen($rutafinal, "w");
                fwrite($file, "server-port=" . $recpuerto . PHP_EOL);
                fwrite($file, "enforce-secure-profile=false" . PHP_EOL);
                fclose($file);

                //PERMISO SERVER.PROPERTIES
                $elcommando = "cd " . $rutaminecraffijo . " && chmod 664 server.properties";
                exec($elcommando);
              }
            }

            //INSERTAR SERVER-ICON EN CASO QUE NO EXISTA
            if ($elerror == 0) {
              $rutacarpetamine = $RUTAPRINCIPAL;
              $rutacarpetamine = trim($rutacarpetamine);

              $rutaiconoimg = $rutacarpetamine . "/img/server-icon.png";
              $rutaiconofinal = $rutacarpetamine . "/" . $reccarpmine . "/server-icon.png";
              $rutacarpetamine .= "/" . $reccarpmine;

              //COMPROBAR SI EXISTE EN CARPETA IMG Y COPIARLA EN CASO QUE EL SERVIDOR NO LA TENGA
              clearstatcache();
              if (file_exists($rutaiconoimg)) {
                clearstatcache();
                if (!file_exists($rutaiconofinal)) {
                  copy($rutaiconoimg, $rutaiconofinal);
                }
              }
            }

            //PERMISO SERVER-ICON.PNG
            $elcommando = "cd " . $rutaminecraffijo . " && chmod 664 server-icon.png";
            exec($elcommando);

            //INICIAR VARIABLE JAVARUTA Y COMPROBAR SI EXISTE
            if ($elerror == 0) {
              if ($recjavaselect == "0") {
                $javaruta = "java";
                //COMPROBAR SI JAVA DEFAULT EXISTE
                $comreq = shell_exec('command -v java >/dev/null && echo "yes" || echo "no"');
                $comreq = trim($comreq);
                if ($comreq == "no") {
                  $retorno = "Error Tarea Iniciar Servidor, no se encontro Java.";
                  $elerror = 1;
                }
              } elseif ($recjavaselect == "1") {
                $javaruta = $recjavaname;
                clearstatcache();
                if (!file_exists($javaruta)) {
                  $retorno = "Error Tarea Iniciar Servidor, no se encontró Java en la ruta especificada.";
                  $elerror = 1;
                }
              } elseif ($recjavaselect == "2") {
                $javaruta = $recjavamanual . "/bin/java";
                clearstatcache();
                if (!file_exists($javaruta)) {
                  $retorno = "Error Tarea Iniciar Servidor, no se encontró Java en la ruta especificada.";
                  $elerror = 1;
                }
              } else {
                $retorno = "Error Tarea Iniciar Servidor, no se ha seleccionado ningún tipo de Java.";
                $elerror = 1;
              }
            }

            //CREAR CARPETA LOGS EN CASO QUE NO EXISTA
            if ($elerror == 0) {
              $rutacarplogs = $rutaminecraffijo . "/logs";
              clearstatcache();
              if (!file_exists($rutacarplogs)) {
                mkdir($rutacarplogs, 0700);
                $elcommando = "chmod 775 " . $rutacarplogs;
                exec($elcommando);
              }
            }

            //COMPROBAR SI EXISTE SCREEN.CONF
            if ($elerror == 0) {
              $rutascreenconf = $RUTAPRINCIPAL;
              $rutascreenconf = trim($rutascreenconf);
              $rutascreenconf .= "/config/screen.conf";

              clearstatcache();
              if (!file_exists($rutascreenconf)) {
                $retorno = "Error Tarea Iniciar Servidor, el archivo de configuración de Screen no existe.";
                $elerror = 1;
              }
            }

            //INICIAR SERVIDOR
            if ($elerror == 0) {
              $comandoserver = "";
              $cominiciostart = "";
              $larutash = "";
              $inigc = "";

              $rutacarpetamine = $RUTAPRINCIPAL;
              $rutacarpetamine = trim($rutacarpetamine);
              $larutash = $rutacarpetamine . "/" . $reccarpmine;
              $larutascrrenlog = $rutacarpetamine . "/" . $reccarpmine . "/logs/screen.log";
              $rutacarpetamine .= "/" . $reccarpmine . "/" . $recarchivojar;

              //BORRAR LOG SCREEN
              clearstatcache();
              if (file_exists($larutascrrenlog)) {
                unlink($larutascrrenlog);
              }

              //INICIO SCRIPT SH
              $comandoserver .= "cd " . $RUTAPRINCIPAL . " && cd " . $reccarpmine . " && umask 002 && screen -c '" . $rutascreenconf . "' -dmS " . $reccarpmine . " -L -Logfile 'logs/screen.log' " . $javaruta . " -Xms" . $recxmsram . "M -Xmx" . $recram . "M ";

              //RECOLECTOR
              if ($recgarbagecolector == "0") {
                $inigc = "";
              } elseif ($recgarbagecolector == "1") {
                $comandoserver .= "-XX:+UseConcMarkSweepGC" . " ";
                $inigc = "-XX:+UseConcMarkSweepGC";
              } elseif ($recgarbagecolector == "2") {
                $comandoserver .= "-XX:+UseG1GC" . " ";
                $inigc = "-XX:+UseG1GC";
              }

              //AÑADE FILE ENCODING
              $comandoserver .= "-Dfile.encoding=UTF8 ";

              //AÑADE PARAMETROS INICIO
              if ($recargmanualinicio != "") {
                $comandoserver .= $recargmanualinicio . " ";
              }

              if ($rectiposerv == "forge new") {
                $comandoserver .= '-Dusing.konata.flags=' . $reccarpmine . " " . '@libraries/net/minecraftforge/forge/' . $vercapforge . '/unix_args.txt "$@"' . " ";
              } elseif ($rectiposerv == "NeoForge") {
                $comandoserver .= '-Dusing.konata.flags=' . $reccarpmine . " " . '@libraries/net/neoforged/neoforge/' . $vercapforge . '/unix_args.txt "$@"' . " ";
              } else {
                $comandoserver .= "-jar '" . $rutacarpetamine . "' ";
              }

              //FORCEUPGRADE MAPA
              if ($recforseupgrade == 1) {
                $comandoserver .= "--forceUpgrade" . " ";
              }

              //ERASE CACHE MAPA
              if ($recerasecache == 1) {
                $comandoserver .= "--eraseCache" . " ";
              }

              //CREATE REGION FILES
              if ($recrecreateregionfiles == 1) {
                $comandoserver .= "--recreateRegionFiles" . " ";
              }

              //RENDER DEBUG LABELS
              if ($recrenderdebuglabels == 1) {
                $comandoserver .= "--renderDebugLabels" . " ";
              }

              //AÑADE NOGUI
              $comandoserver .= "nogui";

              //AÑADE PARAMETROS FINAL
              if ($recargmanualfinal != "") {
                $comandoserver .= " " . $recargmanualfinal;
              }

              //RESTART
              $cominiciostart = "screen -c '" . $rutascreenconf . "' -dmS " . $reccarpmine . " -L -Logfile 'logs/screen.log' " . $javaruta . " -Xms" . $recxmsram . "M -Xmx" . $recram . "M " . $inigc . " -Dfile.encoding=UTF8 " . $recargmanualinicio . " -jar '" . $rutacarpetamine . "' nogui " . $recargmanualfinal;
              if ($rectiposerv == "spigot" || $rectiposerv == "paper" || $rectiposerv == "purpur") {
                guardareinicio($larutash, $cominiciostart, $larutascrrenlog);
              }

              //CREAR SH
              $rutastartsh = $RUTAPRINCIPAL;
              $startsh = $rutastartsh . "/temp";
              $startsh .= "/" . $reccarpmine . ".sh";

              $file = fopen($startsh, "w");
              if (in_array($rectiposerv, ["forge new", "NeoForge"])) {
                fwrite($file, "#!/usr/bin/env sh" . PHP_EOL);
              } else {
                fwrite($file, "#!/bin/sh" . PHP_EOL);
              }
              fwrite($file, $comandoserver . PHP_EOL);
              fclose($file);

              $comandoperm = "chmod 744 " . $startsh;
              exec($comandoperm);
              exec("sh " . $startsh . " &");
              $retorno = "Tarea Iniciar Servidor, ejecutado correctamente.";
            }
          } else {
            $retorno = "Tarea Iniciar Servidor, no se puede realizar al estar ya en ejecución.";
          }

          break;
        case "stop":
          //APAGAR SERVIDOR

          //MATAR SCREEN DEAD / ZOMBIS ANTES DE APAGAR
          $elcomando = "";
          $elcomando = "screen -wipe";
          shell_exec($elcomando);

          //OBTENER PID SABER SI ESTA EN EJECUCION
          $elcomando = "";
          $elnombrescreen = CONFIGDIRECTORIO;
          $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
          $elpid = shell_exec($elcomando);

          //SI ESTA EN EJECUCION ENVIAR COMANDO APAGAR
          if (!$elpid == "") {
            $paraejecutar = "save-all";
            $laejecucion = 'screen -S ' . $elnombrescreen . ' -X stuff "' . $paraejecutar . '\\015"';
            exec($laejecucion);
            $paraejecutar = "stop";
            $laejecucion = 'screen -S ' . $elnombrescreen . ' -X stuff "' . $paraejecutar . '\\015"';
            exec($laejecucion);
            sleep(10);
            $retorno = "Tarea Apagar Servidor, ejecutado correctamente.";
          } else {
            $retorno = "Tarea Apagar Servidor, no se puede realizar al estar apagado.";
          }
          break;
      }
    }
  }
}
