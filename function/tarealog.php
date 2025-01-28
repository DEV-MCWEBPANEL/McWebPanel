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

$retorno = "";
$elerror = 0;

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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pprogtareaslog', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pprogtareaslog'] == 1) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['action']) && !empty($_POST['action'])) {

                $elnombredirectorio = CONFIGDIRECTORIO;
                $retorno = "";

                $rutaarchivo = dirname(getcwd()) . PHP_EOL;
                $rutaarchivo = trim($rutaarchivo);
                $rutaarchivo .= "/cron/cronstat.log";

                $laaccion = test_input($_POST['action']);

                if ($laaccion == "getlog") {
                    clearstatcache();
                    if (file_exists($rutaarchivo)) {
                        //COMPROBAR SI SE PUEDE LEER
                        clearstatcache();
                        if (is_readable($rutaarchivo)) {
                            $retorno = test_input(file_get_contents($rutaarchivo));
                        }
                    }
                } elseif ($laaccion == "borralog") {
                    clearstatcache();
                    if (file_exists($rutaarchivo)) {
                        if (is_writable($rutaarchivo)) {
                            unlink($rutaarchivo);
                            $retorno = "ok";
                        } else {
                            $retorno = "nowritelog";
                        }
                    }
                }

                echo $retorno;
            }
        }
    }
}
