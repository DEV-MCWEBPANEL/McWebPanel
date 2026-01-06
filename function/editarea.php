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

$retorno = "";
$elerror = 0;
$rutaarchivo = "";
$elarchivo = "";

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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pprogtareaseditar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pprogtareaseditar'] == 1) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['action']) && $_POST['action'] === 'submit') {

                if ($elerror == 0) {
                    if (!isset($_SESSION['SETEDITARTAREA'])) {
                        $retorno = "errnosession";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    if (empty($_POST["nombretarea"])) {
                        $retorno = "errnombre";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    if (empty($_POST["laaccion"])) {
                        $retorno = "errlaaccion";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    if (empty($_POST["mes"])) {
                        $retorno = "errmes";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    if (empty($_POST["semana"])) {
                        $retorno = "errsemana";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    if (empty($_POST["hora"])) {
                        $retorno = "errhora";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    if (empty($_POST["minuto"])) {
                        $retorno = "errminuto";
                        $elerror = 1;
                    }
                }

                if ($elerror == 0) {
                    $getaccion = test_input($_POST["laaccion"]);
                    if ($getaccion == "acc4") {
                        if (empty($_POST["elcomando"])) {
                            $retorno = "nocomando";
                            $elerror = 1;
                        } else {
                            $getcommando = $_POST["elcomando"];

                            if ($elerror == 0) {
                                if (strlen($getcommando) > 4096) {
                                    $elerror = 1;
                                    $retorno = "lenmax";
                                }
                            }

                            if ($elerror == 0) {
                                $buscar = preg_match('/[\^][a-zA-Z]/', $getcommando);
                                if ($buscar >= 1) {
                                    $retorno = "badchars";
                                    $elerror = 1;
                                }
                            }
                        }
                    } else {
                        $getcommando = "";
                    }
                }

                if ($elerror == 0) {
                    //OBTENER Y GENERAR ARRAY MES
                    $anadido = 0;

                    for ($i = 0; $i < 12; $i++) {
                        for ($b = 0; $b < count($_POST["mes"]); $b++) {
                            if ($_POST["mes"][$b] == (strval($i + 1))) {
                                $aresultmes['tmes'][$i] = strval($i + 1);
                                $anadido = 1;
                            }
                        }

                        if ($anadido == 0) {
                            $aresultmes['tmes'][$i] = '-1';
                        }

                        $anadido = 0;
                    }

                    //OBTENER Y GENERAR ARRAY SEMANA
                    $anadido = 0;

                    for ($i = 0; $i < 7; $i++) {
                        for ($b = 0; $b < count($_POST["semana"]); $b++) {
                            if ($_POST["semana"][$b] == (strval($i + 1))) {
                                $aresultsemana['tsemana'][$i] = strval($i + 1);
                                $anadido = 1;
                            }
                        }

                        if ($anadido == 0) {
                            $aresultsemana['tsemana'][$i] = '-1';
                        }

                        $anadido = 0;
                    }


                    //OBTENER Y GENERAR ARRAY HORA
                    $anadido = 0;

                    for ($i = 0; $i < 24; $i++) {
                        for ($b = 0; $b < count($_POST["hora"]); $b++) {
                            if ($_POST["hora"][$b] == strval($i)) {
                                $aresulthora['thora'][$i] = strval($i);
                                $anadido = 1;
                            }
                        }

                        if ($anadido == 0) {
                            $aresulthora['thora'][$i] = '-1';
                        }

                        $anadido = 0;
                    }


                    //OBTENER Y GENERAR ARRAY MINUTO
                    $anadido = 0;
                    $losminutos = "";

                    for ($i = 0; $i < 60; $i++) {
                        for ($b = 0; $b < count($_POST["minuto"]); $b++) {
                            if ($i >= 0 && $i <= 9) {
                                $losminutos = "0" . strval($i);
                            } else {
                                $losminutos = strval($i);
                            }
                            if ($_POST["minuto"][$b] == strval($losminutos)) {
                                $aresultminuto['tminuto'][$i] = strval($losminutos);
                                $anadido = 1;
                            }
                        }

                        if ($anadido == 0) {
                            $aresultminuto['tminuto'][$i] = '-1';
                        }

                        $anadido = 0;
                    }
                }

                //OBTENER RUTA CONFIG
                $rutaarchivo = dirname(getcwd()) . PHP_EOL;
                $rutaarchivo = trim($rutaarchivo);
                $rutaarchivo .= "/config";

                $elarchivo = $rutaarchivo;
                $elarchivo .= "/array.json";

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

                if ($elerror == 0) {
                    clearstatcache();
                    if (file_exists($elarchivo)) {

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

                        if ($elerror == 0) {

                            $getarray = file_get_contents($elarchivo);
                            $arrayobtenido = unserialize($getarray);
                            $elindice = count($arrayobtenido);

                            for ($x = 0; $x < $elindice; $x++) {
                                if ($_SESSION['SETEDITARTAREA'] == $arrayobtenido[$x]['id']) {
                                    $arrayobtenido[$x]['nombre'] = test_input($_POST["nombretarea"]);
                                    $arrayobtenido[$x]['accion'] = test_input($_POST["laaccion"]);
                                    $arrayobtenido[$x]['comando'] = test_input($getcommando);

                                    for ($i = 0; $i < 12; $i++) {
                                        $arrayobtenido[$x][$i]["mes"] = $aresultmes['tmes'][$i];
                                    }

                                    for ($i = 0; $i < 7; $i++) {
                                        $arrayobtenido[$x][$i]["semana"] = $aresultsemana['tsemana'][$i];
                                    }

                                    for ($i = 0; $i < 24; $i++) {
                                        $arrayobtenido[$x][$i]["hora"] = $aresulthora['thora'][$i];
                                    }

                                    for ($i = 0; $i < 60; $i++) {
                                        $arrayobtenido[$x][$i]["minuto"] = $aresultminuto['tminuto'][$i];
                                    }
                                }
                            }

                            $serialized = serialize($arrayobtenido);
                            file_put_contents($elarchivo, $serialized);
                            $retorno = "OK";
                        }
                    } else {
                        $retorno = "nofilexist";
                    }
                }

                echo $retorno;
            }
        }
    }
}
