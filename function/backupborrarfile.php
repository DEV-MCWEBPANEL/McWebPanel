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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pbackupsborrar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pbackupsborrar'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {

            $retorno = "";
            $verificarex = "";
            $elerror = 0;

            $archivo = test_input($_POST['action']);

            //OBTENER RUTA RAIZ
            $dirraiz = dirname(getcwd()) . PHP_EOL;
            $dirraiz = trim($dirraiz);

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

            if ($elerror == 0) {
                //OBTENER RUTA CARPETA BACKUP
                $dirconfig = "";
                $dirconfig = dirname(getcwd()) . PHP_EOL;
                $dirconfig = trim($dirconfig);
                $dirconfig .= "/backups";
                $dirconfig = $dirconfig . "/" . $archivo;

                clearstatcache();
                if (file_exists($dirconfig)) {
                    //COMPROBAR SI SE PUEDE ESCRIBIR
                    if (is_writable($dirconfig)) {
                        $retorno = unlink($dirconfig);

                        //DESROTAR ARCHIVO
                        $rotateindice = 0;
                        $elauxiliar = 0;
                        $elauxlimpieza = 0;
                        $arraylimpieza = array();
                        $rutarotate = trim($dirraiz . "/config" . "/backuprotate.json" . PHP_EOL);

                        clearstatcache();
                        if (is_writable($rutarotate)) {

                            //LEER ARCHIVO
                            $getarrayrotate = file_get_contents($rutarotate);
                            $elarrayrotate = unserialize($getarrayrotate);
                            $rotateindice = count($elarrayrotate);

                            //RECORER BUCLE Y QUITAR LOS REGISTROS QUE SE LLAMEN IGUAL A ARCHIVO
                            for ($elbucle = 0; $elbucle < $rotateindice; $elbucle++) {
                                if ($archivo != $elarrayrotate[$elbucle]['archivo']) {
                                    $arraylimpieza[$elauxiliar]['archivo'] = $elarrayrotate[$elbucle]['archivo'];
                                    $arraylimpieza[$elauxiliar]['fecha'] = $elarrayrotate[$elbucle]['fecha'];
                                    $elauxiliar = $elauxiliar + 1;
                                }
                            }

                            //OBTENER EL NUMERO DE REGISTROS DEL ARRAY LIMPIEZA
                            $elauxlimpieza = count($arraylimpieza);

                            //SI NO HAY REGISTROS SE BORRA SINO SE GUARDA ARCHIVO
                            if ($elauxlimpieza == 0) {
                                clearstatcache();
                                if (is_writable($rutarotate)) {
                                    unlink($rutarotate);
                                }
                            } else {
                                $serializedlimpia = serialize($arraylimpieza);
                                file_put_contents($rutarotate, $serializedlimpia);
                            }
                        }
                    } else {
                        $retorno = "nowritable";
                    }
                } else {
                    $retorno = "noexiste";
                }
            }
        }
        echo $retorno;
    }
}
