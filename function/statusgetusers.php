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

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
    $_SESSION['VALIDADO'] = "NO";
    $_SESSION['KEYSECRETA'] = "0";
}

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

    if (isset($_POST['action']) && !empty($_POST['action'])) {

        //DECLARAR VARIABLES
        $elpid = "";
        $retorno = "";
        $valor3 = "";
        $losjugadores = "";
        $okinit = 0;

        //OBTENER PID SABER SI ESTA EN EJECUCION
        $elcomando = "";
        $elnombrescreen = CONFIGDIRECTORIO;
        $elcomando = "screen -ls | gawk '/\." . $elnombrescreen . "\t/ {print strtonum($1)'}";
        $elpid = shell_exec($elcomando);


        if ($elpid == "") {
            $valor3 = "Apagado";
        } else {
            //OBTENER RUTA LOG

            $reccarpmine = CONFIGDIRECTORIO;

            $larutascrrenlog = dirname(getcwd()) . PHP_EOL;
            $larutascrrenlog = trim($larutascrrenlog);
            $larutascrrenlog .= "/" . $reccarpmine . "/logs/screen.log";

            //COMPROBAR QUE EXISTA LOG
            clearstatcache();
            if (file_exists($larutascrrenlog)) {
                //COMPROBAR QUE EL LOG SE PUEDA LEER
                if (is_readable($larutascrrenlog)) {
                    //OBTENER LOG
                    $gestor = @fopen($larutascrrenlog, "r");
                    if ($gestor) {
                        while (($bufer = fgets($gestor, 4096)) !== false) {
                            $buscadone = strrpos($bufer, "Done (");
                            if ($buscadone != "") {
                                $okinit = 1;
                            }
                        }
                        if (!feof($gestor)) {
                            echo "Error: fallo inesperado de fgets()\n";
                        }
                        fclose($gestor);
                    }

                    if ($okinit == 1) {

                        //OBTENER JUGADORES ONLINE
                        $recpuerto = CONFIGPUERTO;

                        $MQ_SERVER_ADDR = "localhost";
                        $MQ_SERVER_PORT = $recpuerto;
                        $MQ_TIMEOUT = 1;

                        require __DIR__ . '/MinecraftPing.php';
                        require __DIR__ . '/MinecraftPingException.php';

                        $Info = false;
                        $Query = null;
                        $maxplayers = 0;
                        $playersin = 0;

                        try {
                            $Query = new MinecraftPing($MQ_SERVER_ADDR, $MQ_SERVER_PORT, $MQ_TIMEOUT);

                            $Info = $Query->Query();

                            if (is_array($Info)) {

                                if (isset($Info['players'])) {
                                    $subarray = $Info['players'];
                                    $maxplayers = $subarray['max'];
                                    $playersin = $subarray['online'];
                                }
                            }

                            if ($Info === false) {

                                $Query->Close();
                                $Query->Connect();

                                $Info = $Query->QueryOldPre17();
                                if (is_array($Info)) {

                                    if (isset($Info['MaxPlayers'])) {
                                        $maxplayers = $Info['MaxPlayers'];
                                    }

                                    if (isset($Info['Players'])) {
                                        $playersin = $Info['Players'];
                                    }
                                }
                            }
                        } catch (MinecraftPingException $e) {
                            $Exception = $e;
                        }

                        if ($Query !== null) {
                            $Query->Close();
                        }

                        if ($playersin == 0 && $maxplayers == 0) {
                            $retorno = "";
                        } else {
                            $retorno = $playersin . " / " . $maxplayers;
                        }
                    }
                }
            }
        }
        echo $retorno;
    }
}
