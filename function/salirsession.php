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

$retorno = "";

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
    $_SESSION = array();
    session_destroy();
    $retorno = "SALIR";
} else {
    if ($_SESSION['VALIDADO'] == "NO" && $_SESSION['KEYSECRETA'] == "0") {
        $_SESSION = array();
        session_destroy();
        $retorno = "SALIR";
    } else {
        //MODO MANTENIMIENTO
        if ($_SESSION['CONFIGUSER']['rango'] != 1) {
            $recmantenimiento = CONFIGMANTENIMIENTO;

            if ($recmantenimiento == "Activado") {
                $_SESSION = array();
                session_destroy();
                $retorno = "SALIR";
            }
        }
    }
}

echo $retorno;
