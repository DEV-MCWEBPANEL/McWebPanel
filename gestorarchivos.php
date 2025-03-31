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

require_once "template/session.php";
require_once "template/errorreport.php";
require_once "config/confopciones.php";
require_once "template/header.php";

function test_input($data)
{
    if (isset($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

//FUNCION DEVUELVE DATOS EN EL FORMATO B/KB/MB/GB/TB
function devolverdatos($losbytes, $opcion, $decimal)
{
    $eltipo = "";

    if ($losbytes >= 0) {
        $eltipo = "B";
        $result = $losbytes;
    }

    if ($losbytes >= 1024) {
        $eltipo = "KB";
        $result = $losbytes / 1024;
    }

    if ($losbytes >= 1048576) {
        $eltipo = "MB";
        $result = $losbytes / 1048576;
    }

    if ($losbytes >= 1073741824) {
        $eltipo = "GB";
        $result = $losbytes / 1073741824;
    }

    if ($losbytes >= 1099511627776) {
        $eltipo = "TB";
        $result = $losbytes / 1099511627776;
    }

    if ($opcion == 0) {
        $result = strval(round($result, $decimal));
        return $result;
    } elseif ($opcion == 1) {
        $result = strval(round($result, $decimal)) . " " . $eltipo;
        return $result;
    }
}

function obtenersizecarpeta($dir)
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );

    $totalSize = 0;
    try {
        foreach ($iterator as $file) {
            $totalSize += $file->getSize();
        }
    } catch (Throwable $t) {
        //VACIO
    }
    return $totalSize;
}

?>
<!-- Custom styles for this template-->
<?php
if (isset($_SESSION['CONFIGUSER']['psystemconftemaweb'])) {
    if ($_SESSION['CONFIGUSER']['psystemconftemaweb'] == 2) {
        echo '<link href="css/dark.css" rel="stylesheet">';
    } else {
        echo '<link href="css/light.css" rel="stylesheet">';
    }
} else {
    echo '<link href="css/light.css" rel="stylesheet">';
}
?>

</head>

<body id="page-top">

    <?php

    $expulsar = 0;

    //COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
    if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
        $_SESSION['VALIDADO'] = "NO";
        $_SESSION['KEYSECRETA'] = "0";
        header("Location:index.php");
        exit;
    }

    //COMPROBAR SI ES EL SUPERADMIN O ADMIN O USER CON PERMISOS
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivos', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivos'] == 1) {
        $expulsar = 1;
    }

    if ($expulsar != 1) {
        header("Location:index.php");
        exit;
    }

    //VALIDAMOS SESSION SINO ERROR
    if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {
    ?>

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <?php
            require_once "template/menu.php";
            ?>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <div class="col-md-12">
                            <div class="card border-left-primary shadow h-100">
                                <div class="card-body">

                                    <!-- Page Heading -->
                                    <div class="py-1">
                                        <div class="container">
                                            <h1 class="">Gestor Archivos</h1>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php

                                                    //INICIAR VARIABLES
                                                    $contadorarchivos = 0;
                                                    $eltamano = "";
                                                    $archivoconcreto = "";
                                                    $tipoarchivo = "";
                                                    $getinfofile = "";
                                                    $getrutalimpia = "";
                                                    $getrutaparseada = "";
                                                    $valencontrado = 0;
                                                    $rutahta = "";

                                                    $recpuerto = CONFIGPUERTO;
                                                    $recram = CONFIGRAM;
                                                    $rectiposerv = CONFIGTIPOSERVER;
                                                    $recnombreserv = CONFIGNOMBRESERVER;
                                                    $reccarpmine = CONFIGDIRECTORIO;
                                                    $recarchivojar = CONFIGARCHIVOJAR;
                                                    $receulaminecraft = CONFIGEULAMINECRAFT;
                                                    $recshowsizefolder = CONFIGSHOWSIZEFOLDERS;

                                                    //OBTENER RUTA RAIZ
                                                    $dirraiz = getcwd() . PHP_EOL;
                                                    $dirraiz = trim($dirraiz);

                                                    //OBTENER RUTA SERVIDOR MINECRAFT
                                                    $rutaarchivo = getcwd();
                                                    $rutaarchivo = trim($rutaarchivo);
                                                    $rutaarchivo .= "/" . $reccarpmine;

                                                    //INICIALIZAR SESSION RUTACTUAL Y RUTALIMITE
                                                    if (!isset($_SESSION['RUTACTUAL'])) {
                                                        $_SESSION['RUTACTUAL'] = $rutaarchivo;
                                                        $_SESSION['RUTALIMITE'] = $rutaarchivo;
                                                        $_SESSION['COPIARFILES'] = "0";
                                                    } else {
                                                        $rutaarchivo = $_SESSION['RUTACTUAL'];
                                                    }

                                                    //OBTENER IDENFIFICADOR SCREEN GESTOR ARCHIVOS
                                                    $processdescomzip = $dirraiz . "/gestorarchivos";
                                                    $processdescomzip = str_replace("/", "", $processdescomzip);

                                                    //VER SI HAY UN PROCESO YA DESCOMPRIMIENDO ZIP
                                                    $elcomando = "screen -ls | gawk '/\." . $processdescomzip . "\t/ {print strtonum($1)'}";
                                                    $retornodeszip = shell_exec($elcomando);

                                                    //INICIALIZAR SESSIONES STATE
                                                    if ($retornodeszip != "") {
                                                        $_SESSION['GESTARCHPROSSES'] = 1;
                                                    } else {
                                                        $_SESSION['GESTARCHPROSSES'] = 0;
                                                    }

                                                    //OBTENER ARRAY ARCHIVOS EXCLUIDOS BACKUP
                                                    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosexcludefiles', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosexcludefiles'] == 1) {
                                                        $rutaexcluidos = trim(getcwd() . "/config" . "/excludeback.json" . PHP_EOL);
                                                        $buscaexcluidos = array();

                                                        clearstatcache();
                                                        if (file_exists($rutaexcluidos)) {
                                                            clearstatcache();
                                                            if (is_readable($rutaexcluidos)) {
                                                                $buscaarray = file_get_contents($rutaexcluidos);
                                                                $buscaexcluidos = unserialize($buscaarray);
                                                            }
                                                        }
                                                    }

                                                    //SI TE QUEDAS ATASCADO EN UNA CARPETA SUPERIOR Y LUEGO SE ELIMINO POR CONSOLA O FTP
                                                    clearstatcache();
                                                    if (!file_exists($rutaarchivo)) {
                                                        $_SESSION['RUTACTUAL'] = $_SESSION['RUTALIMITE'];
                                                        $rutaarchivo = $_SESSION['RUTALIMITE'];
                                                    }

                                                    //COMPROBAR SI EXISTE CARPETA SERVIDOR MINECRAF
                                                    clearstatcache();
                                                    if (!file_exists($_SESSION['RUTALIMITE'])) {
                                                        echo "<div class='alert alert-danger' role='alert'>Error: No existe la carpeta servidor minecraft.</div>";
                                                        exit;
                                                    }

                                                    //COMPROBAR SI SE PUEDE LEER CARPETA
                                                    clearstatcache();
                                                    if (!is_readable($_SESSION['RUTALIMITE'])) {
                                                        echo "<div class='alert alert-danger' role='alert'>Error: La carpeta servidor minecraft no tiene permisos de lectura.</div>";
                                                        exit;
                                                    }

                                                    //COMPROBAR SI SE PUEDE ESCRIBIR EN CARPETA
                                                    clearstatcache();
                                                    if (!is_writable($_SESSION['RUTALIMITE'])) {
                                                        echo "<div class='alert alert-danger' role='alert'>Error: La carpeta servidor minecraft no tiene permisos de escritura.</div>";
                                                        exit;
                                                    }

                                                    //COMPROBAR SI SE PUEDE EJECUTAR EN CARPETA
                                                    clearstatcache();
                                                    if (!is_executable($_SESSION['RUTALIMITE'])) {
                                                        echo "<div class='alert alert-danger' role='alert'>Error: La carpeta servidor minecraft no tiene permisos de ejecucion.</div>";
                                                        exit;
                                                    }

                                                    //FORZAR .htaccess CARPETA SERVIDOR MINECRAFT
                                                    $rutahta = $_SESSION['RUTALIMITE'] . "/.htaccess";
                                                    $file = fopen($rutahta, "w");
                                                    fwrite($file, "deny from all" . PHP_EOL);
                                                    fwrite($file, "php_flag engine off" . PHP_EOL);
                                                    fwrite($file, "AllowOverride None" . PHP_EOL);
                                                    fclose($file);

                                                    //PARSEAR RUTA QUITANDO LO ANTERIOR A LA CARPETA MINECRAFT
                                                    $getrutaparseada = substr($_SESSION['RUTACTUAL'], strlen($dirraiz));
                                                    $getrutaparseada = str_replace("/", " / ", $getrutaparseada);

                                                    ?>

                                                    <nav aria-label="breadcrumb">
                                                        <ol class="breadcrumb">
                                                            <li class="breadcrumb-item active"><?php echo "Carpeta: " . $getrutaparseada; ?></li>
                                                        </ol>
                                                    </nav>

                                                    <button type="button" id="bnactualizar" class="btn btn-primary mr-1 mt-1" title="Actualizar"><img src="img/botones/refresh.png" alt="Actualizar"></button>

                                                    <?php
                                                    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivoscrearcarpeta', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivoscrearcarpeta'] == 1) {
                                                    ?>
                                                        <button type="button" id="bnnuevacarpeta" class="btn btn-primary mr-1 mt-1" title="Crear Carpeta"><img src="img/botones/new.png" alt="+"> Crear Carpeta</button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivoscopiar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivoscopiar'] == 1) {
                                                    ?>
                                                        <button type="button" id="bcopiar" class="btn btn-primary mr-1 mt-1" title="Copiar"><img src="img/botones/copiar.png" alt="+"> Copiar</button>
                                                        <?php
                                                        if ($_SESSION['COPIARFILES'] != "0") {
                                                        ?>
                                                            <button type="button" id="bpegar" class="btn btn-primary mr-1 mt-1" title="Pegar"><img src="img/botones/pegar.png" alt="+"> Pegar</button>
                                                        <?php
                                                        }
                                                        ?>
                                                    <?php
                                                    }
                                                    ?>
                                                    <button type="button" id="bselectall" class="btn btn-primary mr-1 mt-1" title="Seleccionar Todo"><img src="img/botones/checkselect.png" alt=""> Seleccionar Todo</button>
                                                    <button type="button" id="bunselectall" class="btn btn-primary mr-1 mt-1" title="Quitar Selección"><img src="img/botones/checkunselect.png" alt=""> Quitar Selección</button>

                                                    <?php
                                                    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosborrar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosborrar'] == 1) {
                                                    ?>
                                                        <button type="button" id="beliminarseleccion" class="btn btn-danger mr-1 mt-1" title="Eliminar Seleccionados"><img src="img/botones/borrar.png" alt=""> Eliminar Seleccionados</button>
                                                    <?php
                                                    }
                                                    ?>

                                                    <?php
                                                    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosexcludefiles', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosexcludefiles'] == 1) {
                                                        clearstatcache();
                                                        if (file_exists($rutaexcluidos)) {
                                                    ?>
                                                            <button type="button" id="resetexcluidos" class="btn btn-warning mr-1 mt-1" title="Borrar Lista Excluidos"><img src="img/botones/borrar.png" alt=""> Borrar Lista Excluidos</button>
                                                    <?php
                                                        }
                                                    }
                                                    ?>


                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-borderless">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Nombre</th>
                                                                    <th scope="col">Fecha</th>
                                                                    <th scope="col">Tamaño</th>
                                                                    <th scope="col">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <?php

                                                                //CARGAR ARRAYS
                                                                $fcarpetas = array();
                                                                $farchivos = array();

                                                                //OBTENER CARPETAS Y DIRECTORIOS
                                                                $a = scandir($rutaarchivo);

                                                                //SEPARAR CARPETAS Y DIRECTORIOS
                                                                for ($i = 0; $i < count($a); $i++) {
                                                                    $rutfil = $rutaarchivo . "/" . $a[$i];
                                                                    clearstatcache();
                                                                    if (is_dir($rutfil)) {
                                                                        //Evitar mostrar .
                                                                        if ($a[$i] != ".") {
                                                                            $fcarpetas[] = $a[$i];
                                                                        }
                                                                    } else {
                                                                        //Evitar mostrar .htaccess
                                                                        $archivoconcreto = $rutaarchivo . "/" . $a[$i];
                                                                        $getinfofile = pathinfo($archivoconcreto);

                                                                        //COMPRUEBA SI EL ARCHIVO TIENE EXTENSION
                                                                        if (array_key_exists('extension', $getinfofile)) {
                                                                            $tipoarchivo = "." . strtolower($getinfofile['extension']);
                                                                            $tipoarchivo = trim($tipoarchivo);
                                                                        } else {
                                                                            $tipoarchivo = "";
                                                                        }

                                                                        if ($tipoarchivo != ".htaccess" && $tipoarchivo != ".sh") {
                                                                            $farchivos[] = $a[$i];
                                                                        }
                                                                    }
                                                                }

                                                                //JUNTAR ARRAYS
                                                                $fcarpetas = array_merge($fcarpetas, $farchivos);

                                                                //RECORRER ARRAY Y AÑADIR LAS PROPIEDADES Y LOS BOTONES
                                                                for ($i = 0; $i < count($fcarpetas); $i++) {
                                                                    $archivoconcreto = $rutaarchivo . "/" . $fcarpetas[$i];

                                                                    //Se limpia el nombre de archivo
                                                                    $fcarpetas[$i] = test_input($fcarpetas[$i]);

                                                                    echo '<tr class = "menu-hover">';

                                                                    echo '<th class = "elclick1" scope="row" id="' . $i . '">';

                                                                    if ($fcarpetas[$i] != "." && $fcarpetas[$i] != "..") {
                                                                        clearstatcache();
                                                                        if (is_dir($archivoconcreto)) {
                                                                            clearstatcache();
                                                                            if (is_executable($archivoconcreto)) {
                                                                                echo '<input class="laseleccion mr-2" type="checkbox" value="' . $fcarpetas[$i] . '">';
                                                                            } else {
                                                                                echo '<input class="laseleccion mr-2" title="Sin permisos de ejecucion/Enter" type="checkbox" disabled="disabled">';
                                                                            }
                                                                        } else {
                                                                            echo '<input class="laseleccion mr-2" type="checkbox" value="' . $fcarpetas[$i] . '">';
                                                                        }
                                                                    }

                                                                    $getinfofile = pathinfo($archivoconcreto);

                                                                    clearstatcache();
                                                                    if (is_dir($archivoconcreto)) {
                                                                        echo '<img class="mr-2" src="img/gestorarchivos/carpeta.png" alt="carpeta">' . $fcarpetas[$i] . '</th>';
                                                                    } else {

                                                                        //COMPRUEBA SI EL ARCHIVO TIENE EXTENSION
                                                                        if (array_key_exists('extension', $getinfofile)) {
                                                                            $tipoarchivo = "." . strtolower($getinfofile['extension']);
                                                                            $tipoarchivo = trim($tipoarchivo);
                                                                        } else {
                                                                            $tipoarchivo = "";
                                                                        }

                                                                        //VER TIPO Y AÑADIR ICONO
                                                                        if ($tipoarchivo == ".txt") {
                                                                ?>
                                                                            <img class="mr-2" src="img/gestorarchivos/txt.png" alt="txt"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".jar") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/java.png" alt="java"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".yml") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/yml.png" alt="yml"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".json") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/json.png" alt="json"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".htaccess") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/htaccess.png" alt="htaccess"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".properties") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/mine.png" alt="minecraft"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".bmp" || $tipoarchivo == ".dib" || $tipoarchivo == ".jpg" || $tipoarchivo == ".jpeg" || $tipoarchivo == ".jpe" || $tipoarchivo == ".jfif" || $tipoarchivo == ".gif" || $tipoarchivo == ".tiff" || $tipoarchivo == ".png" || $tipoarchivo == ".heic") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/img.png" alt="img"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".rar") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/rar.png" alt="rar"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".zip") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/zip.png" alt="zip"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } elseif ($tipoarchivo == ".tar" || $tipoarchivo == ".bz2" || $tipoarchivo == ".gz" || $tipoarchivo == ".lz" || $tipoarchivo == ".lzma" || $tipoarchivo == ".xz" || $tipoarchivo == ".z" || $tipoarchivo == ".taz" || $tipoarchivo == ".tb2" || $tipoarchivo == ".tbz" || $tipoarchivo == ".tbz2" || $tipoarchivo == ".tgz" || $tipoarchivo == ".tlz" || $tipoarchivo == ".txz" || $tipoarchivo == ".tz") {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/tar.png" alt="tar"><?php echo $fcarpetas[$i]; ?></th>
                                                                        <?php
                                                                        } else {
                                                                        ?><img class="mr-2" src="img/gestorarchivos/void.png" alt="noicon"><?php echo $fcarpetas[$i]; ?></th>
                                                                    <?php
                                                                        }
                                                                    }

                                                                    //AÑADIR FECHA ARCHIVO/CARPETA
                                                                    clearstatcache();
                                                                    if (!is_dir($archivoconcreto)) {
                                                                        echo '<td class = "elclick1" id="' . $i . '">' . date("d/m/Y H:i:s", filemtime($archivoconcreto)) . '</td>';
                                                                    } else {
                                                                        echo '<td class = "elclick1" id="' . $i . '">' . date("d/m/Y H:i:s", filemtime($archivoconcreto)) . '</td>';
                                                                    }

                                                                    //AÑADIR TAMAÑO ARCHIVO
                                                                    clearstatcache();
                                                                    if (!is_dir($archivoconcreto)) {
                                                                        $eltamano = devolverdatos(filesize($archivoconcreto), 1, 2);
                                                                    } else {
                                                                        if ($fcarpetas[$i] != "..") {
                                                                            if ($recshowsizefolder == 1) {
                                                                                $eltamano = devolverdatos(obtenersizecarpeta($archivoconcreto), 1, 2);
                                                                            } else {
                                                                                $eltamano = ".";
                                                                            }
                                                                        }
                                                                    }
                                                                    echo '<td class = "elclick1" id="' . $i . '">' . $eltamano . '</td>';
                                                                    echo '<td>';

                                                                    //CREAR BOTONES ARCHIVOS Y CARPETAS
                                                                    clearstatcache();
                                                                    if (!is_dir($archivoconcreto)) {

                                                                        //BOTON DESCARGAR
                                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosdescargar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosdescargar'] == 1) {
                                                                            if ($fcarpetas[$i] != "0") {
                                                                                echo '<button type="button" class="descargarfile btn btn-primary mr-1" value="' . $fcarpetas[$i] . '" title="Descargar"><img src="img/botones/down.png" alt="Descargar"></button>';
                                                                            }
                                                                        }

                                                                        //BOTON DESCOMPRIMIR
                                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosdescomprimir', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosdescomprimir'] == 1) {
                                                                            if ($tipoarchivo == ".tar" || $tipoarchivo == ".bz2" || $tipoarchivo == ".gz" || $tipoarchivo == ".lz" || $tipoarchivo == ".lzma" || $tipoarchivo == ".xz" || $tipoarchivo == ".z" || $tipoarchivo == ".taz" || $tipoarchivo == ".tb2" || $tipoarchivo == ".tbz" || $tipoarchivo == ".tbz2" || $tipoarchivo == ".tgz" || $tipoarchivo == ".tlz" || $tipoarchivo == ".txz" || $tipoarchivo == ".tz") {
                                                                                echo '<button type="button" class="descomprimirtar btn btn-primary mr-1" value="' . $fcarpetas[$i] . '" title="Descomprimir"><img src="img/botones/descomprimir.png" alt="Descomprimir"></button>';
                                                                            } elseif ($tipoarchivo == ".zip") {
                                                                                echo '<button type="button" class="descomprimirzip btn btn-primary mr-1" value="' . $fcarpetas[$i] . '" title="Descomprimir"><img src="img/botones/descomprimir.png" alt="Descomprimir"></button>';
                                                                            }
                                                                        }

                                                                        //BOTON EDITAR ARCHIVO
                                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivoseditar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivoseditar'] == 1) {
                                                                            if ($tipoarchivo == ".txt" || $tipoarchivo == ".json" || $tipoarchivo == ".log" || $tipoarchivo == ".mcmeta" || $tipoarchivo == ".yml" || $tipoarchivo == ".cfg" || $tipoarchivo == ".conf" || $tipoarchivo == ".toml" || $tipoarchivo == ".sk" || $tipoarchivo == ".properties") {
                                                                                echo '<button type="button" class="editarfile btn btn-info text-white mr-1" value="' . $fcarpetas[$i] . '" title="Editar"><img src="img/botones/editar.png" alt="Editar"></button>';
                                                                            }
                                                                        }

                                                                        //BOTON RENOMBRAR ARCHIVO
                                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosrenombrar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosrenombrar'] == 1) {
                                                                            echo '<button type="button" class="renamefile btn btn-warning text-white mr-1" id="' . $fcarpetas[$i] . '" value="' . $fcarpetas[$i] . '" title="Renombrar"><img src="img/botones/rename.png" alt="Renombrar"></button>';
                                                                        }

                                                                        //BOTON EXCLUIR BACKUP ARCHIVO
                                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosexcludefiles', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosexcludefiles'] == 1) {
                                                                            $compararuta = trim($_SESSION['RUTACTUAL'] . "/" . $fcarpetas[$i]);

                                                                            if (in_array($compararuta, array_column($buscaexcluidos, 'completa'))) {
                                                                                echo '<button type="button" class="incluirbackup btn btn-warning text-white mr-1" id="' . $fcarpetas[$i] . '" value="' . $fcarpetas[$i] . '" title="Incluir archivo al backup"><img src="img/botones/includeb.png" alt="Incluir archivo al backup"></button>';
                                                                            } else {
                                                                                echo '<button type="button" class="excluirbackup btn btn-warning text-white mr-1" id="' . $fcarpetas[$i] . '" value="' . $fcarpetas[$i] . '" title="Excluir archivo del backup"><img src="img/botones/excludeb.png" alt="Excluir archivo del backup"></button>';
                                                                            }
                                                                        }

                                                                        //BOTON BORRAR ARCHIVO
                                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosborrar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosborrar'] == 1) {
                                                                            echo '<button type="button" class="borrarfile btn text-white btn-danger" id="' . $fcarpetas[$i] . '" value="' . $fcarpetas[$i] . '" title="Borrar"><img src="img/botones/borrar.png" alt="Borrar"></button>';
                                                                        }

                                                                        echo '</td>';
                                                                        echo '</tr>';
                                                                    } else {
                                                                        if ($fcarpetas[$i] == "..") {

                                                                            $elatras = explode('/', $_SESSION['RUTACTUAL']);
                                                                            $elatras = end($elatras);
                                                                            $elatras = trim($elatras);

                                                                            echo '<button type="button" class="atras btn btn-info text-white mr-1" value="' . $elatras . '" title="Atras"><img src="img/botones/atras.png" alt="Atras"> Atras</button>';
                                                                        } elseif ($fcarpetas[$i] == ".") {
                                                                            //NADA
                                                                        } else {

                                                                            echo '<button type="button" class="entrar btn btn-info text-white mr-1" value="' . $fcarpetas[$i] . '" title="Entrar"><img src="img/botones/entrar.png" alt="Entrar"></button>';

                                                                            //BOTON COMPRIMIR CARPETA
                                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivoscomprimir', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivoscomprimir'] == 1) {
                                                                                echo '<button type="button" class="comprimirzipfolder btn btn-warning text-white mr-1" value="' . $fcarpetas[$i] . '" title="Comprimir carpeta en Zip"><img src="img/botones/comprimir.png" alt="Comprimir carpeta en Zip"></button>';
                                                                            }

                                                                            //BOTON RENOMBRAR CARPETA
                                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosrenombrar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosrenombrar'] == 1) {
                                                                                echo '<button type="button" id="' . $fcarpetas[$i] . '" class="renamefolder btn btn-warning text-white mr-1" value="' . $fcarpetas[$i] . '" title="Renombrar"><img src="img/botones/rename.png" alt="Renombrar"></button>';
                                                                            }

                                                                            //BOTON EXCLUIR CARPETA BACKUP
                                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosexcludefiles', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosexcludefiles'] == 1) {
                                                                                $compararuta = trim($_SESSION['RUTACTUAL'] . "/" . $fcarpetas[$i]);

                                                                                if (in_array($compararuta, array_column($buscaexcluidos, 'completa'))) {
                                                                                    echo '<button type="button" id="' . $fcarpetas[$i] . '" class="incluirbackup btn text-white btn-warning mr-1" value="' . $fcarpetas[$i] . '" title="Incluir carpeta al Backup"><img src="img/botones/includeb.png" alt="Incluir carpeta al Backup"></button>';
                                                                                } else {
                                                                                    echo '<button type="button" id="' . $fcarpetas[$i] . '" class="excluirbackup btn text-white btn-warning mr-1" value="' . $fcarpetas[$i] . '" title="Excluir carpeta del backup"><img src="img/botones/excludeb.png" alt="Excluir carpeta del backup"></button>';
                                                                                }
                                                                            }

                                                                            //BOTON BORRAR CARPETA
                                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivosborrar', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivosborrar'] == 1) {
                                                                                echo '<button type="button" id="' . $fcarpetas[$i] . '" class="borrarcarpeta btn text-white btn-danger" value="' . $fcarpetas[$i] . '" title="Borrar"><img src="img/botones/borrar.png" alt="Borrar"></button>';
                                                                            }

                                                                            echo '</td>';
                                                                            echo '</tr>';
                                                                        }
                                                                    }
                                                                }


                                                                if (defined('CONFIGFOLDERMINECRAFTSIZE')) {
                                                                    //OBTENER TAMAÑO CARPETA MINE MAXIMO PERMITIDO
                                                                    $recsizemine = CONFIGFOLDERMINECRAFTSIZE;
                                                                    ?>

                                                                    <tr>
                                                                        <th>
                                                                            <p class="lead negrita">Almacenamiento Minecraft</p>
                                                                        </th>
                                                                        <?php
                                                                        if ($recshowsizefolder == 1) {

                                                                            //OBTENER CARPETA SERVIDOR MINECRAFT
                                                                            $rutacarpetamine = getcwd() . PHP_EOL;
                                                                            $rutacarpetamine = trim($rutacarpetamine);
                                                                            $rutacarpetamine .= "/" . $reccarpmine;

                                                                            //OBTENER USADO
                                                                            $getgigasmine = devolverdatos(obtenersizecarpeta($rutacarpetamine), 1, 2);
                                                                        ?>
                                                                            <td>
                                                                                <p class="lead negrita">Usado: <?php echo $getgigasmine; ?></p>
                                                                            </td>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <td>
                                                                            <p class="lead negrita">Total: <?php if ($recsizemine == 0) {
                                                                                                                echo "Ilimitado";
                                                                                                            } else {
                                                                                                                echo $recsizemine . " GB";
                                                                                                            } ?></p>
                                                                        </td>
                                                                        <td>
                                                                            <p class="lead"></p>
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>

                                                                <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                        <hr>
                                                        <p class="lead negrita">Estados Procesos</p>
                                                        <p id="gifstatus"></p>
                                                        <p class="lead" id="textoretorno"></p>
                                                    </div>
                                                    <hr>
                                                    <?php
                                                    //SUBIR ARCHIVO
                                                    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivossubir', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivossubir'] == 1) {
                                                    ?>
                                                        <h1 class="">Subir Archivo</h1>
                                                        <div class="row">
                                                            <div class="col-md-4">

                                                                <p>(Limite Subida: <?php echo ini_get("upload_max_filesize"); ?>B)</p>

                                                                <form id="form" action="function/gestoruploadfile.php" method="post" enctype="multipart/form-data">

                                                                    <div class="custom-file mb-3">
                                                                        <input type="file" class="custom-file-input" id="fileName" name="uploadedFile">
                                                                        <label class="custom-file-label" for="fileName" id="lvltext">Elija el archivo</label>
                                                                    </div>

                                                                    <button class="btn btn-primary btn-block btn-lg text-white mt-2" id="botonsubir" type="submit" value="Upload">Subir Archivo</button>
                                                                </form>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <img class="" src="img/loading.gif" id="gifuploading" alt="uploading">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p class="lead" id="textouploadretorno"></p>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <script src="js/gestorarchivos.js"></script>

    <?php

        //FINAL VALIDAR SESSION
    } else {
        header("Location:index.php");
    }
    ?>

</body>

</html>