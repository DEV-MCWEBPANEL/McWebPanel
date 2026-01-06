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

    //COMPROBAR SI ES EL SUPERADMIN O ADMIN
    if (array_key_exists('rango', $_SESSION['CONFIGUSER'])) {
        if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || $_SESSION['CONFIGUSER']['rango'] == 3) {
            $expulsar = 1;
        }
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
                                    <div class="py-1">
                                        <div class="container">
                                            <h1 class="mb-5">Gestor Usuarios</h1>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <?php

                                                        $dirconfig = getcwd() . PHP_EOL;
                                                        $dirconfig = trim($dirconfig);
                                                        $dirconfig .= "/config/confuser.json";

                                                        //COMPROBAR SI EXISTE EL JSON
                                                        clearstatcache();
                                                        if (!file_exists($dirconfig)) {
                                                            echo "<div class='alert alert-danger' role='alert'>Error: El archivo de usuarios no existe.</div>";
                                                            exit;
                                                        }

                                                        //COMPROBAR SI SE PUEDE LEER EL JSON
                                                        clearstatcache();
                                                        if (!is_readable($dirconfig)) {
                                                            echo "<div class='alert alert-danger' role='alert'>Error: El archivo de usuarios no tiene permisos de lectura.</div>";
                                                            exit;
                                                        }

                                                        //COMPROBAR SI SE PUEDE ESCRIBIR EL JSON
                                                        clearstatcache();
                                                        if (!is_writable($dirconfig)) {
                                                            echo "<div class='alert alert-danger' role='alert'>Error: El archivo de usuarios no tiene permisos de escritura.</div>";
                                                            exit;
                                                        }

                                                        //LEER ARRAY
                                                        $getarray = file_get_contents($dirconfig);
                                                        $arrayobtenido = unserialize($getarray);

                                                        //ORDENAR ARRAY
                                                        $arrayadmins = array();
                                                        $arrayusers = array();
                                                        $arrayordenar = array();

                                                        $arrayordenar[] = $arrayobtenido[0];

                                                        for ($i = 1; $i < count($arrayobtenido); $i++) {
                                                            if ($arrayobtenido[$i]['rango'] == 2) {
                                                                $arrayadmins[] = $arrayobtenido[$i];
                                                            } elseif ($arrayobtenido[$i]['rango'] == 3) {
                                                                $arrayusers[] = $arrayobtenido[$i];
                                                            }
                                                        }

                                                        $arrayordenar = array_merge($arrayordenar, $arrayadmins, $arrayusers);
                                                        $arrayobtenido = $arrayordenar;

                                                        unset($arrayordenar);
                                                        unset($arrayadmins);
                                                        unset($arrayusers);

                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1) {
                                                        ?>
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-primary btn-block btn-lg text-white" id="crearadmin">Crear Administrador</button>
                                                            </div>
                                                        <?php
                                                        }

                                                        if ($_SESSION['CONFIGUSER']['rango'] == 1) {
                                                            echo '<div class="col-md-6">';
                                                            echo '<button type="button" class="btn btn-primary btn-block btn-lg text-white" id="crearuser">Crear Nuevo Usuario</button>';
                                                            echo '</div>';
                                                        } elseif ($_SESSION['CONFIGUSER']['rango'] == 2) {
                                                            if (array_key_exists('psystemcreateuser', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemcreateuser'] == 1) {
                                                                echo '<div class="col-md-6">';
                                                                echo '<button type="button" class="btn btn-primary btn-block btn-lg text-white" id="crearuser">Crear Nuevo Usuario</button>';
                                                                echo '</div>';
                                                            }
                                                        }
                                                        ?>
                                                    </div>

                                                    <div class="py-3">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <br>
                                                                    <h3>Listado Usuarios</h3>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-striped table-borderless">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="col">Usuario</th>
                                                                                    <th scope="col">Rango</th>
                                                                                    <th scope="col">Estado</th>
                                                                                    <th scope="col">Acciones</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                                <?php

                                                                                $_SESSION['EDITARUSUARIO'] = 0;
                                                                                $_SESSION['EDITARSUPER'] = 0;

                                                                                //LEER USUARIOS COMO SUPERADMIN
                                                                                if ($_SESSION['CONFIGUSER']['rango'] == 1) {
                                                                                    for ($i = 0; $i < count($arrayobtenido); $i++) {

                                                                                        echo '<tr class = "menu-hover">';
                                                                                        echo '<th scope="row">' . $arrayobtenido[$i]['usuario'] . '</th>';
                                                                                        echo '<td>';

                                                                                        if ($arrayobtenido[$i]['rango'] == 1) {
                                                                                            echo "Superusuario";
                                                                                        } elseif ($arrayobtenido[$i]['rango'] == 2) {
                                                                                            echo "Administrador";
                                                                                        } elseif ($arrayobtenido[$i]['rango'] == 3) {
                                                                                            echo "Usuario";
                                                                                        }

                                                                                        echo '</td>';

                                                                                        echo '<td>' . $arrayobtenido[$i]['estado'] . '</td>';

                                                                                        echo '<td>';
                                                                                        if ($arrayobtenido[$i]['rango'] != 1) {
                                                                                            echo '<button type="button" class="actdesuser btn btn-info text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Activar/Desactivar Usuario">Activar/Desactivar</button>';
                                                                                        }
                                                                                        echo '<button type="button" class="edituser btn btn-warning text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Editar Usuario">Editar</button>';

                                                                                        if ($arrayobtenido[$i]['rango'] == 1 && $arrayobtenido[$i]['usuario'] == $_SESSION['CONFIGUSER']['usuario']) {
                                                                                            echo '<button type="button" class="cambiartema btn btn-success text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Cambiar Tema">Cambiar Tema</button>';
                                                                                        }

                                                                                        if ($arrayobtenido[$i]['rango'] != 1) {
                                                                                            echo '<button type="button" class="deluser btn btn-danger text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Eliminar Usuario">Eliminar</button>';
                                                                                        }
                                                                                        echo '</td>';
                                                                                    }
                                                                                }

                                                                                //LEER USUARIOS COMO ADMIN
                                                                                if ($_SESSION['CONFIGUSER']['rango'] == 2) {
                                                                                    for ($i = 1; $i < count($arrayobtenido); $i++) {
                                                                                        if ($arrayobtenido[$i]['rango'] == 2 && $arrayobtenido[$i]['usuario'] == $_SESSION['CONFIGUSER']['usuario']) {
                                                                                            echo '<tr class = "menu-hover">';
                                                                                            echo '<th scope="row">' . $arrayobtenido[$i]['usuario'] . '</th>';
                                                                                            echo '<td>';

                                                                                            if ($arrayobtenido[$i]['rango'] == 1) {
                                                                                                echo "Superusuario";
                                                                                            } elseif ($arrayobtenido[$i]['rango'] == 2) {
                                                                                                echo "Administrador";
                                                                                            } elseif ($arrayobtenido[$i]['rango'] == 3) {
                                                                                                echo "Usuario";
                                                                                            }

                                                                                            echo '</td>';

                                                                                            echo '<td>' . $arrayobtenido[$i]['estado'] . '</td>';

                                                                                            echo '<td>';
                                                                                            echo '<button type="button" class="edituser btn btn-warning text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Editar Usuario">Editar</button>';
                                                                                            echo '<button type="button" class="cambiartema btn btn-success text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Cambiar Tema">Cambiar Tema</button>';
                                                                                            echo '</td>';
                                                                                        }
                                                                                    }

                                                                                    if ($_SESSION['CONFIGUSER']['rango'] == 2 && array_key_exists('psystemcreateuser', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['psystemcreateuser'] == 1) {
                                                                                        for ($i = 1; $i < count($arrayobtenido); $i++) {
                                                                                            if ($arrayobtenido[$i]['rango'] == 3) {
                                                                                                echo '<tr class = "menu-hover">';
                                                                                                echo '<th scope="row">' . $arrayobtenido[$i]['usuario'] . '</th>';
                                                                                                echo '<td>';

                                                                                                if ($arrayobtenido[$i]['rango'] == 1) {
                                                                                                    echo "Superusuario";
                                                                                                } elseif ($arrayobtenido[$i]['rango'] == 2) {
                                                                                                    echo "Administrador";
                                                                                                } elseif ($arrayobtenido[$i]['rango'] == 3) {
                                                                                                    echo "Usuario";
                                                                                                }

                                                                                                echo '</td>';

                                                                                                echo '<td>' . $arrayobtenido[$i]['estado'] . '</td>';

                                                                                                echo '<td>';
                                                                                                echo '<button type="button" class="actdesuser btn btn-info text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Activar/Desactivar Usuario">Activar/Desactivar</button>';
                                                                                                echo '<button type="button" class="edituser btn btn-warning text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Editar Usuario">Editar</button>';
                                                                                                echo '<button type="button" class="deluser btn btn-danger text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Eliminar Usuario">Eliminar</button>';
                                                                                                echo '</td>';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }

                                                                                if ($_SESSION['CONFIGUSER']['rango'] == 3) {

                                                                                    for ($i = 1; $i < count($arrayobtenido); $i++) {
                                                                                        if ($arrayobtenido[$i]['rango'] == 3 && $arrayobtenido[$i]['usuario'] == $_SESSION['CONFIGUSER']['usuario']) {
                                                                                            echo '<tr class = "menu-hover">';
                                                                                            echo '<th scope="row">' . $arrayobtenido[$i]['usuario'] . '</th>';
                                                                                            echo '<td>';

                                                                                            if ($arrayobtenido[$i]['rango'] == 1) {
                                                                                                echo "Superusuario";
                                                                                            } elseif ($arrayobtenido[$i]['rango'] == 2) {
                                                                                                echo "Administrador";
                                                                                            } elseif ($arrayobtenido[$i]['rango'] == 3) {
                                                                                                echo "Usuario";
                                                                                            }

                                                                                            echo '</td>';

                                                                                            echo '<td>' . $arrayobtenido[$i]['estado'] . '</td>';

                                                                                            echo '<td>';
                                                                                            echo '<button type="button" class="cambiartema btn btn-success text-white mr-1" value="' . $arrayobtenido[$i]['usuario'] . '" title="Cambiar Tema">Cambiar Tema</button>';
                                                                                            echo '</td>';
                                                                                        }
                                                                                    }
                                                                                }

                                                                                ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
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

            <script src="js/gestorusuarios.js"></script>

        </div>
    <?php
        //FINAL VALIDAR SESSION
    } else {
        header("Location:index.php");
    }
    ?>

</body>

</html>