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
<link href="css/servers.css" rel="stylesheet">

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

    //COMPROBAR SI ES EL SUPERADMIN O ADMIN
    if (array_key_exists('rango', $_SESSION['CONFIGUSER'])) {
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1) {
            $expulsar = 1;
        }
    }

    if ($expulsar != 1) {
        header("Location:index.php");
        exit;
    }

    //VALIDAMOS SESSION SINO ERROR
    if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

        $contador = 0;

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
                                    <div class="py-1">
                                        <div class="container">
                                            <h1 class="mb-5">Descargar Servidor</h1>

                                            <div class="py-1">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3 class="">Elige el tipo de servidor que quieres descargar:</h3>
                                                            <br>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <hr>
                                                        </div>
                                                        <!-- Vanilla Inicio -->
                                                        <?php
                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownvanilla', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownvanilla'] == 1) {
                                                            $contador++;
                                                        ?>
                                                            <div class="col-md-3">
                                                                <a class="" href="vanilla.php">
                                                                    <span class="cartel border border-dark shadow-lg download-hover"><img src="img/icons/favicon-32x32.png" alt="mineminiicon" width="32" height="32"> Servidor Vanilla</span>
                                                                </a>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <ul class="">
                                                                    <li>Servidor Vanilla es el servidor oficial de Minecraft.</li>
                                                                    <li>No soporta plugins.</li>
                                                                    <li>Se actualiza al cambiar de versión el juego.</li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <hr>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                        <!-- Vanilla Fin -->

                                                        <!-- SPIGOT Inicio -->
                                                        <?php
                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pcompilarspigot', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pcompilarspigot'] == 1) {
                                                            $contador++;
                                                        ?>
                                                            <div class="col-md-3">
                                                                <a class="" href="spigot.php">
                                                                    <span class="cartel border border-dark shadow-lg download-hover"><img src="img/menu/spigot.png" alt="spigoticon" width="32" height="27"> Servidor Spigot</span>
                                                                </a>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <ul class="">
                                                                    <li>Servidor Spigot es una versión modificada del servidor de Minecraft.</li>
                                                                    <li>Soporta plugins.</li>
                                                                    <li>Añade más de 150 mejoras respecto al servidor oficial.</li>
                                                                    <li>Creado y soportado por la comunidad.</li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <hr>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <!-- SPIGOT Fin -->

                                                        <!-- PAPER Inicio -->
                                                        <?php
                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownpaper', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownpaper'] == 1) {
                                                            $contador++;
                                                        ?>
                                                            <div class="col-md-3">
                                                                <a class="" href="paper.php">
                                                                    <span class="cartel border border-dark shadow-lg download-hover"><img src="img/menu/paper.png" alt="papericon" width="32" height="32"> Servidor PaperMC</span>
                                                                </a>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <ul class="">
                                                                    <li>Servidor PaperMc es una versión modificada del servidor de Spigot.</li>
                                                                    <li>Soporta plugins.</li>
                                                                    <li>Servidor de alto rendimiento</li>
                                                                    <li>Creado y soportado por la comunidad.</li>
                                                                </ul>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <!-- PAPER Fin -->
                                                        <?php
                                                        if ($contador == 0) {
                                                        ?>
                                                            <div class="col-md-12">
                                                                <h3 class="">No tienes permisos para descargar ningún servidor.</h3>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                        <div class="col-md-12">
                                                            <hr>
                                                        </div>
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

            <script src="js/servers.js"></script>

        </div>
    <?php
        //FINAL VALIDAR SESSION
    } else {
        header("Location:index.php");
    }
    ?>

</body>

</html>