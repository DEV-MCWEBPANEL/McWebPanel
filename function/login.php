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
//confopciones cargado mas abajo

function test_input($data)
{
    if (isset($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

function generarkey()
{
    $secretkey = "";

    for ($a = 1; $a <= 32; $a++) {
        $secretkey .= strval(random_int(0, 9));
    }

    return hash("sha3-512", $secretkey);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //INICIAR VARIABLES
    $retorno = "";
    $elerror = 0;
    $encontraruser = 0;
    $indexuser = "";
    $getconfuser = "";
    $getconfpass = "";
    $elusuario = "";
    $elpassword = "";
    $lakey = "";
    $dirconfig = "";

    //SESSION DE REINTENTOS
    if (!isset($_SESSION['count'])) {
        $_SESSION['count'] = 0;
    } else {
        $_SESSION['count']++;
        if ($_SESSION['count'] > 2) {
            $retorno = "maxintentos";
            $elerror = 1;
        }
    }

    //COMPROBAR SI HAY DATOS
    if ($elerror == 0) {

        if (!isset($_POST['eluser']) || !isset($_POST['elpass'])) {
            $retorno = "faltandatos";
            $elerror = 1;
        }
    }

    //ENCONTRAR USUARIO
    if ($elerror == 0) {
        $dirconfig = dirname(getcwd()) . PHP_EOL;
        $dirconfig = trim($dirconfig);
        $dirconfig .= "/config/confuser.json";

        //OBTENER ARRAY DE USUARIOS
        $getarray = file_get_contents($dirconfig);
        $arrayobtenido = unserialize($getarray);

        //COGER INPUTS
        $elusuario = test_input($_POST["eluser"]);
        $elpassword = test_input($_POST["elpass"]);

        //GENERAR HASH
        $hashed = hash("sha3-512", $elpassword);

        //RECORRER USUARIOS DEL ARRAY
        for ($i = 0; $i < count($arrayobtenido); $i++) {
            $getconfuser = $arrayobtenido[$i]['usuario'];
            $getconfpass = $arrayobtenido[$i]['hash'];

            //SI EL USUARIO CONCUERDA
            if ($elusuario == $getconfuser && $hashed == $getconfpass) {
                $encontraruser = 1;
                $indexuser = $i;
            }
        }

        if ($encontraruser == 0) {
            if($_SESSION['count'] == 0){
                $retorno = "novaliduser1";
            }elseif($_SESSION['count'] == 1){
                $retorno = "novaliduser2";
            }elseif($_SESSION['count'] == 2){
                $retorno = "maxintentos";
            }
            $elerror = 1;
        }
    }

    //COMPROBAR MANTENIMIENTO
    if ($elerror == 0) {
        require_once "../config/confopciones.php";
        $recmantenimiento = CONFIGMANTENIMIENTO;
        if ($recmantenimiento == "Activado") {
            if ($arrayobtenido[$indexuser]['rango'] != 1) {
                $retorno = "mantenimiento";
                $elerror = 1;
            }
        }
    }

    //COMPROBAR SI EL USUARIO ESTA ACTIVO
    if ($elerror == 0) {
        if ($arrayobtenido[$indexuser]['estado'] == "desactivado") {
            $retorno = "userdesactivado";
            $elerror = 1;
        }
    }

    //LOGIN
    if ($elerror == 0) {
        //REGENERAR SESSION
        session_regenerate_id();
        $getconflakey = CONFIGSESSIONKEY;

        $lakey = generarkey();

        $_SESSION['KEYSECRETA'] = $lakey;
        $_SESSION['VALIDADO'] = $lakey;
        $_SESSION['IDENTIFICARSESSION'] = $getconflakey;
        $_SESSION['CONFIGUSER'] = $arrayobtenido[$indexuser];

        unset($lakey);

        $retorno = "gotostatus";
    }

    echo $retorno;
}
