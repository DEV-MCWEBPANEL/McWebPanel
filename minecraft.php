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
<link href="css/motd.css" rel="stylesheet">

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

    //COMPROBAR SI ES EL SUPERADMIN O ADMIN O USER CON PERMISOS
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconfmine', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconfmine'] == 1) {
        $expulsar = 1;
    }

    if ($expulsar != 1) {
        header("location:index.php");
        exit;
    }

    //VALIDAMOS SESSION SINO ERROR
    if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

        function leerlineas($eltipo)
        {
            $reccarpmine = CONFIGDIRECTORIO;
            $rutacarpetamine = getcwd();
            $rutacarpetamine = trim($rutacarpetamine);
            $rutacarpetamine .= "/" . $reccarpmine . "/server.properties";

            clearstatcache();
            if (file_exists($rutacarpetamine)) {
                $gestor = @fopen($rutacarpetamine, "r");

                while (($búfer = fgets($gestor, 4096)) !== false) {
                    $str = $búfer;
                    $array = explode("=", $str);
                    $totalletras = strlen($str);
                    $lakey = strlen($array[0]);
                    $lakey++;
                    if ($array[0] == $eltipo) {
                        if ($lakey < $totalletras) {
                            $elresul = substr($str, $lakey);
                            return trim($elresul);
                        } else {
                            $vacio = "";
                            return $vacio;
                        }
                    }
                }

                if (!feof($gestor)) {
                    echo "Error: fallo inesperado de fgets()\n";
                }
                fclose($gestor);
            }
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

                                    <!-- Page Heading -->
                                    <div class="pt-3">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h2 class=""><strong>Configuración Minecraft</strong></h2>
                                                    <hr>
                                                    <p class="lead">Nota: Los cambios en la configuración no se aplican hasta que se reinicia el servidor de Minecraft.</p>
                                                    <br>
                                                    <?php
                                                    $reccarpmine = CONFIGDIRECTORIO;
                                                    $rutacarpetamine = getcwd();
                                                    $rutacarpetamine = trim($rutacarpetamine);
                                                    $rutacarpetamine .= "/" . $reccarpmine . "/server.properties";

                                                    clearstatcache();
                                                    if (!file_exists($rutacarpetamine)) {
                                                        echo '<div class="alert alert-danger" role="alert">Error: El archivo server.properties no existe.</div>';
                                                    ?>
                                                        <div class="">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button id="restablecer" type="button" class="btn btn-block btn-lg btn-danger">Restablecer configuración por defecto</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script src="js/minecraft.js"></script>
                                                    <?php
                                                        exit;
                                                    }

                                                    clearstatcache();
                                                    if (!is_readable($rutacarpetamine)) {
                                                        echo '<div class="alert alert-danger" role="alert">Error: El archivo server.properties no tiene permisos de lectura.</div>';
                                                        exit;
                                                    }

                                                    clearstatcache();
                                                    if (!is_writable($rutacarpetamine)) {
                                                        echo '<div class="alert alert-danger" role="alert">Error: El archivo server.properties no tiene permisos de escritura.</div>';
                                                        exit;
                                                    }

                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Separacion Inicio -->
                                    <div class="pt-3">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h2 class=""><strong>Opciones Juego</strong></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="pb-2 mt-2">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Modo Juego</strong></h3>
                                                    <p class="lead">Juego por defecto del servidor.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Supervivencia (survival)</p>
                                                    <select id="form-gamemode" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('Supervivencia', 'Creativo', 'Aventura', 'Espectador');
                                                        $losvalues = array('survival', 'creative', 'adventure', 'spectator');

                                                        $obtener = leerlineas('gamemode');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-gamemode" class="lead text-center text-white mt-2 bg-primary">gamemode</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Forzar Modo de juego</strong></h3>
                                                    <p class="lead">Fuerza a los jugadores a entrar con el modo de juego por defecto configurado.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-force-gamemode" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('force-gamemode');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-force-gamemode" class="lead text-center text-white mt-2 bg-primary">force-gamemode</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Dificultad</strong></h3>
                                                    <p class="lead">Dificultad del modo Supervivencia.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Fácil (easy)</p>
                                                    <select id="form-difficulty" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('Pacifico', 'Facil', 'Normal', 'Dificil');
                                                        $losvalues = array('peaceful', 'easy', 'normal', 'hard');

                                                        $obtener = leerlineas('difficulty');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-difficulty" class="lead text-center text-white mt-2 bg-primary">difficulty</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Modo Hardcore</strong></h3>
                                                    <p class="lead">Si está en true, la dificultad es ignorada y fijada en difícil y los jugadores pasan a modo espectador si mueren.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-hardcore" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('hardcore');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-hardcore" class="lead text-center text-white mt-2 bg-primary">hardcore</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>PVP</strong></h3>
                                                    <p class="lead">Los jugadores pueden matar a otros jugadores.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-pvp" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('pvp');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-pvp" class="lead text-center text-white mt-2 bg-primary">pvp</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Spawnear NPCs</strong></h3>
                                                    <p class="lead">Los NPC (Aldeanos) podrán spawnear en el mapa.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-spawn-npcs" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('spawn-npcs');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-spawn-npcs" class="lead text-center text-white mt-2 bg-primary">spawn-npcs</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Spawnear Animales</strong></h3>
                                                    <p class="lead">Los Animales (Cerdo, Vaca, etc.) podrán spawnear en el mapa.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-spawn-animals" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('spawn-animals');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-spawn-animals" class="lead text-center text-white mt-2 bg-primary">spawn-animals</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Spawnear Monstruos</strong></h3>
                                                    <p class="lead">Los Monstruos (Creepers, Arañas, etc.) podrán spawnear en el mapa.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-spawn-monsters" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('spawn-monsters');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-spawn-monsters" class="lead text-center text-white mt-2 bg-primary">spawn-monsters</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar Vuelo</strong></h3>
                                                    <p class="lead">Permite volar a los usuarios.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-allow-flight" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('allow-flight');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-allow-flight" class="lead text-center text-white mt-2 bg-primary">allow-flight</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Jugador AFK</strong></h3>
                                                    <p class="lead">Si el jugador no se mueve en el tiempo asignado (minutos), el servidor lo expulsara.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 0 (Desactivado)<br>Valor Min: 0 - Valor Max: 2147483647</p>
                                                    <input id="form-player-idle-timeout" type="number" class="form-control" min="0" max="2147483647" value="<?php echo leerlineas('player-idle-timeout'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-player-idle-timeout" class="lead text-center text-white mt-2 bg-primary">player-idle-timeout</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Paquete de recursos</strong></h3>
                                                    <p class="lead">Asignar URL al paquete de recursos.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Vacío<br><br>Tamaño Resource:<br>1.0-1.14.4 = 50 MB<br>1.15-1.17.1 = 100 MB<br>1.18 > = 250 MB</p>
                                                    <input id="form-resource-pack" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo htmlentities(leerlineas('resource-pack')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-resource-pack" class="lead text-center text-white mt-2 bg-primary">resource-pack</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Verificar Paquete de recursos usando SHA1</strong></h3>
                                                    <p class="lead">Comprueba si el SHA1 corresponde con el fichero seleccionado de la URL.<br>Se utiliza el SHA1 del fichero en hexadecimal y en minúsculas.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Vacío</p>
                                                    <input id="form-resource-pack-sha1" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo test_input(leerlineas('resource-pack-sha1')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-resource-pack-sha1" class="lead text-center text-white mt-2 bg-primary">resource-pack-sha1</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Requerir Paquete de recursos</strong></h3>
                                                    <p class="lead">Al estar en true, fuerza a requerir que tengas el paquete de recursos del servidor<br>Los usuarios que no acepten el paquete de recursos serán desconectados.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false<br>Requiere Versión: 1.17 o superior</p>
                                                    <select id="form-require-resource-pack" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('require-resource-pack');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-require-resource-pack" class="lead text-center text-white mt-2 bg-primary">require-resource-pack</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Dialogo Paquete de recursos</strong></h3>
                                                    <p class="lead">Añade un dialogo personalizado que se mostrara al solicitar el paquete de recursos cuando está activado (Opcional).</p>
                                                    <p class="lead">Ejemplo: {"text":"Linea1\nLinea2"}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Vacío<br>Requiere Versión: 1.17 o superior</p>
                                                    <input id="form-resource-pack-prompt" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo htmlentities(leerlineas('resource-pack-prompt')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-resource-pack-prompt" class="lead text-center text-white mt-2 bg-primary">resource-pack-prompt</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="pt-3">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h2 class=""><strong>Opciones Mapa</strong></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Nombre Mapa</strong></h3>
                                                    <p class="lead">Nombre con el que se creara el mapa principal.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: world</p>
                                                    <input id="form-level-name" type="text" class="form-control" maxlength="255" spellcheck="false" autocapitalize="none" value="<?php echo test_input(leerlineas('level-name')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-level-name" class="lead text-center text-white mt-2 bg-primary">level-name</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Semilla del mapa</strong></h3>
                                                    <p class="lead">Semilla para generación de mapas.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: aleatorio</p>
                                                    <input id="form-level-seed" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo test_input(leerlineas('level-seed')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-level-seed" class="lead text-center text-white mt-2 bg-primary">level-seed</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Tipo Mapa</strong></h3>
                                                    <p class="lead">Determina el tipo de mapa que se generara.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: default<br>Requiere Versión: 1.15 o superior</p>
                                                    <select id="form-level-type" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('Standard', 'Plano', 'Biomas Largos', 'Amplificado', 'Un bioma (Requiere 1.15 o superior)', 'Standard (Version 1.19 o superior)', 'Plano (Version 1.19 o superior)', 'Biomas Largos (Version 1.19 o superior)', 'Amplificado (Version 1.19 o superior)', 'Un bioma (Version 1.19 o superior)');
                                                        $losvalues = array('default', 'flat', 'largebiomes', 'amplified', 'buffet', 'minecraft\:normal', 'minecraft\:flat', 'minecraft\:large_biomes', 'minecraft\:amplified', 'minecraft\:single_biome_surface');

                                                        $obtener = leerlineas('level-type');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-level-type" class="lead text-center text-white mt-2 bg-primary">level-type</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Configuración Generación Mapa</strong></h3>
                                                    <p class="lead">Configuración utilizada para personalizar la generación del mapa.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Vacío</p>
                                                    <input id="form-generator-settings" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo htmlentities(leerlineas('generator-settings')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-generator-settings" class="lead text-center text-white mt-2 bg-primary">generator-settings</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Algoritmo Compresión Archivos-Región</strong></h3>
                                                    <p class=""><br>-deflate: La opción por defecto, se utiliza el antiguo algoritmo.
                                                        <br><br>-lz4: Este algoritmo requiere menos tiempo de CPU para comprimir/descomprimir, pero usa más espacio en el disco.
                                                        <br><br>-none: Este algoritmo requiere más espacio en el disco y requiere más tiempo de lectura/escritura, apenas hace uso de la CPU.
                                                        <br><br>Atención: Cambiar el algoritmo no comprimirá de nuevo todos los chunks automáticamente, solo los nuevos chunks que se generen o chucks actualizados usarán el nuevo algoritmo, pero los antiguos se mantendrán en el antiguo formato.
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: deflate<br>Requiere Versión: 1.20.5 o superior</p>
                                                    <select id="form-region-file-compression" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('deflate', 'lz4', 'none');
                                                        $losvalues = array('deflate', 'lz4', 'none');

                                                        $obtener = leerlineas('region-file-compression');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-region-file-compression" class="lead text-center text-white mt-2 bg-primary">region-file-compression</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Altura Máxima Construir</strong></h3>
                                                    <p class="lead">Determina la altura máxima que se podrá construir.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 256<br>Valor Min: 8 Valor Max: 256<br>Opción eliminada en Minecraft 1.17</p>
                                                    <input id="form-max-build-height" type="number" class="form-control" min="8" max="256" value="<?php echo leerlineas('max-build-height'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-max-build-height" class="lead text-center text-white mt-2 bg-primary">max-build-height</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Generar Estructuras</strong></h3>
                                                    <p class="lead">Se generarán estructuras (Aldeas, edificios, etc.) por el mapa.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-generate-structures" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('generate-structures');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-generate-structures" class="lead text-center text-white mt-2 bg-primary">generate-structures</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Habilitar Nether</strong></h3>
                                                    <p class="lead">Activar el Nether y sus portales.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-allow-nether" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('allow-nether');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-allow-nether" class="lead text-center text-white mt-2 bg-primary">allow-nether</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Distancia para renderizar entidades</strong></h3>
                                                    <p class="lead">Ajustar la distancia de renderizado de entidades, si es muy lejano puede causar lag.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 100<br>Valor Min: 10 - Valor Max: 1000</p>
                                                    <input id="form-entity-broadcast-range-percentage" type="number" class="form-control" min="10" max="1000" value="<?php echo leerlineas('entity-broadcast-range-percentage'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-entity-broadcast-range-percentage" class="lead text-center text-white mt-2 bg-primary">entity-broadcast-range-percentage</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Distancia Simulación</strong></h3>
                                                    <p class="lead">Distancia en chunks en la que los objetos como hornos, granjas, plantas quedan cargados y funcionando aunque no estés cerca.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 10<br>Valor Min: 5 - Valor Max: 32<br>Requiere Versión: 1.18 o superior</p>
                                                    <input id="form-simulation-distance" type="number" class="form-control" min="5" max="32" value="<?php echo leerlineas('simulation-distance'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-simulation-distance" class="lead text-center text-white mt-2 bg-primary">simulation-distance</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Protección Spawn</strong></h3>
                                                    <p class="lead">Asignas el radio de protección al punto spawn del mapa.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 16<br>Valor Min: 0 - Valor Max: 16<br>Valor 0: Deshabilitar protección</p>
                                                    <input id="form-spawn-protection" type="number" class="form-control" min="0" max="16" value="<?php echo leerlineas('spawn-protection'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-spawn-protection" class="lead text-center text-white mt-2 bg-primary">spawn-protection</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Tamaño Máximo Mundo (en bloques)</strong></h3>
                                                    <p class="lead">Asignas el tamaño máximo del mundo, no se podrá caminar al llegar al límite</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 29999984<br>Valor Min: 1 - Valor Max: 29999984</p>
                                                    <input id="form-max-world-size" type="number" class="form-control" min="1" max="29999984" value="<?php echo leerlineas('max-world-size'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-max-world-size" class="lead text-center text-white mt-2 bg-primary">max-world-size</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="pt-3">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h2 class=""><strong>Opciones Servidor</strong></h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Modo Online (PREMIUM)</strong></h3>
                                                    <p class="lead">Activar o Desactivar el modo Legal (True) o Pirata (False).</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-online-mode" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('online-mode');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-online-mode" class="lead text-center text-white mt-2 bg-primary">online-mode</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Máximo Jugadores</strong></h3>
                                                    <p class="lead">Asignas el máximo de jugadores que podrán entrar al servidor.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 20<br>Valor Min: 1 - Valor Max: 2147483647</p>
                                                    <input id="form-max-players" type="number" class="form-control" min="1" max="2147483647" value="<?php echo leerlineas('max-players'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-max-players" class="lead text-center text-white mt-2 bg-primary">max-players</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Ocultar Jugadores Online</strong></h3>
                                                    <p class="lead">El servidor no devuelve la lista de jugadores online.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false<br>Requiere Versión: 1.18 o superior</p>
                                                    <select id="form-hide-online-players" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True (Requiere 1.18 o superior)');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('hide-online-players');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-hide-online-players" class="lead text-center text-white mt-2 bg-primary">hide-online-players</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar bloque de comandos</strong></h3>
                                                    <p class="lead">Activa el bloque de comandos en el servidor.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-enable-command-block" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('enable-command-block');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enable-command-block" class="lead text-center text-white mt-2 bg-primary">enable-command-block</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar Solicitudes Query</strong></h3>
                                                    <p class="lead">Responde a las solicitudes Query de Servidores y Programas.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-enable-query" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('enable-query');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enable-query" class="lead text-center text-white mt-2 bg-primary">enable-query</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Puerto Query</strong></h3>
                                                    <p class="lead">Establece el puerto para Query.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 25565<br>Valor Min: 1025 - Valor Max: 65535</p>
                                                    <input id="form-query-port" type="number" class="form-control" min="1025" max="65535" value="<?php echo leerlineas('query.port'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-query-port" class="lead text-center text-white mt-2 bg-primary">query.port</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar RCON</strong></h3>
                                                    <p class="lead">Activa protocolo RCON.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-enable-rcon" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('enable-rcon');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enable-rcon" class="lead text-center text-white mt-2 bg-primary">enable-rcon</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Puerto RCON</strong></h3>
                                                    <p class="lead">Establece el puerto de red RCON.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 25575<br>Valor Min: 1025 - Valor Max: 65535</p>
                                                    <input id="form-rconport" type="number" class="form-control" min="1025" max="65535" value="<?php echo leerlineas('rcon.port'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-rconport" class="lead text-center text-white mt-2 bg-primary">rcon.port</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Password RCON</strong></h3>
                                                    <p class="lead">Fijar el password que usaras al conectarte con RCON.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Vacío</p>
                                                    <input id="form-rcon-password" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo htmlentities(leerlineas('rcon.password')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-rcon-password" class="lead text-center text-white mt-2 bg-primary">rcon.password</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Forzar Perfil Seguro</strong></h3>
                                                    <p class="lead">Si se establece en verdadero, los jugadores sin una clave pública firmada por Mojang no podrán conectarse al servidor.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false<br>Requiere Versión: 1.19 o superior</p>
                                                    <select id="form-enforce-secure-profile" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('enforce-secure-profile');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enforce-secure-profile" class="lead text-center text-white mt-2 bg-primary">enforce-secure-profile</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar Lista Blanca</strong></h3>
                                                    <p class="lead">La lista blanca solo permitirá entrar a los usuarios que estén en ella cuando esté activada.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-white-list" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('white-list');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-white-list" class="lead text-center text-white mt-2 bg-primary">white-list</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Forzar Lista Blanca</strong></h3>
                                                    <p class="lead">Cuando está en true, los usuarios que no están añadidos en la lista blanca (si está esta habilitada) son expulsados ​​del servidor después de que el servidor vuelve a cargar el archivo de la lista blanca.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-enforce-whitelist" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('enforce-whitelist');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enforce-whitelist" class="lead text-center text-white mt-2 bg-primary">enforce-whitelist</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Ip del Server</strong></h3>
                                                    <p class="lead">Fijar el servidor obligatoriamente a una IP, se recomienda dejarla en blanco.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: Vacío</p>
                                                    <input id="form-server-ip" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php echo test_input(leerlineas('server-ip')); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-server-ip" class="lead text-center text-white mt-2 bg-primary">server-ip</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar Estado</strong></h3>
                                                    <p class="lead">Hace aparecer el servidor 'online' en la lista de servidores.<br>Si está en falso, suprimirá las respuestas de los clientes. Esto significa que aparecerá como fuera de línea, pero seguirá aceptando conexiones.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-enable-status" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('enable-status');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enable-status" class="lead text-center text-white mt-2 bg-primary">enable-status</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>broadcast-console-to-ops</strong></h3>
                                                    <p class="lead">Envía los resultados de los comandos de consola a todos los operadores.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-broadcast-console-to-ops" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('broadcast-console-to-ops');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-broadcast-console-to-ops" class="lead text-center text-white mt-2 bg-primary">broadcast-console-to-ops</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>broadcast-rcon-to-ops</strong></h3>
                                                    <p class="lead">Envía los resultados de los comandos de consola mediante rcon a todos los operadores.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-broadcast-rcon-to-ops" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('broadcast-rcon-to-ops');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p id="label-broadcast-rcon-to-ops" class="lead text-center text-white mt-2 bg-primary">broadcast-rcon-to-ops</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Usar Transporte Nativo</strong></h3>
                                                    <p class="lead">Mejoras en el rendimiento Linux: envío y recepción de paquetes optimizados.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true</p>
                                                    <select id="form-use-native-transport" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('use-native-transport');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-use-native-transport" class="lead text-center text-white mt-2 bg-primary">use-native-transport</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Vista Previa De Chat</strong></h3>
                                                    <p class="lead">Al habilitarse, los servidores pueden obtener una vista previa de los mensajes con estilos aplicados, como emojis o colores de chat.</p>
                                                    <p class="lead">Vista previa de chat envía mensajes de chat al servidor a medida que se escriben, incluso antes de que se envíen</p>
                                                    <p class="lead">Se muestra una pantalla de advertencia en el cliente cuando se une a un servidor con Vista previa de chat activado, y se puede desactivar globalmente en la configuración de chat</p>
                                                    <p class="lead">El estilo de chat dinámico también puede ser controlado por el servidor, aunque esto solo se firma cuando la vista previa de chat está habilitada.</p>
                                                    <p class="lead">Los clientes pueden preferir mostrar siempre el mensaje original firmado, habilitando "Mostrar solo el chat firmado" en la configuración del chat.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false<br>Requiere Versión: 1.19 o superior</p>
                                                    <select id="form-previews-chat" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('previews-chat');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-previews-chat" class="lead text-center text-white mt-2 bg-primary">previews-chat</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Prevenir Conexiones Proxy</strong></h3>
                                                    <p class="lead">Si el ISP/AS enviado desde el servidor es diferente al del Servidor Autentificación de Mojang, el jugador es kickeado.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false</p>
                                                    <select id="form-prevent-proxy-connections" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('prevent-proxy-connections');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-prevent-proxy-connections" class="lead text-center text-white mt-2 bg-primary">prevent-proxy-connections</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar Monitoreo JMX</strong></h3>
                                                    <p class="lead">Monitorear los tiempos de tick del servidor(averageTickTime y tickTimes).</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: false<br>Requiere Versión: 1.16 o superior</p>
                                                    <select id="form-enable-jmx-monitoring" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('False', 'True (Requiere 1.16 o superior)');
                                                        $losvalues = array('false', 'true');

                                                        $obtener = leerlineas('enable-jmx-monitoring');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-enable-jmx-monitoring" class="lead text-center text-white mt-2 bg-primary">enable-jmx-monitoring</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Estadísticas Mojang</strong></h3>
                                                    <p class="lead">Envía estadísticas del Servidor a Mojang.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: True<br>Opción eliminada en Minecraft 1.18</p>
                                                    <select id="form-snooper-enabled" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('snooper-enabled');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-snooper-enabled" class="lead text-center text-white mt-2 bg-primary">snooper-enabled</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Activar Modo Guardado Syncronizado Chunks</strong></h3>
                                                    <p class="lead">Modo Sincronizado previene la perdida de datos y corrupción después de un crasheo.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: true<br>Requiere Versión: 1.16 o superior</p>
                                                    <select id="form-sync-chunk-writes" class="form-control w-100">
                                                        <?php
                                                        $lostextos = array('True (Requiere 1.16 o superior)', 'False');
                                                        $losvalues = array('true', 'false');

                                                        $obtener = leerlineas('sync-chunk-writes');

                                                        if ($obtener == "") {
                                                            echo '<option selected hidden>No hay ninguna opción seleccionada</option>';
                                                        }

                                                        for ($i = 0; $i < count($lostextos); $i++) {

                                                            if ($obtener == $losvalues[$i]) {
                                                                echo '<option value="' . $losvalues[$i] . '" selected>' . $lostextos[$i] . '</option>';
                                                            } else {
                                                                echo '<option value="' . $losvalues[$i] . '">' . $lostextos[$i] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-sync-chunk-writes" class="lead text-center text-white mt-2 bg-primary">sync-chunk-writes</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Limite Actualizaciones Consecutivas Proximas</strong></h3>
                                                    <p class="lead">Limitar la cantidad de actualizaciones consecutivas proximas antes de omitir las adicionales.<br>Los valores negativos eliminan el límite.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 1000000<br>Valor Min: 1 - Valor Max: 2147483647<br>Valor eliminar límite: -1<br>Requiere Versión: 1.19 o superior</p>
                                                    <input id="form-max-chained-neighbor-updates" type="number" class="form-control" min="-1" max="2147483647" value="<?php echo leerlineas('max-chained-neighbor-updates'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-max-chained-neighbor-updates" class="lead text-center text-white mt-2 bg-primary">max-chained-neighbor-updates</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Tiempo Máximo Respuesta</strong></h3>
                                                    <p class="lead">Los segundos que tardara en cerrar el servidor si no responde en X segundos.<br> (60000 = 60 segundos).</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 60000<br>Valor Min: 1000 - Valor Max: 300000<br>Valor Deshabilitar: -1</p>
                                                    <input id="form-max-tick-time" type="number" class="form-control" min="-1" max="60000" value="<?php echo leerlineas('max-tick-time'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-max-tick-time" class="lead text-center text-white mt-2 bg-primary">max-tick-time</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>op-permission-level</strong></h3>
                                                    <p class="lead">Asigna el permiso por defecto para operadores.</p>
                                                    <p class="">Valor 1: Ops pueden hacer bypass a la protección del spawn.
                                                        <br>Valor 2: Ops pueden usar todos los comandos de un jugador, /debug y bloques de comandos.
                                                        <br>Valor 3: Ops pueden usar la mayoría de comandos multijugador incluidos /ban /op, etc...
                                                        <br>Valor 4: Ops pueden usar todos los comandos incluso /stop /save-all /save-on y /save-off
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 4<br>Valor Min: 1 - Valor Max: 4</p>
                                                    <input id="form-op-permission-level" type="number" class="form-control" min="1" max="4" value="<?php echo leerlineas('op-permission-level'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-op-permission-level" class="lead text-center text-white mt-2 bg-primary">op-permission-level</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>function-permission-level</strong></h3>
                                                    <p class="lead">Asigna el permiso por defecto para funciones.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 2<br>Valor Min: 1 - Valor Max: 4</p>
                                                    <input id="form-function-permission-level" type="number" class="form-control" min="1" max="4" value="<?php echo leerlineas('function-permission-level'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-function-permission-level" class="lead text-center text-white mt-2 bg-primary">function-permission-level</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Limite de Paquetes</strong></h3>
                                                    <p class="lead">Permite expulsar jugadores que constantemente están enviando demasiados paquetes en cuestión de segundos.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 0 (Sin limité)<br>Requiere Versión: 1.16.2 o superior</p>
                                                    <input id="form-rate-limit" type="number" class="form-control" min="0" value="<?php echo leerlineas('rate-limit'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-rate-limit" class="lead text-center text-white mt-2 bg-primary">rate-limit</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Compresión de red</strong></h3>
                                                    <p class="lead">Permite comprimir los paquetes de red del servidor.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 256<br>Valor Min: 64 - Valor Max: 256<br>Valor 0: Comprimir todo<br>Valor -1: Deshabilitar la compresión</p>
                                                    <input id="form-network-compression-threshold" type="number" class="form-control" min="-1" max="256" value="<?php echo leerlineas('network-compression-threshold'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-network-compression-threshold" class="lead text-center text-white mt-2 bg-primary">network-compression-threshold</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Distancia Visionado</strong></h3>
                                                    <p class="lead">Aumentará la distancia de visionado cargando más chunks desde la posición donde mira el jugador.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: 10<br>Valor Min: 3 - Valor Max: 32</p>
                                                    <input id="form-view-distance" type="number" class="form-control" min="3" max="32" value="<?php echo leerlineas('view-distance'); ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <p id="label-view-distance" class="lead text-center text-white mt-2 bg-primary">view-distance</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>MOTD</strong></h3>
                                                    <p class="lead">Mensaje que se muestra en la lista de servidores del cliente.</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="">Valor Defecto: A Minecraft Server</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div>
                                                        <br>
                                                        <input id="form-motd" type="text" class="form-control" spellcheck="false" autocapitalize="none" value="<?php
                                                                                                                                                                $elmotd = "";
                                                                                                                                                                $elmotd = leerlineas('motd');
                                                                                                                                                                $elmotd = str_replace("<?php", htmlentities("<?php"), $elmotd);
                                                                                                                                                                echo $elmotd;
                                                                                                                                                                ?>">
                                                        <br>
                                                    </div>
                                                    <p id="label-motd" class="lead text-center text-white mt-2 bg-primary">motd</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h3 class=""><strong>Visor MOTD</strong></h3>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 ml-2 ">
                                                    <div class="row minirow">
                                                        <div class="bg-dark iconservlist"></div>
                                                        <div class="imgservlist"><br><span id="visormotd"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Separacion Fin -->
                                    <hr>
                                    <!-- Separacion Inicio -->
                                    <div class="">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button id="restablecer" type="button" class="btn btn-block btn-lg btn-danger">Restablecer configuración por defecto</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Separacion Fin -->
                                    <hr>

                                </div>

                            </div>
                        </div>
                        <br>
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

        <script src="js/minecraft.js"></script>

    <?php
        //FINAL VALIDAR SESSION
    } else {
        header("location:index.php");
    }
    ?>
</body>

</html>