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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {
            $retorno = "";
            $elerror = 0;
            $versiones2 = 0;
            $elsha256 = "";

            $reccarpmine = CONFIGDIRECTORIO;

            $carpraiz = dirname(getcwd()) . PHP_EOL;
            $carpraiz = trim($carpraiz);

            $dirtemp = $carpraiz . "/temp";
            $dirmine = $carpraiz . "/" . $reccarpmine;

            $laaction = test_input($_POST['action']);
            $getproyecto = test_input($_POST['elproyecto']);

            if ($laaction == "") {
                $retorno = "nopostaction";
                $elerror = 1;
            }

            if ($getproyecto == "") {
                $retorno = "nopostproyect";
                $elerror = 1;
            }

            if ($elerror == 0) {
                if ($laaction == "getversion") {

                    //OBTENER VERSIONES
                    $url = "https://api.papermc.io/v2/projects/" . $getproyecto;
                    $context = stream_context_create(
                        array(
                            "http" => array(
                                "timeout" => 10,
                                "header" => "User-Agent: User Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 Edg/132.0.0.0"
                            )
                        )
                    );

                    $contenido = @file_get_contents($url, false, $context);

                    if ($contenido === FALSE) {
                        $elerror = 1;
                        $retorno = "errrorgetversions";
                    } else {

                        $versiones = json_decode($contenido, true);
                        $versiones = $versiones['versions'];
                        $versiones2 = array_reverse($versiones);
                        $retorno = "okbuild";
                    }
                } elseif ($laaction == "getbuild") {
                    $getversion = test_input($_POST['elversion']);

                    if ($getversion == "") {
                        $retorno = "nopostver";
                        $elerror = 1;
                    }

                    if ($elerror == 0) {

                        //OBTENER BUILDS
                        $url = "https://api.papermc.io/v2/projects/" . $getproyecto . "/versions/" . $getversion;
                        $context = stream_context_create(
                            array(
                                "http" => array(
                                    "timeout" => 10,
                                    "header" => "User-Agent: User Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 Edg/132.0.0.0"
                                )
                            )
                        );

                        $contenido = @file_get_contents($url, false, $context);

                        if ($contenido === FALSE) {
                            $elerror = 1;
                            $retorno = "errrorgetbuilds";
                        } else {
                            $versiones = json_decode($contenido, true);
                            $versiones = $versiones['builds'];
                            $versiones2 = array_reverse($versiones);
                            $retorno = "okbuild";
                        }
                    }
                } elseif ($laaction == "descargar") {

                    $getversion = test_input($_POST['elversion']);
                    $getbuild = test_input($_POST['elbuild']);

                    if ($getversion == "") {
                        $retorno = "nopostver";
                        $elerror = 1;
                    }

                    if ($getbuild == "") {
                        $retorno = "nopostbuild";
                        $elerror = 1;
                    }

                    //COMPROBAR SI TEMP ES WRITABLE
                    if ($elerror == 0) {
                        clearstatcache();
                        if (!is_writable($dirtemp)) {
                            $elerror = 1;
                            $retorno = "nodirwrite";
                        }
                    }

                    //COMPROBAR SI DIR MINECRAFT ES WRITABLE
                    if ($elerror == 0) {
                        clearstatcache();
                        if (!is_writable($dirmine)) {
                            $elerror = 1;
                            $retorno = "nominewrite";
                        }
                    }

                    //OBTENER DETALLES BUILD
                    if ($elerror == 0) {

                        $url3 = "https://api.papermc.io/v2/projects/" . $getproyecto . "/versions/" . $getversion . "/builds/" . $getbuild;
                        $context3 = stream_context_create(
                            array(
                                "http" => array(
                                    "timeout" => 10,
                                    "header" => "User-Agent: User Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 Edg/132.0.0.0"
                                )
                            )
                        );

                        $contenido3 = @file_get_contents($url3, false, $context3);

                        if ($contenido3 === FALSE) {
                            $retorno = "errorgetbuildinfo";
                            $elerror = 1;
                        } else {
                            $resultado3 = json_decode($contenido3, true);
                            $versiones3 = $resultado3['downloads'];
                            $versiones3 = $versiones3['application'];
                            $nombrejar = $versiones3['name'];
                            $elsha256 = $versiones3['sha256'];
                        }
                    }

                    //DESCARGAR PAPER
                    if ($elerror == 0) {

                        $urldownjar = "https://api.papermc.io/v2/projects/" . $getproyecto . "/versions/" . $getversion . "/builds/" . $getbuild . "/downloads/" . $nombrejar;
                        $elssh = $dirtemp . "/getpaper.sh";

                        //OBTENER FECHA
                        $t = date("Y-m-d-G-i-s");
                        $nombrefichero = $getproyecto . "-" . $getversion . "-" . $getbuild . "-" . $t . ".jar";
                        $delsh = "rm getpaper.sh";

                        $file = fopen($elssh, "w");
                        fwrite($file, "#!/bin/bash" . PHP_EOL);
                        fwrite($file, "wget -cO - " . $urldownjar . " > " . $nombrefichero . PHP_EOL);
                        fwrite($file, $delsh . PHP_EOL);
                        fclose($file);

                        $comando = "cd " . $dirtemp . " && chmod +x getpaper.sh && sh getpaper.sh";
                        exec($comando);
                    }

                    //COMPROBAR SI ESTA DESCARGADO
                    if ($elerror == 0) {
                        $rutafichero = $dirtemp . "/" . $nombrefichero;
                        clearstatcache();
                        if (!file_exists($rutafichero)) {
                            $elerror = 1;
                            $retorno = "filenodownload";
                        }
                    }

                    //CHECKEAR CON SHA256
                    if ($elerror == 0) {
                        $verifisha256 = hash_file('sha256', $rutafichero);
                        $retorno = $verifisha256;

                        if ($verifisha256 != $elsha256) {
                            unlink($rutafichero);
                            $elerror = 1;
                            $retorno = "nogoodsha256";
                        }
                    }

                    //ASIGNAR PERMISOS CORRECTOS
                    if ($elerror == 0) {
                        exec("chmod 664 " . $rutafichero);
                    }

                    //MOVER A LA CARPETA DE MINECRAFT
                    if ($elerror == 0) {
                        $rutadestino = $dirmine . "/" . $nombrefichero;
                        $moverok = rename($rutafichero, $rutadestino);
                        if ($moverok == 1) {
                            $retorno = "ok";
                        } else {
                            $retorno = "renamerror";
                        }
                    }
                }
            }

            $elarray = array("retorno" => $retorno, "lasbuild" => $versiones2);
            echo json_encode($elarray);
        }
    }
}
