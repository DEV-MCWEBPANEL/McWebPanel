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

<link href="css/status.css" rel="stylesheet">

</head>

<body id="page-top">

    <?php
    //COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
    if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
        $_SESSION['VALIDADO'] = "NO";
        $_SESSION['KEYSECRETA'] = "0";
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
                                            <h1 class="mb-4">Estado</h1>
                                            <div class="row">

                                                <div class="col-md-9">
                                                    <div class="card mb-4">
                                                        <div class="card-header text-white bg-primary negrita">Estadísticas</div>
                                                        <div class="card-body">
                                                            <img class="d-block float-left mr-2" src="img/cpu.png" alt="CPU">
                                                            <p class="lead" id="textocpu">Cpu:</p>
                                                            <img class="d-block float-left mr-2" src="img/ram.png" alt="RAM">
                                                            <p class="lead" id="textoram">Ram:</p>
                                                            <img class="d-block float-left mr-2" src="img/menu/users.png" alt="Players">
                                                            <p class="lead" id="jugadores">Jugadores Online:</p>
                                                            <img class="d-block float-left mr-2" src="img/uptime.png" alt="Uptime">
                                                            <p class="lead" id="eluptime">Uptime:</p>
                                                            <img class="d-block float-left mr-2" src="img/clock.png" alt="clock">
                                                            <p class="lead" id="horaserver">Hora Servidor:</p>
                                                            <p class="lead" id="textoservidor">Estado: <span class="cartel">Cargando</span></p>
                                                            
                                                            <p class="lead" id="textoretorno"></p>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header text-white bg-info negrita">Acciones Servidor</div>
                                                        <div class="card-body text-center">

                                                            <?php

                                                            $rectiposerv = CONFIGTIPOSERVER;

                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pstatusstarserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pstatusstarserver'] == 1) {
                                                                echo '<button class="btn btn-primary btn-lg mx-1 mt-1" id="binicio" name="binicio" value="binicio" type="button">Iniciar Servidor</button>';
                                                            }

                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pstatusrestartserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pstatusrestartserver'] == 1) {

                                                                if ($rectiposerv == "spigot" || $rectiposerv == "paper" || $rectiposerv == "purpur") {
                                                                    echo '<button class="btn btn-warning btn-lg mx-1 mt-1" id="breiniciar" name="breiniciar" value="breiniciar" type="button">Reiniciar Servidor</button>';
                                                                }
                                                            }

                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pstatusstopserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pstatusstopserver'] == 1) {
                                                                echo '<button class="btn btn-primary btn-lg mx-1 mt-1" id="bparar" name="bparar" value="bparar" type="button">Apagar Servidor</button>';
                                                            }

                                                            if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pstatuskillserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pstatuskillserver'] == 1) {
                                                                echo '<button class="btn btn-danger btn-lg mx-1 mt-1" id="bkill" name="bkill" value="bkill" type="button">Matar Servidor</button>';
                                                            }
                                                            ?>

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
                </div>
            </div>
        </div>
        <script src="js/status.js"></script>

    <?php
        //FINAL VALIDAR SESSION
    } else {
        header("Location:index.php");
    }
    ?>
</body>

</html>