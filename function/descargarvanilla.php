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
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownvanilla', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownvanilla'] == 1) {

            if (isset($_POST['action']) && !empty($_POST['action'])) {
                $retorno = "";
                $elerror = 0;
                $elindexarray = 0;
                $indexverencontrada = 0;
                $verencontrado = 0;
                $elcomando = "";

                $reccarpmine = CONFIGDIRECTORIO;

                $laaction = test_input($_POST['action']);
                $getversion = test_input($_POST['laversion']);

                $carpraiz = dirname(getcwd()) . PHP_EOL;
                $carpraiz = trim($carpraiz);

                $dirtemp = $carpraiz . "/temp";
                $dirmine = $carpraiz . "/" . $reccarpmine;

                //COMPROBQAR SI TEMP ES WRITABLE
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

                //OBTENER JSON MANIFEST Y CONVERTIRLO EN ARRAY
                if ($elerror == 0) {
                    $url = "https://launchermeta.mojang.com/mc/game/version_manifest.json";

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
                        $retorno = "timeoutmanifest";
                    } else {
                        $versiones = json_decode($contenido, true);
                        $versiones = $versiones['versions'];
                    }
                }

                //COMPROBAR SI EXISTE LA VERSION SELECCIONADA EN EL MANIFEST
                if ($elerror == 0) {

                    for ($i = 0; $i < count($versiones); $i++) {
                        if ($getversion == $versiones[$i]['id']) {
                            $indexverencontrada = $i;
                            $verencontrado = 1;
                        }
                    }

                    if ($verencontrado == 0) {
                        $elerror = 1;
                        $retorno = "noverfound";
                    }
                }

                //OBTENER EL JSON DE LA VERSION SELECCIONADA Y CONVERTIRLO EN ARRAY
                if ($elerror == 0) {
                    $url2 = $versiones[$indexverencontrada]['url'];

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
                        $elerror = 1;
                        $retorno = "timeout";
                    } else {

                        $versiones2 = json_decode($contenido2, true);
                        $versiones2 = $versiones2['downloads'];

                        if (isset($versiones2['server'])) {
                            $versiones2 = $versiones2['server'];
                        } else {
                            $elerror = 1;
                            $retorno = "noserverfound";
                        }
                    }
                }

                //DESCARGAR VERSION
                if ($elerror == 0) {

                    $elssh = $dirtemp . "/getvanilla.sh";

                    //OBTENER FECHA
                    $t = date("Y-m-d-G-i-s");
                    $nombrefichero = "vanilla-" . $getversion . "-" . $t . ".jar";
                    $delsh = "rm getvanilla.sh";

                    $file = fopen($elssh, "w");
                    fwrite($file, "#!/bin/bash" . PHP_EOL);
                    fwrite($file, "wget -cO - " . $versiones2['url'] . " > " . $nombrefichero . PHP_EOL);
                    fwrite($file, $delsh . PHP_EOL);
                    fclose($file);

                    $comando = "cd " . $dirtemp . " && chmod +x getvanilla.sh && sh getvanilla.sh";
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

                //CHECKEAR CON SHA1
                if ($elerror == 0) {
                    $verifisha1 = sha1_file($rutafichero);

                    if ($verifisha1 != $versiones2['sha1']) {
                        unlink($rutafichero);
                        $elerror = 1;
                        $retorno = "nogoodsha1";
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
                echo $retorno;
            }
        }
    }
}
