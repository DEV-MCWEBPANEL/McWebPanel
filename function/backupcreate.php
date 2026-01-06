<?php

/*
This file is part of McWebPanel.
Copyright (C) 2020-2026 DEV-MCWEBPANEL

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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pbackupscrear', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pbackupscrear'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {

            $retorno = "";
            $reccarpmine = CONFIGDIRECTORIO;
            $limitbackupgb = CONFIGFOLDERBACKUPSIZE;
            $elerror = 0;
            $test = 0;
            $rutacrearbackup = "";

            $elarray = $_POST['action'];
            $archivo = $elarray[0];
            $archivo = test_input($archivo);

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

            //OBTENER RUTA RAIZ
            $dirraiz = dirname(getcwd()) . PHP_EOL;
            $dirraiz = trim($dirraiz);

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
            $dirsh .= "/backup.sh";

            //OBTENER IDENFIFICADOR SCREEN
            $nombrescreen = $dirraiz . "/losbackups";
            $nombrescreen = str_replace("/", "", $nombrescreen);

            //VER SI HAY UN PROCESO YA EN RESTORE
            if ($elerror == 0) {
                $procesorestore = $dirraiz . "/restaurar";
                $procesorestore = str_replace("/", "", $procesorestore);

                $elcomando = "screen -ls | gawk '/\." . $procesorestore . "\t/ {print strtonum($1)'}";
                $elpid = shell_exec($elcomando);

                if ($elpid != "") {
                    $retorno = "restoreenejecucion";
                    $elerror = 1;
                }
            }

            //VER SI HAY UN PROCESO YA EN BACKUP
            if ($elerror == 0) {
                $elcomando = "screen -ls | gawk '/\." . $nombrescreen . "\t/ {print strtonum($1)'}";
                $elpid = shell_exec($elcomando);

                if ($elpid != "") {
                    $retorno = "backenejecucion";
                    $elerror = 1;
                }
            }

            if ($elerror == 0) {
                if ($archivo == "") {
                    $retorno = "noname";
                    $elerror = 1;
                }
            }

            //Evitar poder ir a una ruta hacia atras
            if ($elerror == 0) {
                if (strpos($archivo, '..') !== false || strpos($archivo, '*.*') !== false || strpos($archivo, '*/*.*') !== false) {
                    exit;
                }
            }

            if ($elerror == 0) {

                $verificar = array('..', '...', '/.', '~', '../', './', ';', ':', '>', '<', '/', '\\', '&&');

                for ($i = 0; $i < count($verificar); $i++) {

                    $test = substr_count($archivo, $verificar[$i]);

                    if ($test >= 1) {
                        $retorno = "novalidoname";
                        $elerror = 1;
                    }
                }
            }

            //LIMITE ALMACENAMIENTO
            if ($elerror == 0) {
                if ($_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {

                    //MIRAR SI ES ILIMITADO
                    if ($limitbackupgb >= 1) {

                        $getgigasbackup = converdatos(obtenersizecarpeta($dirbackups), 0, 2);

                        if (!is_numeric($getgigasbackup)) {
                            $retorno = "ERRORGETSIZE";
                            $elerror = 1;
                        }

                        if ($elerror == 0) {
                            if ($getgigasbackup > $limitbackupgb) {
                                $retorno = "limitgbexceeded";
                                $elerror = 1;
                            }
                        }
                    }
                }
            }

            //MIRAR SI CARPETA BACKUPS EXISTE
            if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($dirbackups)) {
                    $retorno = "noexiste";
                    $elerror = 1;
                }
            }

            //MIRAR SI CARPETA BACKUPS SE PUEDE ESCRIBIR
            if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($dirbackups)) {
                    $retorno = "nowritable";
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

            //MIRAR SI CARPETA MINECRAFT SE PUEDE LEER
            if ($elerror == 0) {
                clearstatcache();
                if (!is_readable($dirminecraft)) {
                    $retorno = "nolectura";
                    $elerror = 1;
                }
            }

            //MIRAR SI CARPETA MINECRAF SE PUEDE EJECUTAR
            if ($elerror == 0) {
                clearstatcache();
                if (!is_executable($dirminecraft)) {
                    $retorno = "noejecutable";
                    $elerror = 1;
                }
            }

            if ($elerror == 0) {
                //BORRAR START.SH EN CASO QUE EXISTA
                $borrastart = $dirminecraft . "/start.sh";
                clearstatcache();
                if (file_exists($borrastart . "/start.sh")) {
                    unlink($borrastart);
                }

                $rutaexcluidos = trim(dirname(getcwd()) . "/config" . "/excludeback.json" . PHP_EOL);
                $t = date("Y-m-d-G-i-s");
                $rutacrearbackup = $dirtemp . "/" . $archivo . "-" . $t;
                $rutaacomprimir = "'" . $dirminecraft . "/' .";

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
                        $contador = count($buscaexcluidos);

                        for ($ni = 0; $ni < $contador; $ni++) {
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

                        $elcomando .= " -cf '" . $rutacrearbackup . ".tar.gz' -C " . $rutaacomprimir;
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

                    $elcomando .= " -cf '" . $rutacrearbackup . ".tar.gz' -C " . $rutaacomprimir;
                }
                $moverabackups = "mv '" . $archivo . "-" . $t . ".tar.gz' '" . $dirbackups . "/" . $archivo . "-" . $t . ".tar.gz'";
                $delsh = "rm backup.sh";

                $file = fopen($dirsh, "w");
                fwrite($file, "#!/bin/bash" . PHP_EOL);
                fwrite($file, $elcomando . PHP_EOL);
                fwrite($file, $moverabackups . PHP_EOL);
                fwrite($file, $delsh . PHP_EOL);
                fclose($file);

                //DAR PERMISOS AL SH
                $comando = "cd " . $dirtemp . " && chmod +x backup.sh";
                exec($comando);

                //INICIAR SCREEN
                $comando = "cd " . $dirtemp . " && umask 002 && screen -dmS " . $nombrescreen . " sh backup.sh";
                exec($comando, $out, $oky);

                if ($oky) {
                    $retorno = "nobackup";
                }
            }
            echo $retorno;
        }
    }
}
