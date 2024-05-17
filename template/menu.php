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
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="status.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <img class="d-block mr-2" src="img/icons/favicon-32x32.png" alt="mineminiicon" width="40" height="40">
        </div>
        <div class="sidebar-brand-text">
            <?php
            $NAMESERVERMENU = CONFIGNOMBRESERVER;
            echo $NAMESERVERMENU;
            ?>
        </div>
    </a>

    <!-- Linea -->
    <hr class="sidebar-divider my-0">

    <!-- Linea -->
    <hr class="sidebar-divider">

    <!-- Encabezado -->
    <div class="sidebar-heading text-light">Servidor</div>

    <!-- Nav Item - Status -->
    <li class="nav-item menu-hover">
        <a class="nav-link" href="status.php">
            <img class="d-block float-left mr-2" src="img/menu/status.png" alt="status">
            <h6 class="text-light">Estado</h6>
        </a>
    </li>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconsolaread', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconsolaread'] == 1) {
    ?>
        <!-- Nav Item - Consola -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="consola.php">
                <img class="d-block float-left mr-2" src="img/menu/consola.png" alt="consola">
                <h6 class="text-light">Consola</h6>
            </a>
        </li>

    <?php
    }
    ?>

    <?php
    //MOSTRAR O OCULTAR EL TEXTO DE LA SECCION CONFIGURACION
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconfmine', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconfmine'] == 1 || array_key_exists('pprogtareas', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pprogtareas'] == 1) {
        //<!-- Linea -->
        echo ('<hr class="sidebar-divider">');
        //<!-- Encabezado -->
        echo ('<div class="sidebar-heading text-light">Configuración</div>');
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconfmine', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconfmine'] == 1) {
    ?>
        <!-- Nav Item - Minecraft -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="minecraft.php">
                <img class="d-block float-left mr-2" src="img/menu/config.png" alt="confmine">
                <h6 class="text-light">Config minecraft</h6>
            </a>
        </li>

    <?php
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pprogtareas', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pprogtareas'] == 1) {
    ?>
        <!-- Nav Item - Tareas -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="tareas.php">
                <img class="d-block float-left mr-2" src="img/menu/tarea.png" alt="tarea">
                <h6 class="text-light">Prog tareas</h6>
            </a>
        </li>

    <?php
    }
    ?>


    <?php
    //MOSTRAR O OCULTAR EL TEXTO DE LA SECCION SISTEMA
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('psystemconf', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconf'] == 1 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1 || array_key_exists('psubirservidor', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psubirservidor'] == 1 || array_key_exists('pbackups', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pbackups'] == 1 || array_key_exists('pgestorarchivos', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivos'] == 1) {
        //<!-- Linea -->
        echo ('<hr class="sidebar-divider">');
        //<!-- Encabezado -->
        echo ('<div class="sidebar-heading text-light">Sistema</div>');
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('psystemconf', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemconf'] == 1) {
    ?>
        <!-- Nav Item - System Config -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="sysconf.php">
                <img class="d-block float-left mr-2" src="img/menu/sysconf.png" alt="sysconf">
                <h6 class="text-light">System config</h6>
            </a>
        </li>
    <?php
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('ppagedownserver', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['ppagedownserver'] == 1) {
    ?>
        <!-- Nav Item - Descargar Servidor -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="servers.php">
                <img class="d-block float-left mr-2" src="img/menu/servers.png" alt="servers">
                <h6 class="text-light">Descargar Servidor</h6>
            </a>
        </li>
    <?php
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('psubirservidor', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psubirservidor'] == 1) {
    ?>
        <!-- Nav Item - Subir Server -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="subirserver.php">
                <img class="d-block float-left mr-2" src="img/menu/subida.png" alt="subida">
                <h6 class="text-light">Subir servidor</h6>
            </a>
        </li>
    <?php
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pbackups', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pbackups'] == 1) {
    ?>
        <!-- Nav Item - Backups -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="backups.php">
                <img class="d-block float-left mr-2" src="img/menu/backup.png" alt="backup">
                <h6 class="text-light">Backups</h6>
            </a>
        </li>
    <?php
    }
    ?>

    <?php
    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pgestorarchivos', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pgestorarchivos'] == 1) {
    ?>
        <!-- Nav Item - Gestor Archivos -->
        <li class="nav-item menu-hover">
            <a class="nav-link" href="gestorarchivos.php">
                <img class="d-block float-left mr-2" src="img/menu/carpeta.png" alt="gestarch">
                <h6 class="text-light">Gestor archivos</h6>
            </a>
        </li>
    <?php
    }
    ?>

    <?php
    if (array_key_exists('rango', $_SESSION['CONFIGUSER'])) {
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {
    ?>

            <!-- Nav Item - Gestor Usuarios -->
            <li class="nav-item menu-hover">
                <a class="nav-link" href="gestorusers.php">
                    <img class="d-block float-left mr-2" src="img/menu/users.png" alt="gestusers">
                    <h6 class="text-light">Gestor usuarios</h6>
                </a>
            </li>

    <?php
        }
    }
    ?>

    <!-- Linea -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Cerrar Session -->
    <li class="nav-item menu-hover">
        <a class="nav-link" href="salir.php">
            <img class="d-block float-left mr-2" src="img/menu/salir.png" alt="salir">
            <h6 class="text-light">Cerrar sesión</h6>
        </a>
    </li>

    <!-- Linea Final -->
    <hr class="sidebar-divider d-none d-md-block">

    <p class="text-light text-right pr-4 mini-ver">McWebAdmin<br>Ver <?php echo VERSIONPANEL . " " . VERSIONTIPO ?></p>

</ul>
