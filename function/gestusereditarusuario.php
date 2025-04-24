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

        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 && array_key_exists('psystemcreateuser', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemcreateuser'] == 1) {

            $archivo = "";
            $retorno = "";
            $elerror = 0;
            $sincambiarpass = 0;
            $sinpass = 0;
            $sinrepass = 0;
            $usuario = "";

            //OBTENER VARIABLES Y PASARLO A ARRAY

            if (!isset($_SESSION['SEGEDITARUSUARIO'])) {
                $elerror = 1;
            } else {
                if ($_SESSION['SEGEDITARUSUARIO'] == 0) {
                    $elerror = 1;
                }
            }

            if ($elerror == 0) {
                $usuario = $_SESSION['SEGEDITARUSUARIO']['usuario'];
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
                        //PROTECCION EVITAR QUE SUPERADMIN O ADMIN SEAN EDITADOS
                        if ($arrayobtenido[$i]['rango'] == 1 || $arrayobtenido[$i]['rango'] == 2) {
                            $nuevoarray[] = $arrayobtenido[$i];
                        } else if ($arrayobtenido[$i]['rango'] == 3) {

                            //SOLO CAMBIAR PASSWORD SI ESTA INTRODUCIDO EN LOS 2
                            if ($sinpass == 0 && $sinrepass == 0) {
                                $hashed = hash("sha3-512", test_input($_POST["elpass"]));
                                $arrayobtenido[$i]['hash'] = $hashed;
                            }

                            //GUARDAR TEMA WEB
                            if (isset($_POST['selectemaweb'])) {
                                if ($_POST['selectemaweb'] == 2) {
                                    $arrayobtenido[$i]['psystemconftemaweb'] = 2;
                                    if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$i]['usuario']) {
                                        $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 2;
                                    }
                                } else {
                                    $arrayobtenido[$i]['psystemconftemaweb'] = 1;
                                    if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$i]['usuario']) {
                                        $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 1;
                                    }
                                }
                            } else {
                                $arrayobtenido[$i]['psystemconftemaweb'] = 1;
                                if ($_SESSION['CONFIGUSER']['usuario'] == $arrayobtenido[$i]['usuario']) {
                                    $_SESSION['CONFIGUSER']['psystemconftemaweb'] = 1;
                                }
                            }

                            //PERMISOS STATUS
                            if (isset($_POST['pstatusstarserver'])) {
                                $arrayobtenido[$i]['pstatusstarserver'] = 1;
                            } else {
                                $arrayobtenido[$i]['pstatusstarserver'] = 0;
                            }

                            if (isset($_POST['pstatusrestartserver'])) {
                                $arrayobtenido[$i]['pstatusrestartserver'] = 1;
                            } else {
                                $arrayobtenido[$i]['pstatusrestartserver'] = 0;
                            }

                            if (isset($_POST['pstatusstopserver'])) {
                                $arrayobtenido[$i]['pstatusstopserver'] = 1;
                            } else {
                                $arrayobtenido[$i]['pstatusstopserver'] = 0;
                            }

                            if (isset($_POST['pstatuskillserver'])) {
                                $arrayobtenido[$i]['pstatuskillserver'] = 1;
                            } else {
                                $arrayobtenido[$i]['pstatuskillserver'] = 0;
                            }

                            //PERMISOS CONSOLA
                            if (isset($_POST['pconsolaread'])) {
                                $arrayobtenido[$i]['pconsolaread'] = 1;
                            } else {
                                $arrayobtenido[$i]['pconsolaread'] = 0;
                            }

                            if (isset($_POST['pconsolaenviar'])) {
                                $arrayobtenido[$i]['pconsolaenviar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pconsolaenviar'] = 0;
                            }

                            //PERMISOS CONFIG MINECRAFT
                            if (isset($_POST['pconfmine'])) {
                                $arrayobtenido[$i]['pconfmine'] = 1;
                            } else {
                                $arrayobtenido[$i]['pconfmine'] = 0;
                            }

                            //PERMISOS PROG TAREAS
                            if (isset($_POST['pprogtareas'])) {
                                $arrayobtenido[$i]['pprogtareas'] = 1;
                            } else {
                                $arrayobtenido[$i]['pprogtareas'] = 0;
                            }

                            if (isset($_POST['pprogtareascrear'])) {
                                $arrayobtenido[$i]['pprogtareascrear'] = 1;
                            } else {
                                $arrayobtenido[$i]['pprogtareascrear'] = 0;
                            }

                            if (isset($_POST['pprogtareaseditar'])) {
                                $arrayobtenido[$i]['pprogtareaseditar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pprogtareaseditar'] = 0;
                            }

                            if (isset($_POST['pprogtareasactdes'])) {
                                $arrayobtenido[$i]['pprogtareasactdes'] = 1;
                            } else {
                                $arrayobtenido[$i]['pprogtareasactdes'] = 0;
                            }

                            if (isset($_POST['pprogtareasborrar'])) {
                                $arrayobtenido[$i]['pprogtareasborrar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pprogtareasborrar'] = 0;
                            }

                            if (isset($_POST['pprogtareaslog'])) {
                                $arrayobtenido[$i]['pprogtareaslog'] = 1;
                            } else {
                                $arrayobtenido[$i]['pprogtareaslog'] = 0;
                            }

                            //SYSTEM CONFIG
                            if (isset($_POST['psystemconf'])) {
                                $arrayobtenido[$i]['psystemconf'] = 1;
                            } else {
                                $arrayobtenido[$i]['psystemconf'] = 0;
                            }

                            //PAGINA DESCARGAR SERVIDOR
                            if (isset($_POST['ppagedownserver'])) {
                                $arrayobtenido[$i]['ppagedownserver'] = 1;
                            } else {
                                $arrayobtenido[$i]['ppagedownserver'] = 0;
                            }

                            //DESCARGAR VANILLA
                            if (isset($_POST['ppagedownvanilla'])) {
                                $arrayobtenido[$i]['ppagedownvanilla'] = 1;
                            } else {
                                $arrayobtenido[$i]['ppagedownvanilla'] = 0;
                            }

                            //COMPILAR SPIGOT
                            if (isset($_POST['pcompilarspigot'])) {
                                $arrayobtenido[$i]['pcompilarspigot'] = 1;
                            } else {
                                $arrayobtenido[$i]['pcompilarspigot'] = 0;
                            }

                            //DESCARGAR PAPER
                            if (isset($_POST['ppagedownpaper'])) {
                                $arrayobtenido[$i]['ppagedownpaper'] = 1;
                            } else {
                                $arrayobtenido[$i]['ppagedownpaper'] = 0;
                            }

                            //DESCARGAR PURPUR
                            if (isset($_POST['ppagedownpurpur'])) {
                                $arrayobtenido[$i]['ppagedownpurpur'] = 1;
                            } else {
                                $arrayobtenido[$i]['ppagedownpurpur'] = 0;
                            }

                            //SUBIR SERVIDOR
                            if (isset($_POST['psubirservidor'])) {
                                $arrayobtenido[$i]['psubirservidor'] = 1;
                            } else {
                                $arrayobtenido[$i]['psubirservidor'] = 0;
                            }

                            //Backups
                            if (isset($_POST['pbackups'])) {
                                $arrayobtenido[$i]['pbackups'] = 1;
                            } else {
                                $arrayobtenido[$i]['pbackups'] = 0;
                            }

                            if (isset($_POST['pbackupscrear'])) {
                                $arrayobtenido[$i]['pbackupscrear'] = 1;
                            } else {
                                $arrayobtenido[$i]['pbackupscrear'] = 0;
                            }

                            if (isset($_POST['pbackupsdescargar'])) {
                                $arrayobtenido[$i]['pbackupsdescargar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pbackupsdescargar'] = 0;
                            }

                            if (isset($_POST['pbackupsrestaurar'])) {
                                $arrayobtenido[$i]['pbackupsrestaurar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pbackupsrestaurar'] = 0;
                            }

                            if (isset($_POST['pbackupsborrar'])) {
                                $arrayobtenido[$i]['pbackupsborrar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pbackupsborrar'] = 0;
                            }

                            if (isset($_POST['pbackupsdesrotar'])) {
                                $arrayobtenido[$i]['pbackupsdesrotar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pbackupsdesrotar'] = 0;
                            }

                            //Gestor Archivos
                            if (isset($_POST['pgestorarchivos'])) {
                                $arrayobtenido[$i]['pgestorarchivos'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivos'] = 0;
                            }

                            if (isset($_POST['pgestorarchivoscrearcarpeta'])) {
                                $arrayobtenido[$i]['pgestorarchivoscrearcarpeta'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivoscrearcarpeta'] = 0;
                            }

                            if (isset($_POST['pgestorarchivoscopiar'])) {
                                $arrayobtenido[$i]['pgestorarchivoscopiar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivoscopiar'] = 0;
                            }

                            if (isset($_POST['pgestorarchivosborrar'])) {
                                $arrayobtenido[$i]['pgestorarchivosborrar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivosborrar'] = 0;
                            }

                            if (isset($_POST['pgestorarchivosdescomprimir'])) {
                                $arrayobtenido[$i]['pgestorarchivosdescomprimir'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivosdescomprimir'] = 0;
                            }

                            if (isset($_POST['pgestorarchivoscomprimir'])) {
                                $arrayobtenido[$i]['pgestorarchivoscomprimir'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivoscomprimir'] = 0;
                            }

                            if (isset($_POST['pgestorarchivosdescargar'])) {
                                $arrayobtenido[$i]['pgestorarchivosdescargar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivosdescargar'] = 0;
                            }

                            if (isset($_POST['pgestorarchivoseditar'])) {
                                $arrayobtenido[$i]['pgestorarchivoseditar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivoseditar'] = 0;
                            }

                            if (isset($_POST['pgestorarchivosrenombrar'])) {
                                $arrayobtenido[$i]['pgestorarchivosrenombrar'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivosrenombrar'] = 0;
                            }

                            if (isset($_POST['pgestorarchivossubir'])) {
                                $arrayobtenido[$i]['pgestorarchivossubir'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivossubir'] = 0;
                            }

                            if (isset($_POST['pgestorarchivosexcludefiles'])) {
                                $arrayobtenido[$i]['pgestorarchivosexcludefiles'] = 1;
                            } else {
                                $arrayobtenido[$i]['pgestorarchivosexcludefiles'] = 0;
                            }

                            $nuevoarray[] = $arrayobtenido[$i];
                        }
                    }
                }

                //GUARDAR EN ARCHIVO
                $serialized = serialize($nuevoarray);
                file_put_contents($elarchivo, $serialized);
                $_SESSION['SEGEDITARUSUARIO'] = 0;
                $retorno = "OK";
            }
        }
    }
    echo $retorno;
}
