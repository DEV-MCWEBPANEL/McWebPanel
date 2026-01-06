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

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
    $_SESSION['VALIDADO'] = "NO";
    $_SESSION['KEYSECRETA'] = "0";
}

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pstatuskillserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pstatuskillserver'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {

            $retorno = "";
            $elerror = 0;

            //MATAR SCREEN DEAD / ZOMBIS ANTES DE APAGAR
            $elcomando = "";
            $elcomando = "screen -wipe";
            exec($elcomando);

            //OBTENER PID SABER SI SCREEN ESTA EN EJECUCION
            $elcomando = "";
            $elnombrescreen = CONFIGDIRECTORIO;
            $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
            $elpid = shell_exec($elcomando);

            //SI ESTA EN EJECUCION ENVIAR COMANDO MATAR SESSION
            if (!$elpid == "") {

                //OBTENER PID JAVA
                $tipserver = trim(exec('whoami'));
                $elpid2 = "ps au | grep '" . $tipserver . "' | grep '" . $elnombrescreen . "' | gawk '{print $2}'";
                $elpid2 = shell_exec($elpid2);
                if ($elpid2 != "") {
                    $elpid2 = trim($elpid2);

                    //FORZAR SIGKILL
                    $elcomando = "kill -9 " . $elpid2;
                    $elcomando = trim($elcomando);
                    exec($elcomando);

                    $retorno = "ok";
                } else {
                    $retorno = "killnooutput";
                }
            }
            echo $retorno;
        }
    }
}
