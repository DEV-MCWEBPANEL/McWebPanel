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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pbackupsrestaurar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pbackupsrestaurar'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {

            $retorno = "";
            $reccarpmine = CONFIGDIRECTORIO;
            $elerror = 0;
            $verificarex = "";

            $permcomando = "";
            $dirconfig = "";

            $archivo = test_input($_POST['action']);

            //OBTENER RUTA RAIZ
            $rutaraiz = dirname(getcwd()) . PHP_EOL;
            $rutaraiz = trim($rutaraiz);

            //OBTENER RUTA CARPETA BACKUP
            $dirbackups = "";
            $dirbackups = dirname(getcwd()) . PHP_EOL;
            $dirbackups = trim($dirbackups);
            $dirbackups .= "/backups";

            //OBTENER RUTA CARPETA MINECRAFT
            $dirminecraft = "";
            $dirminecraft = dirname(getcwd()) . PHP_EOL;
            $dirminecraft = trim($dirminecraft);
            $dirminecraft .= "/" . $reccarpmine;

            //OBTENER RUTA TEMP
            $dirtemp = "";
            $dirtemp = dirname(getcwd()) . PHP_EOL;
            $dirtemp = trim($dirtemp);
            $dirtemp .= "/temp";

            //OBTENER RUTA SH TEMP
            $dirsh = "";
            $dirsh = $dirtemp;
            $dirsh .= "/restaurar.sh";

            //OBTENER IDENFIFICADOR SCREEN
            $nombrescreen = $rutaraiz . "/restaurar";
            $nombrescreen = str_replace("/", "", $nombrescreen);

            //OBTENER RUTA BACKUP COMPLETA
            $comproarchivo = $rutaraiz;
            $comproarchivo = $comproarchivo . "/backups/" . $archivo;

            //VER SI ESTA EL SERVIDOR ENCENDIDO
            if ($elerror == 0) {
                $elcomando = "screen -ls | gawk '/\." . $reccarpmine . "\t/ {print strtonum($1)'}";
                $elpid = shell_exec($elcomando);

                if ($elpid != "") {
                    $retorno = "servidorejecucion";
                    $elerror = 1;
                }
            }

            //VER SI HAY UN PROCESO YA EN BACKUP
            if ($elerror == 0) {
                $procesobackup = $rutaraiz . "/losbackups";
                $procesobackup = str_replace("/", "", $procesobackup);

                $elcomando = "screen -ls | gawk '/\." . $procesobackup . "\t/ {print strtonum($1)'}";
                $elpid = shell_exec($elcomando);

                if ($elpid != "") {
                    $retorno = "backenejecucion";
                    $elerror = 1;
                }
            }

            //VER SI HAY UN PROCESO YA EN RESTAURAR
            if ($elerror == 0) {
                $elcomando = "screen -ls | gawk '/\." . $nombrescreen . "\t/ {print strtonum($1)'}";
                $elpid = shell_exec($elcomando);

                if ($elpid != "") {
                    $retorno = "restoreenejecucion";
                    $elerror = 1;
                }
            }

            //Evitar poder ir a una ruta hacia atras
            if ($elerror == 0) {
                if (strpos($archivo, '..') !== false || strpos($archivo, '*.*') !== false || strpos($archivo, '*/*.*') !== false) {
                    exit;
                }
            }

            //VERIFICAR EXTENSION
            if ($elerror == 0) {
                $verificarex = substr($archivo, -7);
                if ($verificarex != ".tar.gz") {
                    exit;
                }
            }

            //COMPROBAR SI LA RAIZ SE PUEDE ESCRIBIR
            if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($rutaraiz)) {
                    $retorno = "nowriteraiz";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI EXISTE LA CARPETA SERVIDOR MINECRAFT
            if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($dirminecraft)) {
                    $retorno = "nominexiste";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI SE PUEDE ESCRIBIR LA CARPETA SERVIDOR MINECRAFT
            if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($dirminecraft)) {
                    $retorno = "minenowrite";
                    $elerror = 1;
                }
            }

            //MIRAR SI LA CARPETA TEMP EXISTE
            if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($dirtemp)) {
                    $retorno = "notempexiste";
                    $elerror = 1;
                }
            }

            //MIRAR SI CARPETA TEMP SE PUEDE ESCRIBIR
            if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($dirtemp)) {
                    $retorno = "notempwritable";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI EXISTE EL ARCHIVO TAR
            if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($comproarchivo)) {
                    $retorno = "tarnoexiste";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI SE PUEDE ESCRIBIR EN ARCHIVO TAR
            if ($elerror == 0) {
                clearstatcache();
                if (!is_readable($comproarchivo)) {
                    $retorno = "tarnolectura";
                    $elerror = 1;
                }
            }

            //PROCEDE A RESTAURAR
            if ($elerror == 0) {

                //BORRAR CARPETA MINECRAFT
                $permcomando = "rm -R '" . $dirminecraft . "'";
                exec($permcomando);

                //CREAR CARPETA
                mkdir("$dirminecraft", 0700);

                //DAR PERMISOS A LA CARPETA MINECRAFT
                $permcomando = "chmod 775 '" . $dirminecraft . "'";
                exec($permcomando);

                //GUARDAR FICHERO .htaccess EN MINECRAFT
                $diraccess = $dirminecraft . "/.htaccess";
                $file = fopen($diraccess, "w");
                fwrite($file, "deny from all" . PHP_EOL);
                fwrite($file, "php_flag engine off" . PHP_EOL);
                fwrite($file, "AllowOverride None" . PHP_EOL);
                fclose($file);

                //DESCOMPRIMIR TAR
                $dirbackups = $rutaraiz;
                $dirbackups = $dirbackups . "/backups/";

                $elcomando1 = "tar -xzvf '" . $dirbackups . $archivo . "' -C " . $dirminecraft;
                $elcomando2 = "rm " . $diraccess;
                $elcomando3 = "echo 'deny from all' >> " . $diraccess;
                $elcomando4 = "echo 'php_flag engine off' >> " . $diraccess;
                $elcomando5 = "echo 'AllowOverride None' >> " . $diraccess;
                $elcomando6 = "chmod 644 " . $diraccess;
                $delsh = "rm " . $dirsh;

                $file = fopen($dirsh, "w");
                fwrite($file, "#!/bin/bash" . PHP_EOL);
                fwrite($file, $elcomando1 . PHP_EOL);
                fwrite($file, $elcomando2 . PHP_EOL);
                fwrite($file, $elcomando3 . PHP_EOL);
                fwrite($file, $elcomando4 . PHP_EOL);
                fwrite($file, $elcomando5 . PHP_EOL);
                fwrite($file, $elcomando6 . PHP_EOL);
                fwrite($file, $delsh . PHP_EOL);
                fclose($file);

                //DAR PERMISOS AL SH
                $comando = "cd " . $dirtemp . " && chmod +x restaurar.sh";
                exec($comando);

                //INICIAR SCREEN
                $comando = "cd " . $dirtemp . " && umask 002 && screen -dmS '" . $nombrescreen . "' sh restaurar.sh";
                exec($comando, $out, $oky);

                if (!$oky) {
                    $retorno = "okrestore";
                } else {
                    $retorno = "norestore";
                }
            }
        }
        echo $retorno;
    }
}
