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

function converdatoscarpmine($losbytes, $opcion, $decimal)
{
    $eltipo = "GB";
    $result = $losbytes / 1048576;

    if ($opcion == 0) {
        $result = strval(round($result, $decimal));
        return $result;
    } elseif ($opcion == 1) {
        $result = strval(round($result, $decimal)) . " " . $eltipo;
        return $result;
    }
}

function converdatoscarpmineGB($losbytes, $opcion, $decimal)
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

    if (isset($_POST['action']) && !empty($_POST['action'])) {

        $retorno = "";
        $elerror = 0;
        $archivosize = 0;
        $limitmine = CONFIGFOLDERMINECRAFTSIZE;
        $reccarpmine = CONFIGDIRECTORIO;
        $rutacarpetamine = "";
        $getgigasmine = "";

        //OBTENER UPLOAD MAX PHP
        $maxdeupload = ini_get("upload_max_filesize");
        $maxdeupload = substr($maxdeupload, 0, -1);
        $maxdeupload = trim($maxdeupload);

        $archivosize = test_input($_POST['action']);

        //CONVERTIR DATOS
        $archivosizemb = converdatoscarpmine($archivosize, 0, 0);
        $archivosizegb = converdatoscarpmineGB($archivosize, 0, 2);

        //COMPROBAR SI LO QUE SE SUBE ES MAYOR AL UPLOAD PERMITIDO
        if ($elerror == 0) {
            if ($archivosizemb > $maxdeupload) {
                $elerror = 1;
                $retorno = "OUTUPLOAD";
            }
        }

        //COMPROBAR SI LO QUE SE SUBE ES MAYOR AL LIMITE CARPETA MINECRAFT
        if ($elerror == 0) {
            if ($_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {

                if ($limitmine >= 1) {
                    if ($archivosizegb > $limitmine) {
                        $elerror = 1;
                        $retorno = "OUTLIMITE";
                    }
                }
            }
        }

        //LIMITE ALMACENAMIENTO
        if ($elerror == 0) {
            if ($_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {

                //OBTENER CARPETA SERVIDOR MINECRAFT
                $rutacarpetamine = dirname(getcwd()) . PHP_EOL;
                $rutacarpetamine = trim($rutacarpetamine);
                $rutacarpetamine .= "/" . $reccarpmine;

                $getgigasmine = converdatoscarpmineGB(obtenersizecarpeta($rutacarpetamine), 0, 2);

                if (!is_numeric($getgigasmine)) {
                    $retorno = "ERRORGETSIZE";
                    $elerror = 1;
                }

                //MIRAR SI ES ILIMITADO
                if ($limitmine >= 1) {

                    if ($elerror == 0) {
                        if ($getgigasmine > $limitmine) {
                            $retorno = "OUTGIGAS";
                            $elerror = 1;
                        }
                    }
                }
            }
        }

        //COMPROBAR SI LO QUE SE SUBE ES MAYOR AL TAMAÑO RESTANTE DISPONIBLE
        if ($elerror == 0) {
            if ($_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {
                if ($limitmine >= 1) {
                    $laresta = $getgigasmine;
                    $espaciolibre = $limitmine - $laresta;
                    if ($archivosizegb > $espaciolibre) {
                        $retorno = "NOFREESPACE";
                        $elerror = 1;
                    }
                }
            }
        }

        //SI NO HAY ERRORES DEVOLVER OKGIGAS
        if ($elerror == 0) {
            $retorno = "OKGIGAS";
        }

        echo $retorno;
    }
}
