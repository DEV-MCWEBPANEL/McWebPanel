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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || array_key_exists('psystemconfbuffer', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconfbuffer'] == 1) {

        if (isset($_POST['action']) && !empty($_POST['action'])) {
            $retorno = "";
            $elerror = 0;

            $carpraiz = dirname(getcwd()) . PHP_EOL;
            $carpraiz = trim($carpraiz);

            $carpconfig = $carpraiz . "/config";
            $archbuffer = $carpconfig . "/buffer.json";

            //VERIFICAR LECTURA CARPETA CONFIG
            if ($elerror == 0) {
                clearstatcache();
                if (!is_readable($carpconfig)) {
                    //$retorno = "noreadcarpconfig";
                    $elerror = 1;
                }
            }

            //VERIFICAR LECTURA BUFFER.JSON
            if ($elerror == 0) {
                clearstatcache();
                if (!is_readable($archbuffer)) {
                    //$retorno = "noreadbufferjson";
                    $elerror = 1;
                }
            }

            if ($elerror == 0) {

                if (isset($_SESSION['BUFFER'])) {
                    if (defined('CONFIGBUFFERLIMIT')) {
                        $laaction = test_input($_POST['action']);

                        $bufflimite = CONFIGBUFFERLIMIT;

                        if ($bufflimite >= 1) {

                            $getarray = file_get_contents($archbuffer);
                            $arrayobtenido = unserialize($getarray);

                            $arrayobtenido = array_reverse($arrayobtenido);

                            $elindice = count($arrayobtenido);
                            $indexbuffer = $_SESSION['BUFFER'];

                            if ($laaction == "bufferarriba") {
                                if ($indexbuffer == -1) {
                                    $indexbuffer = 0;
                                    $retorno = stripslashes($arrayobtenido[$indexbuffer]['comando']);
                                    $_SESSION['BUFFER'] = $indexbuffer;
                                } else {
                                    $indexbuffer++;
                                    if ($indexbuffer < $bufflimite) {
                                        if ($indexbuffer < $elindice) {
                                            $retorno = stripslashes($arrayobtenido[$indexbuffer]['comando']);
                                            $_SESSION['BUFFER'] = $indexbuffer;
                                        } else {
                                            $indexbuffer--;
                                            $retorno = stripslashes($arrayobtenido[$indexbuffer]['comando']);
                                        }
                                    } else {
                                        $indexbuffer--;
                                        $retorno = stripslashes($arrayobtenido[$indexbuffer]['comando']);
                                    }
                                }
                            } elseif ($laaction == "bufferabajo") {
                                if ($indexbuffer == -1) {
                                    $retorno = "";
                                } else {
                                    if ($indexbuffer <= 0) {
                                        if ($indexbuffer == 0) {
                                            $_SESSION['BUFFER'] = -1;
                                            $retorno = "";
                                        } else {
                                            $retorno = stripslashes($arrayobtenido[$indexbuffer]['comando']);
                                        }
                                    } else {
                                        if ($indexbuffer >= 0) {
                                            $indexbuffer--;
                                            $_SESSION['BUFFER'] = $indexbuffer;
                                        }
                                        $retorno = stripslashes($arrayobtenido[$indexbuffer]['comando']);
                                        if ($indexbuffer == 0) {
                                            $_SESSION['BUFFER'] = -1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo $retorno;
        }
    }
}
