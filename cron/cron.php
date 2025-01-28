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
header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; script-src 'self'; form-action 'self'; base-uri 'none'; connect-src 'self'; frame-ancestors 'none'");
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header('Permissions-Policy: geolocation=(), microphone=()');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$RUTAPRINCIPAL = $_SERVER['PHP_SELF'];
$RUTAPRINCIPAL = substr($RUTAPRINCIPAL, 0, -14);
$RUTACONFIG = $RUTAPRINCIPAL . "/config/confopciones.php";

if (!defined('CONFIGZONAHORARIA')) {
    $reczonahoraria = "UTC";
} else {
    $reczonahoraria = CONFIGZONAHORARIA;
}

date_default_timezone_set($reczonahoraria);

$retorno = "";
$logfechayhora = "[" . date("d/m/Y") . "] [" . date("H:i:s") . "]";

$elerror = 0;
$time = date("G:i");
$mesactual = date('n');
$semanactual = date('N');
$horactual = date('G');
$minutoactual = date('i');

//OBTENER RUTA CONFIG
$rutaarchivo = $RUTAPRINCIPAL;
$rutaarchivo = trim($rutaarchivo);
$rutaarchivo .= "/config";

$elarchivo = $rutaarchivo;
$elarchivo .= "/array.json";

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

if ($elerror == 0) {

    //CARGAR CONFIGURACION PANEL
    require_once $RUTACONFIG;

    clearstatcache();
    if (file_exists($elarchivo)) {

        //COMPROBAR SI SE PUEDE LEER EL JSON
        if ($elerror == 0) {
            clearstatcache();
            if (!is_readable($elarchivo)) {
                $retorno = "Error el archivo de tareas no tiene permisos de lectura.";
                $elerror = 1;
            }
        }

        //COMPROBAR SI SE PUEDE ESCRIBIR EL JSON
        if ($elerror == 0) {
            clearstatcache();
            if (!is_writable($elarchivo)) {
                $retorno = "Error el archivo de tareas no tiene permisos de escritura.";
                $elerror = 1;
            }
        }

        if ($elerror == 0) {
            //LEER FICHERO OBTENER ARRAY
            $getarray = file_get_contents($elarchivo);
            $arrayobtenido = unserialize($getarray);

            $elindice = count($arrayobtenido);

            for ($i = 0; $i < $elindice; $i++) {
                if ($arrayobtenido[$i]['estado'] == "activado") {
                    for ($a = 0; $a < 12; $a++) {
                        if ($arrayobtenido[$i][$a]['mes'] == $mesactual) {
                            for ($b = 0; $b < 7; $b++) {
                                if ($arrayobtenido[$i][$b]['semana'] == $semanactual) {
                                    for ($c = 0; $c < 24; $c++) {
                                        if ($arrayobtenido[$i][$c]['hora'] == $horactual) {
                                            for ($d = 0; $d < 60; $d++) {
                                                if ($arrayobtenido[$i][$d]['minuto'] == $minutoactual) {
                                                    //TAREA VALIDA PROCEDER A LA EJECUCION
                                                    $tarea = $arrayobtenido[$i]['accion'];

                                                    switch ($tarea) {
                                                        case "acc1":
                                                            //APAGAR SERVIDOR

                                                            //MATAR SCREEN DEAD / ZOMBIS ANTES DE APAGAR
                                                            $elcomando = "";
                                                            $elcomando = "screen -wipe";
                                                            exec($elcomando);

                                                            //OBTENER PID SABER SI ESTA EN EJECUCION
                                                            $elcomando = "";
                                                            $elnombrescreen = CONFIGDIRECTORIO;
                                                            $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
                                                            $elpid = shell_exec($elcomando);

                                                            //SI ESTA EN EJECUCION ENVIAR COMANDO APAGAR
                                                            if (!$elpid == "") {
                                                                $paraejecutar = "stop";
                                                                $laejecucion = 'screen -S ' . $elnombrescreen . ' -X stuff "' . $paraejecutar . '\\015"';
                                                                exec($laejecucion);
                                                                $retorno = "Tarea Apagar Servidor, ejecutado correctamente.";
                                                            } else {
                                                                $retorno = "Tarea Apagar Servidor, no se puede realizar al estar apagado.";
                                                            }

                                                            break;
                                                        case "acc2":
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

                                                                $javaruta = "";

                                                                $rutacarpetamine = "";

                                                                //VERIFICAR CARPETA MINECRAFT
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

                                                                if ($elerror == 0) {
                                                                    if ($rectiposerv == "forge") {
                                                                        $libforge = $rutacarpetamine . "/libraries";
                                                                        clearstatcache();
                                                                        if (!file_exists($libforge)) {
                                                                            $retorno = "Error Tarea Iniciar Servidor, faltan las librerias necesarias para iniciar el servidor de Forge.";
                                                                            $elerror = 1;
                                                                        }
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
                                                                            if (is_writable($rutaconfigproperties)) {
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
                                                                    }
                                                                }

                                                                //PERMISO EULA.TXT
                                                                $elcommando = "cd " . $rutaminecraffijo . " && chmod 664 eula.txt";
                                                                exec($elcommando);

                                                                //VERIFICAR SI HAY NOMBRE.JAR
                                                                if ($elerror == 0) {
                                                                    if ($recarchivojar == "") {
                                                                        $elerror = 1;
                                                                        $retorno = "Error Tarea Iniciar Servidor, no hay seleccionado un servidor .jar";
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

                                                                //COMPROBAR PUERTO EN USO
                                                                if ($elerror == 0) {
                                                                    $comandopuerto = "netstat -tulpn 2>/dev/null | grep :" . $recpuerto;
                                                                    $obtener = exec($comandopuerto);
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
                                                                        if ($totalramsys != "") {
                                                                            $totalramsys = trim($totalramsys);
                                                                            $totalramsys = intval($totalramsys);

                                                                            $getramavaliable = shell_exec("free -m | grep Mem | gawk '{ print $7 }'");
                                                                            if ($getramavaliable != "") {
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
                                                                                $retorno = "Error Tarea Iniciar Servidor, No se pudo obtener la memoria disponible del servidor.";
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

                                                                //COMPROBAR SERVER.PROPERTIES
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

                                                                    $comandoserver .= "cd " . $RUTAPRINCIPAL . " && cd " . $reccarpmine . " && umask 002 && screen -c '" . $rutascreenconf . "' -dmS " . $reccarpmine . " -L -Logfile 'logs/screen.log' " . $javaruta . " -Xms" . $recxmsram . "M -Xmx" . $recram . "M ";

                                                                    //RECOLECTOR
                                                                    if ($recgarbagecolector == "1") {
                                                                        $inigc = "-XX:+UseConcMarkSweepGC";
                                                                    } elseif ($recgarbagecolector == "2") {
                                                                        $inigc = "-XX:+UseG1GC";
                                                                    }

                                                                    if ($inigc != "") {
                                                                        $comandoserver .= $inigc . " ";
                                                                    }

                                                                    $comandoserver .= "-Dfile.encoding=UTF8 ";

                                                                    if ($recargmanualinicio != "") {
                                                                        $comandoserver .= $recargmanualinicio . " ";
                                                                    }

                                                                    $comandoserver .= "-jar '" . $rutacarpetamine . "' ";

                                                                    $comandoserver .= "nogui";

                                                                    if ($recargmanualfinal != "") {
                                                                        $comandoserver .= " " . $recargmanualfinal;
                                                                    }

                                                                    //RESTART
                                                                    $cominiciostart = "screen -c '" . $rutascreenconf . "' -dmS " . $reccarpmine . " -L -Logfile 'logs/screen.log' " . $javaruta . " -Xms" . $recxmsram . "M -Xmx" . $recram . "M " . $inigc . " -Dfile.encoding=UTF8 " . $recargmanualinicio . " -jar '" . $rutacarpetamine . "' nogui " . $recargmanualfinal;
                                                                    if ($rectiposerv == "spigot") {
                                                                        guardareinicio($larutash, $cominiciostart, $larutascrrenlog);
                                                                    } elseif ($rectiposerv == "paper") {
                                                                        guardareinicio($larutash, $cominiciostart, $larutascrrenlog);
                                                                    }

                                                                    //CREAR SH
                                                                    $rutastartsh = $RUTAPRINCIPAL;
                                                                    $startsh = $rutastartsh . "/temp";
                                                                    $startsh .= "/" . $reccarpmine . ".sh";

                                                                    $file = fopen($startsh, "w");
                                                                    fwrite($file, "#!/bin/sh" . PHP_EOL);
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
                                                        case "acc3":
                                                            //BACKUP SERVIDOR

                                                            function converdatoscarpbackup($losbytes, $opcion, $decimal)
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

                                                            function getfoldersize($dir)
                                                            {
                                                                $size = 0;

                                                                foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
                                                                    $size += is_file($each) ? filesize($each) : getfoldersize($each);
                                                                }

                                                                return $size;
                                                            }

                                                            $reccarpmine = CONFIGDIRECTORIO;
                                                            $elerror = 0;

                                                            $archivo = "AUTO";

                                                            $dirconfig = "";
                                                            $dirconfig = $RUTAPRINCIPAL;
                                                            $dirconfig = trim($dirconfig);
                                                            $dirconfig .= "/backups";

                                                            //ASIGNAR CONSTANTES SI NO EXISTEN
                                                            if (!defined('CONFIGBACKUPMULTI')) {
                                                                $recbackupmulti = 1;
                                                            } else {
                                                                $recbackupmulti = CONFIGBACKUPMULTI;
                                                            }

                                                            if (!defined('CONFIGBACKUPCOMPRESS')) {
                                                                $recbackupcompress = 1;
                                                            } else {
                                                                $recbackupcompress = CONFIGBACKUPCOMPRESS;
                                                            }

                                                            if (!defined('CONFIGBACKUPHILOS')) {
                                                                $recbackuphilos = 1;
                                                            } else {
                                                                $recbackuphilos = CONFIGBACKUPHILOS;
                                                            }

                                                            //LIMITE ALMACENAMIENTO
                                                            if ($elerror == 0) {
                                                                //OBTENER GIGAS CARPETA BACKUPS
                                                                $getgigasbackup = getfoldersize($dirconfig);
                                                                $getgigasbackup = trim($getgigasbackup);

                                                                if (!is_numeric($getgigasbackup)) {
                                                                    $retorno = "Error Tarea Backup, no se puede obtener los GB de la carpeta Backups.";
                                                                    $elerror = 1;
                                                                }
                                                            }

                                                            if ($elerror == 0) {
                                                                //CONVERTIR DATOS
                                                                $getgigasbackup = converdatoscarpbackup($getgigasbackup, 0, 2);

                                                                //OBTENER GIGAS LIMITE BACKUPS
                                                                $limitbackupgb = CONFIGFOLDERBACKUPSIZE;

                                                                //MIRAR SI ES ILIMITADO
                                                                if ($limitbackupgb >= 1) {
                                                                    if ($getgigasbackup > $limitbackupgb) {
                                                                        $retorno = "Error Tarea Backup, se ha superado el límite GB para backups.";
                                                                        $elerror = 1;
                                                                    }
                                                                }
                                                            }

                                                            if ($elerror == 0) {
                                                                clearstatcache();
                                                                if (file_exists($dirconfig)) {
                                                                    //COMPROBAR SI SE PUEDE ESCRIBIR
                                                                    clearstatcache();
                                                                    if (is_writable($dirconfig)) {
                                                                        $rutaarchivo = $RUTAPRINCIPAL;
                                                                        $rutaarchivo = trim($rutaarchivo);
                                                                        $rutaminelimpia = $rutaarchivo . "/" . $reccarpmine;
                                                                        clearstatcache();
                                                                        if (is_readable($rutaminelimpia)) {
                                                                            $rutaarchivo = "'" . $rutaarchivo . "/" . $reccarpmine . "/'";
                                                                            $dirconfig = $dirconfig . "/" . $archivo . "-";
                                                                            $rutaexcluidos = trim($RUTAPRINCIPAL . "/config" . "/excludeback.json" . PHP_EOL);

                                                                            $tget = time();
                                                                            $t = date("Y-m-d-G-i-s");
                                                                            //SI ES MONONUCLEO Y NO ES LA MEJOR SE ASIGNA LA RAPIDA
                                                                            if ($recbackupmulti == 1) {
                                                                                if ($recbackupcompress < 9) {
                                                                                    $lacompression = 1;
                                                                                }
                                                                            }

                                                                            //COMPRUEBA SI PIGZ ESTA INSTALADO
                                                                            if ($recbackupmulti == 2) {
                                                                                $comreq = shell_exec('command -v pigz >/dev/null && echo "yes" || echo "no"');
                                                                                $comreq = trim($comreq);
                                                                                if ($comreq == "no") {
                                                                                    $recbackupmulti = 1;
                                                                                }
                                                                            }

                                                                            clearstatcache();
                                                                            if (file_exists($rutaexcluidos)) {
                                                                                clearstatcache();
                                                                                if (is_readable($rutaexcluidos)) {
                                                                                    $elcomando = "tar --warning=no-file-changed ";
                                                                                    $buscaarray = file_get_contents($rutaexcluidos);
                                                                                    $buscaexcluidos = unserialize($buscaarray);
                                                                                    $contadorex = count($buscaexcluidos);

                                                                                    for ($ni = 0; $ni < $contadorex; $ni++) {
                                                                                        clearstatcache();
                                                                                        if (file_exists($buscaexcluidos[$ni]['completa'])) {
                                                                                            $elcomando .= "--exclude='" . $buscaexcluidos[$ni]['excluido'] . "' ";
                                                                                        }
                                                                                    }

                                                                                    if ($recbackupmulti == 1) {
                                                                                        $elcomando .= '--warning=no-file-changed --use-compress-program="gzip -' . $lacompression . '"';
                                                                                    } elseif ($recbackupmulti == 2) {
                                                                                        $elcomando .= '--warning=no-file-changed --use-compress-program="pigz -k -' . $recbackupcompress . ' -p' . $recbackuphilos . '"';
                                                                                    }

                                                                                    $elcomando .= " -cf '" . $dirconfig . $t . ".tar.gz' -C " . $rutaarchivo . " .";
                                                                                } else {
                                                                                    if ($recbackupmulti == 1) {
                                                                                        $elcomando = 'tar --warning=no-file-changed --use-compress-program="gzip -' . $lacompression . '"';
                                                                                    } elseif ($recbackupmulti == 2) {
                                                                                        $elcomando = 'tar --warning=no-file-changed --use-compress-program="pigz -k -' . $recbackupcompress . ' -p' . $recbackuphilos . '"';
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                if ($recbackupmulti == 1) {
                                                                                    $elcomando = 'tar --warning=no-file-changed --use-compress-program="gzip -' . $lacompression . '"';
                                                                                } elseif ($recbackupmulti == 2) {
                                                                                    $elcomando = 'tar --warning=no-file-changed --use-compress-program="pigz -k -' . $recbackupcompress . ' -p' . $recbackuphilos . '"';
                                                                                }

                                                                                $elcomando .= " -cf '" . $dirconfig . $t . ".tar.gz' -C " . $rutaarchivo . " .";
                                                                            }

                                                                            clearstatcache();
                                                                            if (is_executable($rutaminelimpia)) {
                                                                                exec($elcomando, $out, $oky);

                                                                                if (!$oky) {
                                                                                    $retorno = "Tarea Crear Backup, ejecutado correctamente.";

                                                                                    //CARGAR ROTACIONES Y SINO EXISTE 0
                                                                                    if (!defined('CONFIGBACKUROTATE')) {
                                                                                        $elbackuprotate = 0;
                                                                                    } else {
                                                                                        $elbackuprotate = CONFIGBACKUROTATE;
                                                                                    }

                                                                                    //DECLARAR VARIABLES ROTATE
                                                                                    $rutarotate = trim($RUTAPRINCIPAL . "/config" . "/backuprotate.json" . PHP_EOL);
                                                                                    $rutawriteconfig = trim($RUTAPRINCIPAL . "/config" . PHP_EOL);

                                                                                    //GUARDAR LISTA BACKUP ROTATE
                                                                                    if ($elbackuprotate >= 1) {
                                                                                        if (is_writable($rutawriteconfig)) {
                                                                                            clearstatcache();
                                                                                            if (!file_exists($rutarotate)) {
                                                                                                $arraycreate[0]['archivo'] = "AUTO-" . $t . ".tar.gz";
                                                                                                $arraycreate[0]['fecha'] = $tget;
                                                                                                $serialized = serialize($arraycreate);
                                                                                                file_put_contents($rutarotate, $serialized);
                                                                                            } else {
                                                                                                clearstatcache();
                                                                                                if (is_writable($rutarotate)) {

                                                                                                    //INICIAR INDICE DE LOS ARRAYS PARA EVITAR ERRORES
                                                                                                    $rotateindice = 0;

                                                                                                    //LEER ARCHIVO
                                                                                                    $getarrayrotate = file_get_contents($rutarotate);
                                                                                                    $elarrayrotate = unserialize($getarrayrotate);
                                                                                                    $rotateindice = count($elarrayrotate);

                                                                                                    //BORRAR LOS REGISTROS EN LOS QUE NO EXISTA EL ARCHIVO DE BACKUP
                                                                                                    $arraylimpieza = array();
                                                                                                    $elauxiliar = 0;

                                                                                                    for ($elbucle = 0; $elbucle < $rotateindice; $elbucle++) {
                                                                                                        $rotatelimpieza = trim($RUTAPRINCIPAL . "/backups" . "/" . $elarrayrotate[$elbucle]['archivo']);
                                                                                                        clearstatcache();
                                                                                                        if (file_exists($rotatelimpieza)) {
                                                                                                            $arraylimpieza[$elauxiliar]['archivo'] = $elarrayrotate[$elbucle]['archivo'];
                                                                                                            $arraylimpieza[$elauxiliar]['fecha'] = $elarrayrotate[$elbucle]['fecha'];
                                                                                                            $elauxiliar = $elauxiliar + 1;
                                                                                                        }
                                                                                                    }

                                                                                                    $elarrayrotate = null;
                                                                                                    $elarrayrotate = $arraylimpieza;
                                                                                                    $rotateindice = count($elarrayrotate);

                                                                                                    //MIRAR SI HAY QUE ROTAR LOS ARCHIVOS
                                                                                                    if ($rotateindice >= $elbackuprotate) {

                                                                                                        //OBTENER TODAS LAS FECHA Y GUARDAR EN ARRAY AUXILIAR
                                                                                                        for ($elbucle = 0; $elbucle < $rotateindice; $elbucle++) {
                                                                                                            $arrayauxl[$elbucle] = $elarrayrotate[$elbucle]['fecha'];
                                                                                                        }

                                                                                                        //OBTENER EL VALOR MAS PEQUEÑO DE FECHA
                                                                                                        sort($arrayauxl);

                                                                                                        //CREAR ARRAY NUEVO SIN FECHA MAS ANTIGUA
                                                                                                        $arrayfinal = array();
                                                                                                        $elauxiliar = 0;

                                                                                                        for ($elbucle = 0; $elbucle < $rotateindice; $elbucle++) {
                                                                                                            if ($arrayauxl[0] != $elarrayrotate[$elbucle]['fecha']) {
                                                                                                                $arrayfinal[$elauxiliar]['archivo'] = $elarrayrotate[$elbucle]['archivo'];
                                                                                                                $arrayfinal[$elauxiliar]['fecha'] = $elarrayrotate[$elbucle]['fecha'];
                                                                                                                $elauxiliar = $elauxiliar + 1;
                                                                                                            } else {
                                                                                                                //BORRAR ARCHIVO
                                                                                                                $rotatedelete = trim($RUTAPRINCIPAL . "/backups" . "/" . $elarrayrotate[$elbucle]['archivo']);

                                                                                                                clearstatcache();
                                                                                                                if (file_exists($rotatedelete)) {
                                                                                                                    clearstatcache();
                                                                                                                    if (is_writable($rotatedelete)) {
                                                                                                                        unlink($rotatedelete);
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }

                                                                                                        $elarrayrotate = null;
                                                                                                        $elarrayrotate = $arrayfinal;
                                                                                                        $rotateindice = count($elarrayrotate);
                                                                                                    }

                                                                                                    //GUARDAR FICHERO
                                                                                                    $elarrayrotate[$rotateindice]['archivo'] = "AUTO-" . $t . ".tar.gz";
                                                                                                    $elarrayrotate[$rotateindice]['fecha'] = $tget;
                                                                                                    $serialized2 = serialize($elarrayrotate);
                                                                                                    file_put_contents($rutarotate, $serialized2);
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    $retorno = "Error Tarea Crear Backup, no se creo correctamente.";
                                                                                    //AUNQUE NO SE CREA, A VECES SI CREA UN FICHERO VACIO
                                                                                    $borrarerror = $dirconfig . $t . ".tar.gz";
                                                                                    if (file_exists($borrarerror)) {
                                                                                        unlink($borrarerror);
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            break;
                                                        case "acc4":
                                                            //ENVIAR COMANDO
                                                            $paraejecutar = htmlspecialchars_decode($arrayobtenido[$i]['comando']);
                                                            $paraejecutar = addslashes($paraejecutar);

                                                            //OBTENER PID SABER SI ESTA EN EJECUCION
                                                            $elcomando = "";
                                                            $elnombrescreen = CONFIGDIRECTORIO;
                                                            $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
                                                            $elpid = shell_exec($elcomando);

                                                            //SI ESTA EN EJECUCION ENVIAR COMANDO
                                                            if (!$elpid == "") {
                                                                $laejecucion = 'screen -S ' . $elnombrescreen . ' -X stuff "' . trim($paraejecutar) . '^M"';
                                                                exec($laejecucion);
                                                                $retorno = "Tarea Enviar Comando, ejecutado correctamente.";
                                                            } else {
                                                                $retorno = "Tarea Enviar Comando, no se puede realizar al estar apagado el servidor.";
                                                            }

                                                            break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
if ($retorno != "") {
    echo $logfechayhora . ": " . $retorno . PHP_EOL;
} else {
    echo $retorno;
}
