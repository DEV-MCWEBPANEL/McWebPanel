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

            $retorno = "";
            $elerror = 0;
            $sinoldpass = 0;
            $sinpass = 0;
            $sinrepass = 0;
            $usuario = "";
            $oldpasscheck = 0;

            //OBTENER VARIABLES Y PASARLO A ARRAY

            if (!isset($_SESSION['SEGEDITARSUPER'])) {
                $elerror = 1;
            } else {
                if ($_SESSION['SEGEDITARSUPER'] == 0) {
                    $elerror = 1;
                }
            }

            if ($elerror == 0) {
                $usuario = $_SESSION['SEGEDITARSUPER']['usuario'];
            }

            if ($_SESSION['SEGEDITARSUPER']['usuario'] == $_SESSION['CONFIGUSER']['usuario']) {
                if (test_input($_POST['eloldpass']) == "") {
                    $sinoldpass = 1;
                }
            }

            if (test_input($_POST['elpass']) == "") {
                $sinpass = 1;
            }

            if (test_input($_POST['elrepass']) == "") {
                $sinrepass = 1;
            }

            //COMPROBAR SI USUARIO ESTA VACIO
            if ($elerror == 0) {
                if ($usuario == "") {
                    $retorno = "nohayusuario";
                    $elerror = 1;
                }
            }

            //COMPROBAR SI LOS PASSWORS SON IGUALES
            if ($elerror == 0) {
                if ($sinpass == 0 || $sinrepass == 0) {
                    if (test_input($_POST['elpass']) != test_input($_POST['elrepass'])) {
                        $retorno = "passwordsdiferentes";
                        $elerror = 1;
                    }
                }
            }

            //COMPROBAR REQUISITOS DEL PASSWORD
            if ($elerror == 0) {
                if ($sinpass == 0 || $sinrepass == 0) {
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

            //GUARDAR ARRAY
            if ($elerror == 0) {

                //RECORRER ARRAY
                for ($i = 0; $i < count($arrayobtenido); $i++) {

                    if ($arrayobtenido[$i]['usuario'] != $usuario) {
                        $nuevoarray[] = $arrayobtenido[$i];
                    } else {

                        //EVITAR QUE ADMINISTRADOR EDITE SUPERADMIN
                        if ($elerror == 0) {
                            if ($_SESSION['CONFIGUSER']['rango'] == 2 && $_SESSION['SEGEDITARSUPER']['rango'] == 1) {
                                $elerror = 1;
                            }
                        }

                        //EVITAR QUE UN ADMINISTRADOR EDITE UN ADMINISTRADOR DIFERENTE
                        if ($elerror == 0) {
                            if ($_SESSION['CONFIGUSER']['rango'] == 2 && $_SESSION['SEGEDITARSUPER']['rango'] == 2 && $_SESSION['CONFIGUSER']['usuario'] != $_SESSION['SEGEDITARSUPER']['usuario']) {
                                $elerror = 1;
                            }
                        }

                        //COMPROBAR EL OLDPASS CON EL ACTUAL
                        if ($elerror == 0) {
                            if ($_SESSION['SEGEDITARSUPER']['usuario'] == $_SESSION['CONFIGUSER']['usuario']) {
                                if ($sinoldpass == 0) {
                                    $oldhashed = hash("sha3-512", test_input($_POST["eloldpass"]));

                                    if ($arrayobtenido[$i]['hash'] == $oldhashed) {
                                        $oldpasscheck = 1;
                                    } else {
                                        $elerror = 1;
                                        $retorno = "oldpasserror";
                                    }
                                }
                            }
                        }

                        //DAR ACCESO A SUPERADMIN PARA CAMBIAR EL PASS
                        if ($elerror == 0) {
                            if ($_SESSION['CONFIGUSER']['rango'] == 1 && $sinpass == 0 && $sinrepass == 0 && $oldpasscheck == 0) {
                                $oldpasscheck = 1;
                            }
                        }


                        if ($elerror == 0) {
                            //SOLO CAMBIAR PASSWORD SI ESTA INTRODUCIDO EN LOS 2
                            if ($sinpass == 0 && $sinrepass == 0 && $sinoldpass == 0 && $oldpasscheck == 1) {
                                $hashed = hash("sha3-512", test_input($_POST["elpass"]));
                                $arrayobtenido[$i]['hash'] = $hashed;
                            }

                            //GUARDAR PERMISOS CREAR USUARIOS SOLO ADMINS
                            if ($_SESSION['CONFIGUSER']['rango'] == 1 && $_SESSION['SEGEDITARSUPER']['rango'] == 2) {
                                if (isset($_POST['psystemcreateuser'])) {
                                    if ($_POST['psystemcreateuser'] == 2) {
                                        $arrayobtenido[$i]['psystemcreateuser'] = 1;
                                    } else {
                                        $arrayobtenido[$i]['psystemcreateuser'] = 0;
                                    }
                                } else {
                                    $arrayobtenido[$i]['psystemcreateuser'] = 0;
                                }
                            }

                            //GUARDAR TEMA WEB
                            if (isset($_POST['selectemaweb'])) {
                                if ($_POST['selectemaweb'] == 2) {
                                    $arrayobtenido[$i]['psystemconftemaweb'] = 2;
                                    //APLICAR SI ES MISMO USUARIO
                                    if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$i]['usuario']) {
                                        $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 2;
                                    }
                                } else {
                                    $arrayobtenido[$i]['psystemconftemaweb'] = 1;
                                    //APLICAR SI ES MISMO USUARIO
                                    if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$i]['usuario']) {
                                        $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 1;
                                    }
                                }
                            } else {
                                $arrayobtenido[$i]['psystemconftemaweb'] = 1;
                                //APLICAR SI ES MISMO USUARIO
                                if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$i]['usuario']) {
                                    $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 1;
                                }
                            }

                            //MODIFICAR PERMISOS SOLO ADMINS
                            if ($_SESSION['SEGEDITARSUPER']['rango'] == 2) {

                                //SYSTEM CONFIG PUERTO
                                if (isset($_POST['psystemconfpuerto'])) {
                                    $arrayobtenido[$i]['psystemconfpuerto'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfpuerto'] = 0;
                                }

                                //SYSTEM CONFIG MEMORIA
                                if (isset($_POST['psystemconfmemoria'])) {
                                    $arrayobtenido[$i]['psystemconfmemoria'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfmemoria'] = 0;
                                }

                                //SYSTEM CONFIG TIPO
                                if (isset($_POST['psystemconftipo'])) {
                                    $arrayobtenido[$i]['psystemconftipo'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconftipo'] = 0;
                                }

                                //SYSTEM CONFIG SUBIDA
                                if (isset($_POST['psystemconfsubida'])) {
                                    $arrayobtenido[$i]['psystemconfsubida'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfsubida'] = 0;
                                }

                                //SYSTEM CONFIG NOMBRE
                                if (isset($_POST['psystemconfnombre'])) {
                                    $arrayobtenido[$i]['psystemconfnombre'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfnombre'] = 0;
                                }

                                //SYSTEM CONFIG ZONA HORARIA
                                if (isset($_POST['psystemconfzonahoraria'])) {
                                    $arrayobtenido[$i]['psystemconfzonahoraria'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfzonahoraria'] = 0;
                                }

                                //SYSTEM CONFIG PARAMETROS AVANZADOS
                                if (isset($_POST['psystemconfavanzados'])) {
                                    $arrayobtenido[$i]['psystemconfavanzados'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfavanzados'] = 0;
                                }

                                //SYSTEM CONFIG SELECTOR JAVA
                                if (isset($_POST['psystemconfjavaselect'])) {
                                    $arrayobtenido[$i]['psystemconfjavaselect'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfjavaselect'] = 0;
                                }

                                //SYSTEM CONFIG LIMITE ALMACENAMIENTO
                                if (isset($_POST['psystemconffoldersize'])) {
                                    $arrayobtenido[$i]['psystemconffoldersize'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconffoldersize'] = 0;
                                }

                                //SYSTEM CONFIG LIMITE LINEAS CONSOLA
                                if (isset($_POST['psystemconflinconsole'])) {
                                    $arrayobtenido[$i]['psystemconflinconsole'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconflinconsole'] = 0;
                                }

                                //SYSTEM CONFIG LIMITE BUFFER CONSOLA
                                if (isset($_POST['psystemconfbuffer'])) {
                                    $arrayobtenido[$i]['psystemconfbuffer'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfbuffer'] = 0;
                                }

                                //SYSTEM CONFIG TIPO CONSOLA
                                if (isset($_POST['psystemconftypeconsole'])) {
                                    $arrayobtenido[$i]['psystemconftypeconsole'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconftypeconsole'] = 0;
                                }

                                //SYSTEM CONFIG OPCIONES BACKUPS
                                if (isset($_POST['psystemconfbackup'])) {
                                    $arrayobtenido[$i]['psystemconfbackup'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfbackup'] = 0;
                                }

                                //SYSTEM CONFIG INICIAR MINECRAFT AL ARRANCAR LINUX
                                if (isset($_POST['psystemstartonboot'])) {
                                    $arrayobtenido[$i]['psystemstartonboot'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemstartonboot'] = 0;
                                }

                                //SYSTEM CONFIG ARGUMENTOS JAVA
                                if (isset($_POST['psystemcustomarg'])) {
                                    $arrayobtenido[$i]['psystemcustomarg'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemcustomarg'] = 0;
                                }

                                //SYSTEM CONFIG IGNORAR RAM SISTEMA AL INICIAR MINECRAFT
                                if (isset($_POST['psystemconfignoreramlimit'])) {
                                    $arrayobtenido[$i]['psystemconfignoreramlimit'] = 1;
                                } else {
                                    $arrayobtenido[$i]['psystemconfignoreramlimit'] = 0;
                                }
                            }

                            $nuevoarray[] = $arrayobtenido[$i];
                        }
                    }
                }

                //GUARDAR EN ARCHIVO
                if ($elerror == 0) {
                    $serialized = serialize($nuevoarray);
                    file_put_contents($elarchivo, $serialized);
                    $_SESSION['SEGEDITARSUPER'] = 0;
                    $retorno = "OK";
                }
            }
        }
    }
    echo $retorno;
}
