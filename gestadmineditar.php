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
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2) {
            $expulsar = 1;
        }
    }

    if ($expulsar != 1) {
        header("Location:index.php");
        exit;
    }


    if (!isset($_SESSION['EDITARSUPER'])) {
        header("Location:gestorusers.php");
        exit;
    } else {
        if ($_SESSION['EDITARSUPER'] == 0) {
            header("Location:gestorusers.php");
            exit;
        }
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
                                    <div class="py-1">
                                        <div class="container">
                                            <?php
                                            if ($_SESSION['EDITARSUPER']['rango'] == 1) {
                                                echo '<h1 class="mb-5">Editar Superusuario</h1>';
                                            } elseif ($_SESSION['EDITARSUPER']['rango'] == 2) {
                                                echo '<h1 class="mb-5">Editar Administrador</h1>';
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <form id="formeditadmin" action="function/gestadmineditaradmin.php" method="post" autocomplete="off">
                                                        <div class="py-1">
                                                            <div class="container">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="negrita">Nombre Usuario</label>
                                                                            <br>
                                                                            <h4>
                                                                                <?php
                                                                                echo $_SESSION['EDITARSUPER']['usuario'];
                                                                                ?>
                                                                            </h4>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                    if ($_SESSION['EDITARSUPER']['rango'] == 1 && $_SESSION['EDITARSUPER']['usuario'] == $_SESSION['CONFIGUSER']['usuario'] || $_SESSION['EDITARSUPER']['rango'] == 2 && $_SESSION['EDITARSUPER']['usuario'] == $_SESSION['CONFIGUSER']['usuario']) {
                                                                    ?>
                                                                        <div class="col-md-6">
                                                                            <label class="negrita" for="eloldpass">Antigua Contraseña</label>
                                                                            <input type="password" class="form-control" id="eloldpass" name="eloldpass" spellcheck="false" autocapitalize="none" placeholder="••••">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <div class="col-md-6">
                                                                        <br>
                                                                        <label class="negrita" for="elpass">Nueva Contraseña</label>
                                                                        <input type="password" class="form-control" id="elpass" name="elpass" spellcheck="false" autocapitalize="none" placeholder="••••">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <br>
                                                                        <label class="negrita" for="elrepass">Confirmar Contraseña</label>
                                                                        <input type="password" class="form-control" id="elrepass" name="elrepass" spellcheck="false" autocapitalize="none" placeholder="••••">
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <label>
                                                                            <input type="checkbox" name="verpassword" id="verpassword"> Mostrar contraseñas
                                                                        </label>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <p class="lead" id="textoretorno"></p>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label class="negrita">Seleccionar Tema Web:</label>
                                                                        <select id="selectemaweb" name="selectemaweb" class="form-control">
                                                                            <?php
                                                                            if (array_key_exists('psystemconftemaweb', $_SESSION['EDITARSUPER'])) {
                                                                                if ($_SESSION['EDITARSUPER']['psystemconftemaweb'] == 2) {
                                                                            ?>
                                                                                    <option value="1">Claro</option>
                                                                                    <option value="2" selected>Oscuro</option>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <option value="1" selected>Claro</option>
                                                                                    <option value="2">Oscuro</option>
                                                                                <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <option value="1" selected>Claro</option>
                                                                                <option value="2">Oscuro</option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                    <?php
                                                                    //SOLO MUESTRA LAS OPCIONES SI EDITAS UN ADMIN
                                                                    if ($_SESSION['EDITARSUPER']['rango'] == 2 &&  $_SESSION['CONFIGUSER']['rango'] == 1) {
                                                                    ?>

                                                                        <div class="col-md-6">
                                                                            <label class="negrita">Permiso creación/edición usuarios:</label>
                                                                            <select id="psystemcreateuser" name="psystemcreateuser" class="form-control">
                                                                                <?php
                                                                                if (array_key_exists('psystemcreateuser', $_SESSION['EDITARSUPER']) && $_SESSION['EDITARSUPER']['psystemcreateuser'] == 1) {
                                                                                    echo '<option value="1">No</option>';
                                                                                    echo '<option value="2" selected>Si</option>';
                                                                                } else {
                                                                                    echo '<option value="1" selected>No</option>';
                                                                                    echo '<option value="2">Si</option>';
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <br>
                                                                            <label class="negrita">Asignar Permisos:</label>
                                                                            <br><br>

                                                                            <!-- SYSTEM CONFIG -->
                                                                            <div class="negrita card-header text-white bg-primary">Página System Config
                                                                                <span class="botselectpeque botselectpeque-hover ml-2 float-right" id="deselecionarpsysconf" name="deselecionarpsysconf">Desactivar todo</span>
                                                                                <span class="botselectpeque botselectpeque-hover float-right" id="selectodaspsysconf" name="selectodaspsysconf">Activar todo</span>
                                                                            </div>
                                                                            <div class="card-body border">

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfpuerto" name="psystemconfpuerto" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemconfpuerto', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfpuerto'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfpuerto">Puerto</label>
                                                                                    </div>
                                                                                    <p>Permite cambiar el puerto del servidor de minecraft.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfmemoria" name="psystemconfmemoria" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemconfmemoria', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfmemoria'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfmemoria">Memoria</label>
                                                                                    </div>
                                                                                    <p>Permite cambiar la memoria máxima del servidor.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconftipo" name="psystemconftipo" type="checkbox" value="1" <?php

                                                                                                                                                                        if (array_key_exists('psystemconftipo', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                            if ($_SESSION['EDITARSUPER']['psystemconftipo'] == 1) {
                                                                                                                                                                                echo "checked";
                                                                                                                                                                            }
                                                                                                                                                                        }
                                                                                                                                                                        ?>>

                                                                                        <label class="negrita mr-2" for="psystemconftipo">Tipo Servidor</label>
                                                                                    </div>
                                                                                    <p>Permite cambiar el tipo de servidor.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfsubida" name="psystemconfsubida" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemconfsubida', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfsubida'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfsubida">Limite Subida Archivos</label>
                                                                                    </div>
                                                                                    <p>Permite cambiar el tamaño máximo de subida de archivos.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfnombre" name="psystemconfnombre" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemconfnombre', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfnombre'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfnombre">Nombre Servidor</label>
                                                                                    </div>
                                                                                    <p>Permite cambiar el nombre del servidor.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfzonahoraria" name="psystemconfzonahoraria" type="checkbox" value="1" <?php

                                                                                                                                                                                    if (array_key_exists('psystemconfzonahoraria', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                        if ($_SESSION['EDITARSUPER']['psystemconfzonahoraria'] == 1) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfzonahoraria">Zona Horaria</label>
                                                                                    </div>
                                                                                    <p>Permite cambiar la zona horaria del servidor.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfavanzados" name="psystemconfavanzados" type="checkbox" value="1" <?php

                                                                                                                                                                                if (array_key_exists('psystemconfavanzados', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                    if ($_SESSION['EDITARSUPER']['psystemconfavanzados'] == 1) {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    }
                                                                                                                                                                                }
                                                                                                                                                                                ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfavanzados">Parametros Avanzados</label>
                                                                                    </div>
                                                                                    <p>Permite administrar las opciones de lanzamiento del servidor minecraft.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfjavaselect" name="psystemconfjavaselect" type="checkbox" value="1" <?php

                                                                                                                                                                                    if (array_key_exists('psystemconfjavaselect', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                        if ($_SESSION['EDITARSUPER']['psystemconfjavaselect'] == 1) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfjavaselect">Selector JAVA</label>
                                                                                    </div>
                                                                                    <p>Permite configurar la versión de JAVA que usara el servidor minecraft.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconffoldersize" name="psystemconffoldersize" type="checkbox" value="1" <?php

                                                                                                                                                                                    if (array_key_exists('psystemconffoldersize', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                        if ($_SESSION['EDITARSUPER']['psystemconffoldersize'] == 1) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>

                                                                                        <label class="negrita mr-2" for="psystemconffoldersize">Limite Almacenamiento</label>
                                                                                    </div>
                                                                                    <p>Permite configurar los GB de espacio disponible en las carpetas del servidor.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconflinconsole" name="psystemconflinconsole" type="checkbox" value="1" <?php

                                                                                                                                                                                    if (array_key_exists('psystemconflinconsole', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                        if ($_SESSION['EDITARSUPER']['psystemconflinconsole'] == 1) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>

                                                                                        <label class="negrita mr-2" for="psystemconflinconsole">Líneas Consola</label>
                                                                                    </div>
                                                                                    <p>Permite configurar el máximo de líneas que se mostraran en la consola.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfbuffer" name="psystemconfbuffer" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemconfbuffer', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfbuffer'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfbuffer">Buffer Consola</label>
                                                                                    </div>
                                                                                    <p>Permite configurar el máximo de líneas que guarda el buffer en la consola.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconftypeconsole" name="psystemconftypeconsole" type="checkbox" value="1" <?php

                                                                                                                                                                                    if (array_key_exists('psystemconftypeconsole', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                        if ($_SESSION['EDITARSUPER']['psystemconftypeconsole'] == 1) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        }
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>

                                                                                        <label class="negrita mr-2" for="psystemconftypeconsole">Tipo Consola</label>
                                                                                    </div>
                                                                                    <p>Permite configurar el tipo de output de la consola.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfbackup" name="psystemconfbackup" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemconfbackup', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfbackup'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfbackup">Configurar Opciones Backup</label>
                                                                                    </div>
                                                                                    <p>Permite configurar las opciones para la generación de los backups.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemstartonboot" name="psystemstartonboot" type="checkbox" value="1" <?php

                                                                                                                                                                            if (array_key_exists('psystemstartonboot', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemstartonboot'] == 1) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                }
                                                                                                                                                                            }
                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemstartonboot">Iniciar servidor Minecraft al arrancar Linux</label>
                                                                                    </div>
                                                                                    <p>Permite iniciar servidor Minecraft automáticamente al arrancar el servidor Linux.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemcustomarg" name="psystemcustomarg" type="checkbox" value="1" <?php

                                                                                                                                                                        if (array_key_exists('psystemcustomarg', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                            if ($_SESSION['EDITARSUPER']['psystemcustomarg'] == 1) {
                                                                                                                                                                                echo "checked";
                                                                                                                                                                            }
                                                                                                                                                                        }
                                                                                                                                                                        ?>>

                                                                                        <label class="negrita mr-2" for="psystemcustomarg">Argumentos Java</label>
                                                                                    </div>
                                                                                    <p>Permite añadir argumentos personalizados en Java tanto al inicio como al final.</p>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <div>
                                                                                        <input id="psystemconfignoreramlimit" name="psystemconfignoreramlimit" type="checkbox" value="1" <?php

                                                                                                                                                                                            if (array_key_exists('psystemconfignoreramlimit', $_SESSION['EDITARSUPER'])) {
                                                                                                                                                                                                if ($_SESSION['EDITARSUPER']['psystemconfignoreramlimit'] == 1) {
                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                }
                                                                                                                                                                                            }
                                                                                                                                                                                            ?>>

                                                                                        <label class="negrita mr-2" for="psystemconfignoreramlimit">Ignorar limites RAM sistema</label>
                                                                                    </div>
                                                                                    <p>Permite iniciar servidor Minecraft ignorando los limites de RAM del sistema.</p>
                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    <?php
                                                                    }
                                                                    ?>

                                                                    <div class="col-md-12">
                                                                        <br>
                                                                        <button class="btn btn-lg btn-primary btn-block" id="btcrearusuario" name="btcrearusuario" type="submit"><?php
                                                                                                                                                                                    if ($_SESSION['EDITARSUPER']['rango'] == 1) {
                                                                                                                                                                                        echo "Editar Superusuario";
                                                                                                                                                                                    } elseif ($_SESSION['EDITARSUPER']['rango'] == 2) {
                                                                                                                                                                                        echo "Editar Administrador";
                                                                                                                                                                                    }
                                                                                                                                                                                    ?></button>
                                                                        <button class="btn btn-lg btn-secondary btn-block" id="btcancelar" name="btcancelar" type="button">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                            <?php
                                            $_SESSION['SEGEDITARSUPER'] = $_SESSION['EDITARSUPER'];
                                            $_SESSION['EDITARSUPER'] = 0;
                                            ?>
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

            <script src="js/gestadmineditar.js"></script>

        </div>
    <?php
        //FINAL VALIDAR SESSION
    } else {
        header("Location:index.php");
    }
    ?>

</body>

</html>