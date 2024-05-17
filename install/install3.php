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

header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; script-src 'self'; form-action 'self'; base-uri 'none'; connect-src 'self'; frame-ancestors 'none'");
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header('Permissions-Policy: geolocation=(), microphone=()');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once "../template/errorreport.php";

?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Instalador">
    <meta name="author" content="DEV-MCWEBPANEL">
    <title>McWebPanel Install</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Script AJAX -->
    <script src="../js/jquery.min.js"></script>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../img/icons/apple-icon-180x180.png" sizes="180x180">
    <link rel="icon" href="../img/icons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/icons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="icon" href="../img/icons/favicon.ico">
</head>

<body>

    <?php

    //Funcion limpieza strings entrantes
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
        $gethash = "";

        for ($a = 1; $a <= 32; $a++) {
            $secretkey .= strval(random_int(0, 9));
        }

        $gethash = hash("sha3-512", $secretkey);

        return $gethash;
    }

    // No se aceptan metodos que no sean post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //CARGAR VARIABLES
        $elusuario = "";
        $elpassword = "";
        $elrepassword = "";
        $elnombreservidor = "";
        $eldirectorio = "";
        $elpuerto = "";
        $laram = "";
        $eltiposerver = "";
        $loserrores = 0;
        $lakey = "";

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA CONFIG
        $dirconfig = "";
        $dirconfig = dirname(getcwd()) . PHP_EOL;
        $dirconfig = trim($dirconfig);
        $dirconfig .= "/config";

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA BACKUPS
        $dirbackups = "";
        $dirbackups = dirname(getcwd()) . PHP_EOL;
        $dirbackups = trim($dirbackups);
        $dirbackups .= "/backups";

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA TEMP
        $dirtemp = "";
        $dirtemp = dirname(getcwd()) . PHP_EOL;
        $dirtemp = trim($dirtemp);
        $dirtemp .= "/temp";

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA CONFIG
        $dirinstall = "";
        $dirinstall = dirname(getcwd()) . PHP_EOL;
        $dirinstall = trim($dirinstall);
        $dirinstall .= "/install";

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA CONFIG
        $dircron = "";
        $dircron = dirname(getcwd()) . PHP_EOL;
        $dircron = trim($dircron);
        $dircron .= "/cron";

        //RECOGER DATOS Y LIMPIARLOS
        $elusuario = test_input($_POST["eluser"]);
        $elpassword = test_input($_POST["elpass"]);
        $elrepassword = test_input($_POST["elrepass"]);
        $elnombreservidor = test_input($_POST["elnomserv"]);
        $t = time();
        $eldirectorio = "Minecraft" . $t;
        $elpuerto = test_input($_POST["elport"]);
        $laram = test_input($_POST["elram"]);
        $eltiposerver = test_input($_POST["eltipserv"]);
        $elmaxupload = test_input($_POST["maxupload"]);
        $elpostmax = $elmaxupload + 1;

        //COMPROBAR NO ESTEN VACIOS
        if ($elusuario == "" || $elpassword == "" || $elrepassword == "" || $elnombreservidor == "" || $eldirectorio == "" || $elpuerto == "" || $laram == "" || $eltiposerver == "" || $elmaxupload == "") {
            exit;
        }

        //COMPROBAR LONGITUD USUARIO
        if (strlen($elusuario) > 255) {
            exit;
        }

        $rutaraiz = dirname(getcwd()) . PHP_EOL;
        $rutaraiz = trim($rutaraiz);

        //OBTENER RUTA DONDE TIENE QUE ESTAR LA CARPETA SERVIDOR MINECRAFT
        $dircarpserver = "";
        $dircarpserver = dirname(getcwd()) . PHP_EOL;
        $dircarpserver = trim($dircarpserver);
        $dircarpserver .= "/" . $eldirectorio;

        //SI HAY PERMISOS ESCRITURA EN RAIZ
        clearstatcache();
        if (!is_writable($rutaraiz)) {
            echo "La carpeta raiz no tiene permisos de escritura";
            exit;
        }

        //COMPROBAR SI EL PASSWORD COINCIDE
        if ($elpassword != $elrepassword) {
            echo "El password no coincide";
            exit;
        }

        //CREAR CARPETA CONFIG
        clearstatcache();
        if (!file_exists($dirconfig)) {
            mkdir($dirconfig, 0700);
        }

        //CREAR CARPETA BACKUP
        clearstatcache();
        if (!file_exists($dirbackups)) {
            mkdir($dirbackups, 0700);
        }

        //CREAR CARPETA TEMP
        clearstatcache();
        if (!file_exists($dirtemp)) {
            mkdir($dirtemp, 0700);
        }

        //CREAR CARPETA SERVER MINECRAFT
        clearstatcache();
        if (!file_exists($dircarpserver)) {
            mkdir($dircarpserver, 0700);

            //PERFMISOS FTP
            $permcomando = "chmod 775 '" . $dircarpserver . "'";
            exec($permcomando);
        }

        //GENERAR TAREA CRONTAB
        $rutacron = $rutaraiz;
        $rutacronstat = $rutaraiz;
        $rutacron .= "/cron/cron.php";
        $rutacronstat .= "/cron/cronstat.log";
        $comandocron = 'crontab -l | grep -v -F "' . $rutacron . '"> mycron';
        exec($comandocron);
        $comandocron = 'echo "* * * * * php ' . $rutacron . ' >> ' . $rutacronstat . ' 2>&1" >> mycron';
        exec($comandocron);
        exec('crontab mycron');
        exec('rm mycron');

        //GUARDAR FICHERO .htaccess EN RAIZ
        $rutaescribir = $rutaraiz;
        $rutaescribir .= "/.htaccess";

        $linea1 = "php_value upload_max_filesize " . $elmaxupload . "M";
        $linea2 = "php_value post_max_size " . $elpostmax . "M";
        $linea3 = "php_value max_file_uploads 1";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "<IfModule mod_php7.c>" . PHP_EOL);
        fwrite($file, $linea1 . PHP_EOL);
        fwrite($file, $linea2 . PHP_EOL);
        fwrite($file, $linea3 . PHP_EOL);
        fwrite($file, "php_value max_execution_time 600" . PHP_EOL);
        fwrite($file, "php_value max_input_time 600" . PHP_EOL);
        fwrite($file, "</IfModule>" . PHP_EOL);
        fwrite($file, "<IfModule mod_php.c>" . PHP_EOL);
        fwrite($file, $linea1 . PHP_EOL);
        fwrite($file, $linea2 . PHP_EOL);
        fwrite($file, $linea3 . PHP_EOL);
        fwrite($file, "php_value max_execution_time 600" . PHP_EOL);
        fwrite($file, "php_value max_input_time 600" . PHP_EOL);
        fwrite($file, "</IfModule>" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO .htaccess EN CONFIG
        $rutaescribir = $dirconfig;
        $rutaescribir .= "/.htaccess";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "deny from all" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO .htaccess EN BACKUPS
        $rutaescribir = $dirbackups;
        $rutaescribir .= "/.htaccess";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "deny from all" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO .htaccess EN TEMP
        $rutaescribir = $dirtemp;
        $rutaescribir .= "/.htaccess";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "deny from all" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO .htaccess EN CRON
        $rutaescribir = $dircron;
        $rutaescribir .= "/.htaccess";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "deny from all" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO .htaccess EN MINECRAFT
        $rutaescribir = $dircarpserver;
        $rutaescribir .= "/.htaccess";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "deny from all" . PHP_EOL);
        fwrite($file, "php_flag engine off" . PHP_EOL);
        fwrite($file, "AllowOverride None" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO CONFUSER.JSON
        $rutaescribir = $dirconfig;
        $rutaescribir .= "/confuser.json";

        $hashed = hash("sha3-512", $elpassword);

        $arrayadmin[0]['usuario'] = $elusuario;
        $arrayadmin[0]['hash'] = $hashed;
        $arrayadmin[0]['rango'] = 1;
        $arrayadmin[0]['estado'] = "activado";
        $arrayadmin[0]['psystemconftemaweb'] = 1;

        $serialized = serialize($arrayadmin);
        file_put_contents($rutaescribir, $serialized);

        //GUARDAR FICHERO CONFOPCIONES.PHP
        $rutaescribir = $dirconfig;
        $rutaescribir .= "/confopciones.php";

        $lakey = generarkey($lakey);
        $lakey .= $t;

        $file = fopen($rutaescribir, "w");
        fwrite($file, "<?php " . PHP_EOL);
        fwrite($file, 'define("CONFIGSESSIONKEY", "' . $lakey . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGNOMBRESERVER", "' . $elnombreservidor . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGDIRECTORIO", "' . $eldirectorio . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGPUERTO", "' . $elpuerto . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGRAM", "' . $laram . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGTIPOSERVER", "' . $eltiposerver . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGARCHIVOJAR", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGEULAMINECRAFT", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGMAXUPLOAD", "' . $elmaxupload . '");' . PHP_EOL);
        fwrite($file, 'define("CONFIGOPTIONGARBAGE", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGOPTIONFORCEUPGRADE", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGOPTIONERASECACHE", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGJAVASELECT", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGJAVANAME", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGJAVAMANUAL", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGFOLDERBACKUPSIZE", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGFOLDERMINECRAFTSIZE", "0");' . PHP_EOL);
        fwrite($file, 'define("CONFIGLINEASCONSOLA", "100");' . PHP_EOL);
        fwrite($file, 'define("CONFIGSHOWSIZEFOLDERS", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGBOOTSYSTEM", "NO");' . PHP_EOL);
        fwrite($file, 'define("CONFIGIGNORERAMLIMIT", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGMANTENIMIENTO", "Desactivado");' . PHP_EOL);
        fwrite($file, 'define("CONFIGBUFFERLIMIT", "100");' . PHP_EOL);
        fwrite($file, 'define("CONFIGARGMANUALINI", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGARGMANUALFINAL", "");' . PHP_EOL);
        fwrite($file, 'define("CONFIGCONSOLETYPE", "2");' . PHP_EOL);
        fwrite($file, 'define("CONFIGXMSRAM", "1024");' . PHP_EOL);
        fwrite($file, 'define("CONFIGBACKUPMULTI", "1");' . PHP_EOL);
        fwrite($file, 'define("CONFIGBACKUPCOMPRESS", "1");' . PHP_EOL);
        fwrite($file, 'define("CONFIGBACKUPHILOS", "1");' . PHP_EOL);
        fwrite($file, 'define("CONFIGBACKUROTATE", "0");' . PHP_EOL);
        fwrite($file, "?>" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO SCREEN.CONF
        $rutaescribir = $dirconfig;
        $rutaescribir .= "/screen.conf";

        $file = fopen($rutaescribir, "w");
        fwrite($file, "logfile flush 0" . PHP_EOL);
        fwrite($file, "log on" . PHP_EOL);
        fclose($file);

        //GUARDAR FICHERO SERVER.PROPERTIES
        $rutaescribir = $dircarpserver;
        $rutaescribir .= "/server.properties";

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
        fwrite($file, "server-port=" . PHP_EOL);
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
        //eliminado ya que puede variar si es inferior a Minecraft 1.19 fwrite($file, "level-type=default" . PHP_EOL);
        fwrite($file, "text-filtering-config=" . PHP_EOL);
        fwrite($file, "spawn-monsters=true" . PHP_EOL);
        fwrite($file, "enforce-whitelist=false" . PHP_EOL);
        fwrite($file, "resource-pack-sha1=" . PHP_EOL);
        fwrite($file, "spawn-protection=16" . PHP_EOL);
        fwrite($file, "max-world-size=29999984" . PHP_EOL);
        fclose($file);

        //ELIMINAR INSTALL
        $elcomando = "rm -r ";
        $elcomando .= $dirinstall;
        exec($elcomando);

        //REDIRECCIONAR AL LOGIN
        header("location:../index.php");
    } else {
        //REDIRECCIONAR INICIO INSTALACION
        header("location:index.php");
    }

    ?>

</body>

</html>