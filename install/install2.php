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
  <meta name="description" content="Instalación McWebPanel">
  <meta name="author" content="DEV-MCWEBPANEL">
  <title>Instalación McWebPanel</title>

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
  // No se aceptan metodos que no sean post
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $licencia = 0;
    if (empty($_POST["confirmlicencia"])) {
      echo "No has aceptado la licencia";
      exit();
    } else {
      $licencia = $_POST["confirmlicencia"];
      if ($licencia != 1) {
        echo "No has aceptado la licencia";
        exit();
      }
    }
  ?>
    <div class="pt-5">
      <div class="container">
        <div class="row">
          <div class="col-md-3"><img class="d-block float-right" src="logo.png" alt="Logo"></div>
          <div class="col-md-9">
            <h1 class="display-4 text-left">Instalación McWebPanel</h1>
          </div>
        </div>
        <hr>
      </div>
    </div>
    <div class="py-2 text-center">
      <div class="container">
        <div class="row">
          <div class="mx-auto col-lg-6 col-10">

            <h4 class="mb-4"><u>Configurar Instalación Inicial</u></h4>
            <p class="text-center">Completa los campos para terminar la instalación, la configuración podrá ser modificada posteriormente desde el apartado System Config en el menú principal, recuerda no introducir datos personales ni contraseñas inseguras.</p>
            <hr>
            <form class="text-left" action="install3.php" method="POST" id="login-install2">

              <div class="form-group">
                <label for="eluser" class="">Nombre Usuario (SuperAdmin):</label>
                <input type="text" class="form-control" id="eluser" name="eluser" spellcheck="false" autocapitalize="off" required="required" maxlength="255">
              </div>

              <div class="form-row">

                <div class="form-group col-md-6">
                  <label for="elpass">Contraseña:</label>
                  <input type="password" class="form-control" id="elpass" name="elpass" spellcheck="false" autocapitalize="off" placeholder="••••" required="required">
                </div>

                <div class="form-group col-md-6">
                  <label for="elrepass">Confirmar Contraseña:</label>
                  <input type="password" class="form-control" id="elrepass" name="elrepass" spellcheck="false" autocapitalize="off" placeholder="••••" required="required">
                </div>

                <div class="form-group col-md-12">
                  <label>
                    <input type="checkbox" name="verpassword" id="verpassword"> Mostrar contraseñas
                  </label>
                </div>

              </div>

              <div class="form-group">
                <p class="lead" id="textoretorno"></p>
              </div>

              <div class="form-group">
                <label for="elnomserv">Nombre Servidor:</label>
                <input type="text" class="form-control" id="elnomserv" name="elnomserv" required="required" placeholder="McWebPanel">
              </div>

              <div class="form-row">

                <div class="form-group col-md-6">
                  <label for="elport">Puerto:</label>
                  <input type="number" value="25565" class="form-control" id="elport" name="elport" required="required" placeholder="25565" max="65535" min="1025">
                </div>

                <div class="form-group col-md-6">
                  <label for="maxupload">Subida Archivos (Limite MB):</label>
                  <select id="maxupload" name="maxupload" class="form-control" required="required">
                    <?php

                    $servidorweb = php_sapi_name();

                    if ($servidorweb == "apache" || $servidorweb == "apache2handler") {
                      $opcionesserver = array('128', '256', '386', '512', '640', '768', '896', '1024', '2048', '3072', '4096', '5120');
                      for ($i = 0; $i < count($opcionesserver); $i++) {
                        echo '<option value="' . $opcionesserver[$i] . '">' . $opcionesserver[$i] . " MB" . '</option>';
                      }
                    } else {
                      echo '<option value="128">Solo en Apache (mod_php)</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6"> <label for="elram" class="">Memoria Ram Límite:</label>
                  <select id="elram" name="elram" class="form-control" required="required">
                    <?php

                    $salida = shell_exec("free -g | grep Mem | gawk '{ print $2 }'");
                    if ($salida != "") {
                      $totalram = trim($salida);
                      $totalram = intval($totalram);

                      if ($totalram == 0) {
                        echo '<option value="0" selected>MEMORIA INSUFICIENTE / NO TIENES NI UN GB</option>';
                      } elseif ($totalram >= 1) {
                        for ($i = 1; $i <= $totalram; $i++) {
                          echo '<option value="' . 1024 * $i . '">' . $i . ' GB</option>';
                        }
                      }
                    } else {
                      echo '<option value="0" selected>NO SE PUDO OBTENER LA MEMORIA DEL SISTEMA</option>';
                    }

                    ?>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="eltipserv">Tipo Servidor:</label>
                  <select id="eltipserv" name="eltipserv" class="form-control" required="required">
                    <option value="vanilla">Vanilla</option>
                    <option value="spigot">Spigot</option>
                    <option value="paper">Paper</option>
                    <option value="forge old">Forge Old</option>
                    <option value="forge new">Forge New</option>
                    <option value="magma">Magma</option>
                    <option value="otros">Otros</option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="zonahoraria" class="">Zona Horaria:</label>
                  <select id="zonahoraria" name="zonahoraria" class="form-control" required="required">
                    <?php
                    $zonas_horarias = timezone_identifiers_list();

                    foreach ($zonas_horarias as $zona) {
                      $offset = timezone_offset_get(new DateTimeZone($zona), new DateTime());
                      $utc = $offset / 3600; // Convertir a horas

                      if ($utc < 0) {
                        echo "<option value='$zona'>$zona (UTC $utc:00)</option>";
                      } else {
                        echo "<option value='$zona'>$zona (UTC +$utc:00)</option>";
                      }
                    }
                    echo '</select>';
                    ?>
                  </select>
                </div>
              </div>

              <p class="lead" id="errorsubmit"></p>
              <button type="submit" id="binstalar" class="btn btn-primary btn-block">Finalizar Instalación</button>
            </form>
            <br>
          </div>
        </div>
      </div>
    </div>
    <script src="js/install2.js"></script>
  <?php
    //FIN DEL IF DEL POST
  } else {
    header("location:index.php");
  }
  ?>

</body>
</html>