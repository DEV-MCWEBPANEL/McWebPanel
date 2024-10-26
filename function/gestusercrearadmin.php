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

        if ($_SESSION['CONFIGUSER']['rango'] == 1) {

            $archivo = "";
            $retorno = "";
            $elerror = 0;
            $test = 0;
            $permiso;

            //OBTENER VARIABLES Y PASARLO A ARRAY

            if (!isset($_POST['eluser'])) {
                $retorno = "nohayusuario";
                $elerror = 1;
            }

            if (!isset($_POST['elpass'])) {
                $retorno = "nohaypassword";
                $elerror = 1;
            }

            if (!isset($_POST['elrepass'])) {
                $retorno = "nohayrepass";
                $elerror = 1;
            }

            //COMPROBAR SI USUARIO ESTA VACIO
            if ($elerror == 0) {
                if (test_input($_POST['eluser']) == "") {
                    $retorno = "nohayusuario";
                    $elerror = 1;
                }else{
                    $usuariolen = test_input($_POST['eluser']);
                    if (strlen($usuariolen) > 255) {
                        $retorno = "nolenuser";
                        $elerror = 1;
                    }
                }
            }

            //COMPROBAR SI EL PASSWORD ESTA VACIO
            if ($elerror == 0) {
                if (test_input($_POST['elpass']) == "") {
                    $retorno = "nohaypassword";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI EL REPASS ESTA VACIO
            if ($elerror == 0) {
                if (test_input($_POST['elrepass']) == "") {
                    $retorno = "nohayrepass";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI LOS PASSWORS SON IGUALES
            if ($elerror == 0) {
                if (test_input($_POST['elpass']) != test_input($_POST['elrepass'])) {
                    $retorno = "passwordsdiferentes";
                    $elerror = 1;
                }
            }

            //COMPROBAR REQUISITOS DEL PASSWORD
            if ($elerror == 0) {

                $pwd = test_input($_POST['elpass']);

                if (strlen($pwd) < 16) {
                    $retorno = "nocumplereq";
                    $elerror = 1;
                }

                if (!preg_match("#[0-9]+#", $pwd)) {
                    $retorno = "nocumplereq";
                    $elerror = 1;
                }

                if (!preg_match("#[a-z]+#", $pwd)) {
                    $retorno = "nocumplereq";
                    $elerror = 1;
                }

                if (!preg_match("#[A-Z]+#", $pwd)) {
                    $retorno = "nocumplereq";
                    $elerror = 1;
                }

                if (!preg_match("#\W+#", $pwd)) {
                    if (!preg_match('#_+#', $pwd)) {
                        $retorno = "nocumplereq";
                        $elerror = 1;
                    }
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

            //COMPROBAR SI EL NICK NO ESTA REPETIDO
            if ($elerror == 0) {
                for ($i = 0; $i < count($arrayobtenido); $i++) {

                    if ($arrayobtenido[$i]['usuario'] == test_input($_POST['eluser'])) {
                        $retorno = "useryaexiste";
                        $elerror = 1;
                    }
                }
            }

            //GUARDAR ARRAY
            if ($elerror == 0) {
                $arrayobtenido[$elindice]['usuario'] = test_input($_POST["eluser"]);
                $hashed = hash("sha3-512", test_input($_POST["elpass"]));
                $arrayobtenido[$elindice]['hash'] = $hashed;
                $arrayobtenido[$elindice]['rango'] = 2;
                $arrayobtenido[$elindice]['estado'] = "activado";


                //GUARDAR PERMISOS CREAR USUARIOS
                if (isset($_POST['psystemcreateuser'])) {
                    if ($_POST['psystemcreateuser'] == 2) {
                        $arrayobtenido[$elindice]['psystemcreateuser'] = 1;
                    }
                }

                //GUARDAR TEMA WEB
                if (isset($_POST['selectemaweb'])) {
                    if ($_POST['selectemaweb'] == 2) {
                        $arrayobtenido[$elindice]['psystemconftemaweb'] = 2;
                        //APLICAR SI ES MISMO USUARIO
                        if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$elindice]['usuario']) {
                            $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 2;
                        }
                    } else {
                        $arrayobtenido[$elindice]['psystemconftemaweb'] = 1;
                        //APLICAR SI ES MISMO USUARIO
                        if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$elindice]['usuario']) {
                            $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 1;
                        }
                    }
                } else {
                    $arrayobtenido[$elindice]['psystemconftemaweb'] = 1;
                    //APLICAR SI ES MISMO USUARIO
                    if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$elindice]['usuario']) {
                        $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 1;
                    }
                }

                //SYSTEM CONFIG PUERTO
                if (isset($_POST['psystemconfpuerto'])) {
                    $arrayobtenido[$elindice]['psystemconfpuerto'] = 1;
                }

                //SYSTEM CONFIG MEMORIA
                if (isset($_POST['psystemconfmemoria'])) {
                    $arrayobtenido[$elindice]['psystemconfmemoria'] = 1;
                }

                //SYSTEM CONFIG TIPO
                if (isset($_POST['psystemconftipo'])) {
                    $arrayobtenido[$elindice]['psystemconftipo'] = 1;
                }

                //SYSTEM CONFIG SUBIDA
                if (isset($_POST['psystemconfsubida'])) {
                    $arrayobtenido[$elindice]['psystemconfsubida'] = 1;
                }

                //SYSTEM CONFIG NOMBRE
                if (isset($_POST['psystemconfnombre'])) {
                    $arrayobtenido[$elindice]['psystemconfnombre'] = 1;
                }

                //SYSTEM ZONA HORARIA
                if (isset($_POST['psystemconfzonahoraria'])) {
                    $arrayobtenido[$elindice]['psystemconfzonahoraria'] = 1;
                }

                //SYSTEM CONFIG PARAMETROS AVANZADOS
                if (isset($_POST['psystemconfavanzados'])) {
                    $arrayobtenido[$elindice]['psystemconfavanzados'] = 1;
                }

                //SYSTEM CONFIG SELECTOR JAVA
                if (isset($_POST['psystemconfjavaselect'])) {
                    $arrayobtenido[$elindice]['psystemconfjavaselect'] = 1;
                }

                //SYSTEM CONFIG LIMITE ALMACENAMIENTO
                if (isset($_POST['psystemconffoldersize'])) {
                    $arrayobtenido[$elindice]['psystemconffoldersize'] = 1;
                }

                //SYSTEM CONFIG LIMITE LINEAS CONSOLA
                if (isset($_POST['psystemconflinconsole'])) {
                    $arrayobtenido[$elindice]['psystemconflinconsole'] = 1;
                }

                //SYSTEM CONFIG LIMITE BUFFER CONSOLA
                if (isset($_POST['psystemconfbuffer'])) {
                    $arrayobtenido[$elindice]['psystemconfbuffer'] = 1;
                }

                //SYSTEM CONFIG TIPO CONSOLA
                if (isset($_POST['psystemconftypeconsole'])) {
                    $arrayobtenido[$elindice]['psystemconftypeconsole'] = 1;
                }

                //SYSTEM CONFIG OPCIONES BACKUPS
                if (isset($_POST['psystemconfbackup'])) {
                    $arrayobtenido[$elindice]['psystemconfbackup'] = 1;
                }

                //SYSTEM CONFIG INICIAR MINECRAFT AL ARRANCAR LINUX
                if (isset($_POST['psystemstartonboot'])) {
                    $arrayobtenido[$elindice]['psystemstartonboot'] = 1;
                }

                //SYSTEM CONFIG ARGUMENTOS JAVA
                if (isset($_POST['psystemcustomarg'])) {
                    $arrayobtenido[$elindice]['psystemcustomarg'] = 1;
                }

                //SYSTEM CONFIG IGNORAR RAM SISTEMA AL INICIAR MINECRAFT
                if (isset($_POST['psystemconfignoreramlimit'])) {
                    $arrayobtenido[$elindice]['psystemconfignoreramlimit'] = 1;
                }

                //GUARDAR EN ARCHIVO
                $serialized = serialize($arrayobtenido);
                file_put_contents($elarchivo, $serialized);
                $retorno = "OK";
            }
        }
    }
    echo $retorno;
}
