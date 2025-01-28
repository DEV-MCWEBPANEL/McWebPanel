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
<link href="css/consola.css" rel="stylesheet">

</head>

<body id="page-top">

    <?php

    $expulsar = 0;

    //COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
    if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
        $_SESSION['VALIDADO'] = "NO";
        $_SESSION['KEYSECRETA'] = "0";
        header("location:index.php");
        exit;
    }

    //COMPROBAR SI ES EL SUPERADMIN O ADMIN
    if (array_key_exists('rango', $_SESSION['CONFIGUSER'])) {
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1) {
            $expulsar++;
        }
    }

    if (array_key_exists('rango', $_SESSION['CONFIGUSER'])) {
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownpaper', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownpaper'] == 1) {
            $expulsar++;
        }
    }

    if ($expulsar != 2) {
        header("location:index.php");
        exit;
    }

    //VALIDAMOS SESSION SINO ERROR
    if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

        $elindexarray = 0;
        $elerror = 0;
        $retorno = "";
        $versiones = array();

        $url = "https://papermc.io/api/v2/projects/paper";

        $context = stream_context_create(
            array(
                "http" => array(
                    "timeout" => 10,
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );

        $contenido = @file_get_contents($url, false, $context);

        if ($contenido === FALSE) {
            $elerror = 1;
        } else {

            $versiones = json_decode($contenido, true);
            $versiones = $versiones['versions'];
            $versiones = array_reverse($versiones);
        }

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
                                            <h1 class="mb-5">Descargar Servidor Paper</h1>

                                            <div class="py-2">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="serselectver">Selecciona Versión Paper:</label>
                                                            <select id="serselectver" name="serselectver" class="form-control" required="required">
                                                                <?php

                                                                if ($elerror == 0) {
                                                                    echo '<option selected disabled hidden>No hay ningúna versión seleccionada</option>';

                                                                    for ($i = 0; $i < count($versiones); $i++) {
                                                                        echo '<option value="' . $versiones[$i] . '">Paper '  . $versiones[$i] . '</option>';
                                                                    }
                                                                } else {
                                                                    echo '<option selected disabled hidden>Error obtener listado</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <br>
                                                            <?php

                                                            if ($elerror == 1) {
                                                                $retorno = "No se pudo obtener las versiones Paper del json";
                                                                echo '<p>Error: ' . $retorno . '</p>';
                                                            }

                                                            if ($elerror == 0) {
                                                            ?>
                                                                <label for="buildversion">Selecciona Build:</label>
                                                                <select id="buildversion" name="buildversion" class="form-control" required="required"></select>
                                                                <br>
                                                                <button class="btn btn-primary btn-block mt-2" id="descargar" name="descargar" type="button">Descargar Servidor Paper</button>
                                                            <?php
                                                            }
                                                            ?>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <img class="" src="img/loading.gif" id="gifloading" alt="loading">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-2">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="lead" id="textoretorno"></p>
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

            <script src="js/paper.js"></script>

        </div>
    <?php
        //FINAL VALIDAR SESSION
    } else {
        header("location:index.php");
    }
    ?>

</body>

</html>