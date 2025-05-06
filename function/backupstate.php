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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pbackups', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pbackups'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {

            if (!isset($_SESSION['BACKUPSTATUS'])) {
                $_SESSION['BACKUPSTATUS'] = 0;
            }

            $retorno = "";
            $reccarpmine = CONFIGDIRECTORIO;
            $elerror = 0;
            $test = 0;

            $archivo = test_input($_POST['action']);
            $archivo = trim($archivo);

            //OBTENER RUTA RAIZ
            $dirraiz = dirname(getcwd()) . PHP_EOL;
            $dirraiz = trim($dirraiz);

            //OBTENER IDENFIFICADOR SCREEN
            $nombrescreen = $dirraiz . "/losbackups";
            $nombrescreen = str_replace("/", "", $nombrescreen);

            $procesorestore = $dirraiz . "/restaurar";
            $procesorestore = str_replace("/", "", $procesorestore);

            //VER SI HAY UN PROCESO YA EN BACKUP
            $elcomando = "screen -ls | gawk '/\." . $nombrescreen . "\t/ {print strtonum($1)'}";
            $elpid = shell_exec($elcomando);

            //VER SI HAY UN PROCESO YA EN RESTAURAR
            $elcomando = "screen -ls | gawk '/\." . $procesorestore . "\t/ {print strtonum($1)'}";
            $elpid2 = shell_exec($elcomando);

            //SELECTOR TIPO PROCESO
            if ($archivo == "estadobackup") {

                if ($elpid != "") {
                    $retorno = "ON";
                    $_SESSION['BACKUPSTATUS'] = 1;
                }

                if ($elpi2 != "") {
                    $retorno = "ON";
                    $_SESSION['BACKUPSTATUS'] = 1;
                }

                if ($retorno == "") {
                    $retorno = "OFF";
                    if ($_SESSION['BACKUPSTATUS'] == 1) {
                        $retorno = "REFRESH";
                        $_SESSION['BACKUPSTATUS'] = 0;
                    }
                }
            }
        }
        echo $retorno;
    }
}
