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

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
    $_SESSION['VALIDADO'] = "NO";
    $_SESSION['KEYSECRETA'] = "0";
}

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1) {
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownpaper', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownpaper'] == 1) {

            if (isset($_POST['action']) && !empty($_POST['action'])) {
                $retorno = "";
                $elerror = 0;
                $verencontrado = 0;
                $buildencontrado = 0;
                $verifiquetver = "";
                $verifibuild = "";
                $versiones2 = 0;
                $nombrepaperjar = "";
                $elsha256 = "";

                $reccarpmine = CONFIGDIRECTORIO;

                $carpraiz = dirname(getcwd()) . PHP_EOL;
                $carpraiz = trim($carpraiz);

                $dirtemp = $carpraiz . "/temp";
                $dirmine = $carpraiz . "/" . $reccarpmine;

                $laaction = test_input($_POST['action']);
                $getversion = test_input($_POST['laversion']);

                if ($laaction == "") {
                    $retorno = "nopostaction";
                    $elerror = 1;
                }

                if ($getversion == "") {
                    $retorno = "nopostver";
                    $elerror = 1;
                }

                if ($elerror == 0) {
                    if ($laaction == "getbuild") {

                        //OBTENER VERSIONES PAPER
                        $url = "https://papermc.io/api/v2/projects/paper";

                        $context = stream_context_create(
                            array(
                                "http" => array(
                                    "timeout" => 10,
                                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                                )
                            )
                        );

                        $contenido = @file_get_contents($url, false, $context);

                        if ($contenido === FALSE) {
                            $elerror = 1;
                            $retorno = "errrorgetpaperversion";
                        } else {

                            $versiones = json_decode($contenido, true);
                            $versiones = $versiones['versions'];
                        }

                        //COMPROBAR SI EXISTE LA VERSION SELECCIONADA
                        if ($elerror == 0) {

                            for ($i = 0; $i < count($versiones); $i++) {
                                if ($getversion == $versiones[$i]) {
                                    $verencontrado = 1;
                                    $verifiquetver = $versiones[$i];
                                }
                            }

                            if ($verencontrado == 0) {
                                $elerror = 1;
                                $retorno = "noverfound";
                            }
                        }

                        //OBTENER BUILDS DE LA VERSION ELEGIDA
                        if ($elerror == 0) {
                            $url2 = "https://papermc.io/api/v2/projects/paper/versions/" . $verifiquetver;

                            $context2 = stream_context_create(
                                array(
                                    "http" => array(
                                        "timeout" => 10,
                                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                                    )
                                )
                            );

                            $contenido2 = @file_get_contents($url2, false, $context2);

                            if ($contenido2 === FALSE) {
                                $retorno = "errorgetbuilds";
                                $elerror = 1;
                            } else {
                                $resultado2 = json_decode($contenido2, true);
                                $versiones2 = $resultado2['builds'];
                                $versiones2 = array_reverse($versiones2);
                                $retorno = "okbuild";
                            }
                        }
                    } elseif ($laaction == "descargar") {

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

                        //COMPROBAR INPUT
                        if ($elerror == 0) {
                            $getbuild = test_input($_POST['labuild']);

                            if ($getbuild == "") {
                                $retorno = "nopostbuild";
                                $elerror = 1;
                            }
                        }

                        //OBTENER VERSIONES PAPER
                        if ($elerror == 0) {

                            $url = "https://papermc.io/api/v2/projects/paper";

                            $context = stream_context_create(
                                array(
                                    "http" => array(
                                        "timeout" => 10,
                                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                                    )
                                )
                            );

                            $contenido = @file_get_contents($url, false, $context);

                            if ($contenido === FALSE) {
                                $elerror = 1;
                                $retorno = "errrorgetpaperversion";
                            } else {

                                $versiones = json_decode($contenido, true);
                                $versiones = $versiones['versions'];
                            }
                        }

                        //COMPROBAR SI EXISTE LA VERSION SELECCIONADA
                        if ($elerror == 0) {

                            for ($i = 0; $i < count($versiones); $i++) {
                                if ($getversion == $versiones[$i]) {
                                    $verencontrado = 1;
                                    $verifiquetver = trim($versiones[$i]);
                                }
                            }

                            if ($verencontrado == 0) {
                                $elerror = 1;
                                $retorno = "noverfound";
                            }
                        }

                        //OBTENER BUILDS DE LA VERSION ELEGIDA
                        if ($elerror == 0) {
                            $url2 = "https://papermc.io/api/v2/projects/paper/versions/" . $verifiquetver;

                            $context2 = stream_context_create(
                                array(
                                    "http" => array(
                                        "timeout" => 10,
                                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                                    )
                                )
                            );

                            $contenido2 = @file_get_contents($url2, false, $context2);

                            if ($contenido2 === FALSE) {
                                $retorno = "errorgetbuilds";
                                $elerror = 1;
                            } else {
                                $resultado2 = json_decode($contenido2, true);
                                $versiones2 = $resultado2['builds'];
                            }
                        }

                        //COMPROBAR SI EXISTE LA BUILD SELECCIONADA
                        if ($elerror == 0) {

                            for ($i = 0; $i < count($versiones2); $i++) {
                                if ($getbuild == $versiones2[$i]) {
                                    $buildencontrado = 1;
                                    $verifibuild = trim($versiones2[$i]);
                                }
                            }

                            if ($buildencontrado == 0) {
                                $elerror = 1;
                                $retorno = "nobuildfound";
                            }
                        }

                        //OBTENER DETALLES VERSION
                        if ($elerror == 0) {

                            $url3 = "https://papermc.io/api/v2/projects/paper/versions/" . $verifiquetver . "/builds/" . $verifibuild;

                            $context3 = stream_context_create(
                                array(
                                    "http" => array(
                                        "timeout" => 10,
                                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                                    )
                                )
                            );

                            $contenido3 = @file_get_contents($url3, false, $context3);

                            if ($contenido3 === FALSE) {
                                $retorno = "errorgetverinfo";
                                $elerror = 1;
                            } else {
                                $resultado3 = json_decode($contenido3, true);
                                $versiones3 = $resultado3['downloads'];
                                $versiones3 = $versiones3['application'];
                                $nombrepaperjar = $versiones3['name'];
                                $elsha256 = $versiones3['sha256'];
                            }
                        }

                        //DESCARGAR PAPER
                        if ($elerror == 0) {

                            $urldownjar = "https://papermc.io/api/v2/projects/paper/versions/" . $verifiquetver . "/builds/" . $verifibuild . "/downloads/" . $nombrepaperjar;

                            $elssh = $dirtemp . "/getpaper.sh";

                            //OBTENER FECHA
                            $t = date("Y-m-d-G-i-s");
                            $nombrefichero = "paper-" . $verifiquetver . "-" . $verifibuild . "-" . $t . ".jar";
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
}
