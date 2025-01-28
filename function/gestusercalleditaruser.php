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

    //COMPROBAR SI ES EL SUPERADMIN O ADMIN
    if (array_key_exists('rango', $_SESSION['CONFIGUSER'])) {

        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2) {

            $encontrado = 0;
            $essuper = 0;
            $esadmin = 0;
            $elerror = 0;

            $usuario = test_input($_POST['action']);

            //COMPROBAR SI USUARIO ESTA VACIO
            if ($elerror == 0) {
                if ($usuario == "") {
                    $retorno = "nohayusuario";
                    $elerror = 1;
                }
            }

            //RUTAS AL ARCHIVO
            if ($elerror == 0) {
                $rutaarchivo = dirname(getcwd()) . PHP_EOL;
                $rutaarchivo = trim($rutaarchivo);
                $rutaarchivo .= "/config";

                $elarchivo = $rutaarchivo;
                $elarchivo .= "/confuser.json";
            }


            //COMPROBAR SI EXISTE CARPETA CONFIG
            if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($rutaarchivo)) {
                    $retorno = "errarchnoconfig";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI CONFIG TIENE PERMISOS DE LECTURA
            if ($elerror == 0) {
                clearstatcache();
                if (!is_readable($rutaarchivo)) {
                    $retorno = "errconfignoread";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI CONFIG TIENE PERMISOS DE ESCRITURA
            if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($rutaarchivo)) {
                    $retorno = "errconfignowrite";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI EXISTE EL JSON
            if ($elerror == 0) {
                clearstatcache();
                if (!file_exists($elarchivo)) {
                    $retorno = "errjsonnoexist";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI SE PUEDE LEER EL JSON
            if ($elerror == 0) {
                clearstatcache();
                if (!is_readable($elarchivo)) {
                    $retorno = "errjsonnoread";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI SE PUEDE ESCRIBIR EL JSON
            if ($elerror == 0) {
                clearstatcache();
                if (!is_writable($elarchivo)) {
                    $retorno = "errjsonnowrite";
                    $elerror = 1;
                }
            }


            //CARGAR ARRAY
            if ($elerror == 0) {
                $getarray = file_get_contents($elarchivo);
                $arrayobtenido = unserialize($getarray);
                $elindice = count($arrayobtenido);
            }

            //COMPROBAR SI EL USUARIO EXISTA
            if ($elerror == 0) {
                for ($i = 0; $i < count($arrayobtenido); $i++) {

                    if ($arrayobtenido[$i]['usuario'] == $usuario) {
                        $encontrado = 1;

                        if ($arrayobtenido[$i]['rango'] == 1 || $arrayobtenido[$i]['rango'] == 2) {
                            $essuper = 1;
                            $arrayuser = $arrayobtenido[$i];
                        }

                        if ($arrayobtenido[$i]['rango'] == 3) {
                            $esadmin = 1;
                            $arrayuser = $arrayobtenido[$i];
                        }

                        //EVITAR QUE UN ADMINISTRADOR EDITE UN SUPERADMIN
                        if ($_SESSION['CONFIGUSER']['rango'] == 2 && $arrayobtenido[$i]['rango'] == 1) {
                            $essuper = 0;
                            $arrayuser = "";
                        }

                        //EVITAR QUE UN ADMINISTRADOR EDITE OTRO ADMINISTRADOR
                        if ($_SESSION['CONFIGUSER']['rango'] == 2 && $arrayobtenido[$i]['rango'] == 2 && $_SESSION['CONFIGUSER']['usuario'] != $arrayobtenido[$i]['usuario']) {
                            $essuper = 0;
                            $arrayuser = "";
                        }
                    }
                }
            }

            //COMPROBAR SI EXISTE EL USUARIO
            if ($elerror == 0) {
                if ($encontrado == 0) {
                    $retorno = "usernoexiste";
                    $elerror = 1;
                }
            }

            //GUARDAR SESSION
            if ($elerror == 0) {
                if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2) {
                    if ($essuper == 1) {
                        $retorno = "OKSUPER";
                        $_SESSION['EDITARSUPER'] = $arrayuser;
                    } elseif ($esadmin == 1) {
                        $retorno = "OKADMIN";
                        $_SESSION['EDITARUSUARIO'] = $arrayuser;
                    }
                }
            }
        }
    }
    echo $retorno;
}
