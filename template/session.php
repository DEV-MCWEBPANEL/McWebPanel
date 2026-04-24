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

//EN CASO DE TENER UN DOMINIO O SUBDOMINIO SE PUEDE AJUSTAR LA VARIABLE $dominio
//ES OPCIONAL

//$dominio = ".eldominio.com"
//$dominio = ".subdominio.eldominio.com"

$dominio = "";

//SE PUEDE CONFIGURAR LA LONGITUD DE LA SESSION
//POR DEFECTO: 3600 = 1 HORA
$vidasession = "3600";

$getconflakey = "";

$esHttps = (
  (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
  (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
  (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
);

session_set_cookie_params([
  'lifetime' => $vidasession,
  'path' => '/',
  'domain' => $dominio,
  'secure' => $esHttps,
  'httponly' => true,
  'samesite' => 'Strict'
]);

ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);

session_start();

if (isset($_SESSION['IDENTIFICARSESSION'])) {
  $rutanofunction = getcwd();
  $rutanofunction = trim($rutanofunction);
  $rutanofunction .= "/config/confopciones.php";

  $rutasifunction = dirname(getcwd()) . PHP_EOL;
  $rutasifunction = trim($rutasifunction);
  $rutasifunction .= "/config/confopciones.php";

  if (file_exists($rutanofunction)) {
    require_once "config/confopciones.php";
  } else {
    if (file_exists($rutasifunction)) {
      require_once "../config/confopciones.php";
    } else {
      echo '<div class="alert alert-danger" role="alert">No se encontró confopciones.php</div>';
      exit;
    }
  }

  $getconflakey = "";

  if (defined('CONFIGSESSIONKEY')) {
    $getconflakey = CONFIGSESSIONKEY;
  }

  if ($getconflakey != $_SESSION['IDENTIFICARSESSION']) {
    echo '<div class="alert alert-danger" role="alert">Tu sesión no pertenece a este panel, elimina la sesión y vuelve a intentar</div>';
    exit;
  }

  if (!defined('CONFIGZONAHORARIA')) {
    $reczonahoraria = "UTC";
  } else {
    $reczonahoraria = CONFIGZONAHORARIA;
  }

  date_default_timezone_set($reczonahoraria);
}

unset($dominio);
unset($getconflakey);
unset($vidasession);
unset($reczonahoraria);
