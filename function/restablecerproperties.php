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

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconfmine', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconfmine'] == 1) {

        //VALIDAMOS SESSION
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST['action']) && !empty($_POST['action'])) {
                $elerror = 0;
                $retorno = "";
                $dirconfig = "";
                $rutaescribir = "";

                //OBTENER PUERTO DE LA CONFIGURACION
                $recpuerto = CONFIGPUERTO;

                //OBTENER CARPETA MINECRAFT
                $reccarpmine = CONFIGDIRECTORIO;

                //OBTENER RUTA DONDE TIENE QUE ESTAR
                $dirconfig = dirname(getcwd()) . PHP_EOL;
                $dirconfig = trim($dirconfig);
                $dirconfig .= "/" . $reccarpmine;

                //ASIGNAR DIRECTORIO A RUTAESCRIBIR
                $rutaescribir = $dirconfig;
                $rutaescribir .= "/server.properties";

                //SI HAY PERMISOS ESCRITURA
                clearstatcache();
                if (!is_writable($dirconfig)) {
                    $retorno = "nowriteconfig";
                    $elerror = 1;
                }

                if ($elerror == 0) {
                    //SI EXISTE EL ARCHIVO
                    clearstatcache();
                    if (file_exists($rutaescribir)) {
                        //SI SE PUEDE ESCRIBIR
                        if (!is_writable($rutaescribir)) {
                            $retorno = "nowriteproperties";
                            $elerror = 1;
                        }
                    }
                }

                if ($elerror == 0) {
                    $file = fopen($rutaescribir, "w");
                    fwrite($file, "enable-jmx-monitoring=false" . PHP_EOL);
                    fwrite($file, "rcon.port=25575" . PHP_EOL);
                    fwrite($file, "level-seed=" . PHP_EOL);
                    fwrite($file, "gamemode=survival" . PHP_EOL);
                    fwrite($file, "enable-command-block=false" . PHP_EOL);
                    fwrite($file, "enable-query=false" . PHP_EOL);
                    fwrite($file, "generator-settings=" . PHP_EOL);
                    fwrite($file, "level-name=world" . PHP_EOL);
                    fwrite($file, "motd=A Minecraft Server" . PHP_EOL);
                    fwrite($file, "query.port=25565" . PHP_EOL);
                    fwrite($file, "pvp=true" . PHP_EOL);
                    fwrite($file, "generate-structures=true" . PHP_EOL);
                    fwrite($file, "max-chained-neighbor-updates=1000000" . PHP_EOL);
                    fwrite($file, "difficulty=easy" . PHP_EOL);
                    fwrite($file, "network-compression-threshold=256" . PHP_EOL);
                    fwrite($file, "require-resource-pack=false" . PHP_EOL);
                    fwrite($file, "max-tick-time=60000" . PHP_EOL);
                    fwrite($file, "use-native-transport=true" . PHP_EOL);
                    fwrite($file, "max-players=20" . PHP_EOL);
                    fwrite($file, "online-mode=true" . PHP_EOL);
                    fwrite($file, "enable-status=true" . PHP_EOL);
                    fwrite($file, "allow-flight=false" . PHP_EOL);
                    fwrite($file, "broadcast-rcon-to-ops=true" . PHP_EOL);
                    fwrite($file, "view-distance=10" . PHP_EOL);
                    //eliminado en Minecraft 1.17 fwrite($file, "max-build-height=256" . PHP_EOL);
                    fwrite($file, "server-ip=" . PHP_EOL);
                    fwrite($file, "resource-pack-prompt=" . PHP_EOL);
                    fwrite($file, "allow-nether=true" . PHP_EOL);
                    fwrite($file, "server-port=" . $recpuerto . PHP_EOL);
                    fwrite($file, "enable-rcon=false" . PHP_EOL);
                    fwrite($file, "sync-chunk-writes=true" . PHP_EOL);
                    fwrite($file, "op-permission-level=4" . PHP_EOL);
                    fwrite($file, "prevent-proxy-connections=false" . PHP_EOL);
                    fwrite($file, "hide-online-players=false" . PHP_EOL);
                    fwrite($file, "resource-pack=" . PHP_EOL);
                    fwrite($file, "entity-broadcast-range-percentage=100" . PHP_EOL);
                    fwrite($file, "simulation-distance=10" . PHP_EOL);
                    fwrite($file, "rcon.password=" . PHP_EOL);
                    fwrite($file, "player-idle-timeout=0" . PHP_EOL);
                    fwrite($file, "force-gamemode=false" . PHP_EOL);
                    fwrite($file, "rate-limit=0" . PHP_EOL);
                    fwrite($file, "hardcore=false" . PHP_EOL);
                    fwrite($file, "white-list=false" . PHP_EOL);
                    fwrite($file, "broadcast-console-to-ops=true" . PHP_EOL);
                    fwrite($file, "spawn-npcs=true" . PHP_EOL);
                    fwrite($file, "spawn-animals=true" . PHP_EOL);
                    //eliminado en Minecraft 1.18 fwrite($file, "snooper-enabled=true" . PHP_EOL);
                    fwrite($file, "function-permission-level=2" . PHP_EOL);
                    //Quitado ya que puede variar si es inferior a Minecraft 1.19 fwrite($file, "level-type=default" . PHP_EOL);
                    fwrite($file, "text-filtering-config=" . PHP_EOL);
                    fwrite($file, "spawn-monsters=true" . PHP_EOL);
                    fwrite($file, "enforce-whitelist=false" . PHP_EOL);
                    fwrite($file, "resource-pack-sha1=" . PHP_EOL);
                    fwrite($file, "spawn-protection=16" . PHP_EOL);
                    fwrite($file, "max-world-size=29999984" . PHP_EOL);
                    fwrite($file, "accepts-transfers=false" . PHP_EOL);
                    fwrite($file, "bug-report-link=" . PHP_EOL);
                    fwrite($file, "region-file-compression=deflate" . PHP_EOL);
                    fclose($file);

                    //PERMISO SERVER.PROPERTIES
                    $elcommando = "chmod 664 " . $rutaescribir;
                    exec($elcommando);

                    $retorno = "OK";
                }

                echo $retorno;
            }
        }
    }
}
