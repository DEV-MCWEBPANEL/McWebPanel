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

header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; script-src 'self'; form-action 'self'; base-uri 'none'; connect-src 'self'; frame-ancestors 'none'");
header("Cross-Origin-Resource-Policy: same-origin");
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header('Permissions-Policy: geolocation=(), microphone=()');
header('Cache-Control: private, no-cache, must-revalidate');
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
  <meta name="description" content="Instalación McWebPanel">
  <meta name="author" content="DEV-MCWEBPANEL">
  <title>Instalación McWebPanel</title>

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="css/install1.css">

  <!-- Script AJAX -->
  <script src="../js/jquery.min.js"></script>

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="../img/icons/apple-icon-180x180.png" sizes="180x180">
  <link rel="icon" href="../img/icons/favicon-32x32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="../img/icons/favicon-16x16.png" sizes="16x16" type="image/png">
  <link rel="icon" href="../img/icons/favicon.ico">
</head>
<?php

//CARGAR VARIABLES
$losrequisitos = 0;
$comreq = "";
$permisos = "";
$estamodulo = "";

?>

<body>
  <div class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="py-1">
            <div class="container">
              <div class="row">
                <div class="col-md-3"><img class="d-block float-right" src="logo.png" alt="Logo"></div>
                <div class="col-md-9">
                  <h1 class="display-4 text-left">Instalación McWebPanel</h1>
                </div>
              </div>
              <hr>
              <p>Bienvenidos a McWebPanel, un panel de código abierto para la administración de Servidores Minecraft construido con PHP, jQuery y Bootstrap. Diseñado para una fácil instalación y una UI intuitiva tanto para administradores como usuarios. Completa el proceso de instalación para usar McWebPanel (Solo tomará unos minutos).</p>
              <hr>
            </div>
          </div>
          <div class="container">
            <h4 class="mb-4 text-left"><u>Requisitos del sistema</u></h4>
            <div class="table-responsive">
              <table class="table table-borderless table-striped">
                <thead>
                  <tr>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-center">PHP comando Shell_exec/exec</td>
                    <td></td>

                    <?php

                    //REQUISITO SHELL_EXEC
                    if (function_exists('shell_exec')) {
                      echo '<td class="text-success">Activado - SI</td>';
                    } else {
                      echo '<td class="text-danger">Activado - NO</td></tr></tbody></table><div class="alert alert-danger" role="alert">La instalación no puede continuar.</div>';
                      exit;
                    }

                    ?>

                  </tr>
                  <tr>
                    <td class="text-center">Máquina Virtual Java</td>
                    <td></td>

                    <?php

                    //REQUISITO JAVA
                    $comreq = shell_exec('command -v java >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>
                  <tr>
                    <td class="text-center">GNU Screen</td>
                    <td></td>

                    <?php

                    //REQUISITO SCREEN
                    $comreq = shell_exec('command -v screen >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>
                  <tr>
                    <td class="text-center">GNU GAWK</td>
                    <td></td>

                    <?php

                    //REQUISITO GNU GAWK
                    $comreq = shell_exec('command -v gawk >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">GNU WGET</td>
                    <td></td>

                    <?php

                    //REQUISITO GNU WGET
                    $comreq = shell_exec('command -v wget >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">GNU TAR</td>
                    <td></td>

                    <?php

                    //REQUISITO GNU TAR
                    $comreq = shell_exec('command -v tar >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">GNU GZIP</td>
                    <td></td>

                    <?php

                    //REQUISITO GNU GZIP
                    $comreq = shell_exec('command -v gzip >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">PHP JSON</td>
                    <td></td>

                    <?php

                    //REQUISITO JSON
                    if (!extension_loaded('json')) {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } else {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>
                  </tr>

                  <tr>
                    <td class="text-center">PHP CLI</td>
                    <td></td>

                    <?php

                    //PHP CLI
                    $comreq = shell_exec('command -v php -v >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">ZIP</td>
                    <td></td>

                    <?php

                    //REQUISITO GNU ZIP
                    $comreq = shell_exec('command -v zip >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">UNZIP</td>
                    <td></td>

                    <?php

                    //REQUISITO GNU UNZIP
                    $comreq = shell_exec('command -v unzip >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">GIT</td>
                    <td></td>

                    <?php

                    //REQUISITO GIT
                    $comreq = shell_exec('command -v git >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      $losrequisitos = 1;
                      echo '<td class="text-danger">Instalado - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">PIGZ</td>
                    <td></td>

                    <?php

                    //REQUISITO PIGZ
                    $comreq = shell_exec('command -v pigz >/dev/null && echo "yes" || echo "no"');
                    $comreq = trim($comreq);
                    if ($comreq == "no") {
                      echo '<td class="text-warning">Opcional - NO</td>';
                    } elseif ($comreq == "yes") {
                      echo '<td class="text-success">Instalado - SI</td>';
                    }

                    ?>

                  </tr>

                  <tr>
                    <td class="text-center">Carpeta install permisos escritura</td>
                    <td></td>

                    <?php

                    //PERMISOS CARPETA INSTALL
                    $permisos = getcwd() . PHP_EOL;
                    $permisos = trim($permisos);
                    if (is_writable($permisos)) {
                      echo '<td class="text-success">Escritura - SI</td>';
                    } else {
                      echo '<td class="text-danger">Escritura - NO</td>';
                      $losrequisitos = 1;
                    }
                    ?>

                  </tr>
                </tbody>
              </table>
            </div>



            <form action="<?php if ($losrequisitos == 0) {
                            echo 'install2.php';
                          } ?>" method="POST" id="login-install">
              <hr>
              <h4 class="mb-2 text-left"><u>Términos y condiciones de uso</u></h4>
              <div class="cartel-blackgris">
                <p>McWebPanel is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.</p>
                <p>McWebPanel is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.</p>
                <p>You should have received a copy of the GNU General Public License along with McWebPanel. If not, see https: //www.gnu.org/licenses.</p>
              </div>
              <br><br>
              <input type="checkbox" id="confirmlicencia" name="confirmlicencia" value="1" required> <label class="lead" for="confirmlicencia">Confirmo que he léido y aceptado los términos y condiciones de uso.</label>
              <hr>
              <?php

              if ($losrequisitos == 1) {
                echo '<div class="alert alert-danger text-center" role="alert">No cumples los requisitos para continuar la instalación.</div>';
              } elseif ($losrequisitos == 0) {
                echo '<button type="submit" class="btn btn-primary btn-block">Continuar Instalación</button>';
              }

              ?>
              <br>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
