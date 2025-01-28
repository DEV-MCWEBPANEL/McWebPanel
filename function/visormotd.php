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

require_once "../template/session.php";
require_once "../template/errorreport.php";

function test_input($data)
{
    if (isset($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

//COMPROBAR SI SESSION EXISTE SINO CREARLA CON NO
if (!isset($_SESSION['VALIDADO']) || !isset($_SESSION['KEYSECRETA'])) {
    $_SESSION['VALIDADO'] = "NO";
    $_SESSION['KEYSECRETA'] = "0";
}

//VALIDAMOS SESSION
if ($_SESSION['VALIDADO'] == $_SESSION['KEYSECRETA']) {

    if ($_SESSION['CONFIGUSER']['rango'] == 1 || $_SESSION['CONFIGUSER']['rango'] == 2 || array_key_exists('pconfmine', $_SESSION['CONFIGUSER']) && $_SESSION['CONFIGUSER']['pconfmine'] == 1) {

        //VALIDAMOS SESSION
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST['action']) && !empty($_POST['action'])) {

                function leermotd($texto)
                {

                    $textototal = "";
                    $runicode = "";
                    $unicodeChar = "";

                    $texto = str_replace("<", htmlentities("<"), $texto);
                    $texto = str_replace("<", htmlentities(">"), $texto);

                    $texto = str_replace('\"', htmlentities('"'), $texto);

                    $texto = "<span class='colreset'>" . $texto;

                    //OFUSCADO
                    $texto = str_replace("\u00a7k", "", $texto);
                    $texto = str_replace("\u00A7k", "", $texto);

                    //SALTO
                    $texto = str_replace("\\n", "<br>", $texto);

                    //NEGRITA
                    $texto = str_replace("\u00a7l", "<strong>", $texto);
                    $texto = str_replace("\u00A7l", "<strong>", $texto);

                    //CURSIVA
                    $texto = str_replace("\u00a7o", "<i>", $texto);
                    $texto = str_replace("\u00A7o", "<i>", $texto);

                    //SUBRAYADO
                    $texto = str_replace("\u00a7n", "<u>", $texto);
                    $texto = str_replace("\u00A7n", "<u>", $texto);

                    //TACHADO
                    $texto = str_replace("\u00a7m", "<s>", $texto);
                    $texto = str_replace("\u00A7m", "<s>", $texto);

                    //RESET
                    $texto = str_replace("\u00a7r", "</strong></i></u></s><span class='colreset'>", $texto);
                    $texto = str_replace("\u00A7r", "</strong></i></u></s><span class='colreset'>", $texto);

                    //COLORES MAYUSCULA
                    $texto = str_replace("\u00A70", "<span class='col01'>", $texto);
                    $texto = str_replace("\u00A71", "<span class='col02'>", $texto);
                    $texto = str_replace("\u00A72", "<span class='col03'>", $texto);
                    $texto = str_replace("\u00A73", "<span class='col04'>", $texto);
                    $texto = str_replace("\u00A74", "<span class='col05'>", $texto);
                    $texto = str_replace("\u00A75", "<span class='col06'>", $texto);
                    $texto = str_replace("\u00A76", "<span class='col07'>", $texto);
                    $texto = str_replace("\u00A77", "<span class='col08'>", $texto);
                    $texto = str_replace("\u00A78", "<span class='col09'>", $texto);
                    $texto = str_replace("\u00A79", "<span class='col10'>", $texto);
                    $texto = str_replace("\u00A7a", "<span class='col11'>", $texto);
                    $texto = str_replace("\u00A7b", "<span class='col12'>", $texto);
                    $texto = str_replace("\u00A7c", "<span class='col13'>", $texto);
                    $texto = str_replace("\u00A7d", "<span class='col14'>", $texto);
                    $texto = str_replace("\u00A7e", "<span class='col15'>", $texto);
                    $texto = str_replace("\u00A7f", "<span class='col16'>", $texto);

                    //COLORES MINUSCULA
                    $texto = str_replace("\u00a70", "<span class='col01'>", $texto);
                    $texto = str_replace("\u00a71", "<span class='col02'>", $texto);
                    $texto = str_replace("\u00a72", "<span class='col03'>", $texto);
                    $texto = str_replace("\u00a73", "<span class='col04'>", $texto);
                    $texto = str_replace("\u00a74", "<span class='col05'>", $texto);
                    $texto = str_replace("\u00a75", "<span class='col06'>", $texto);
                    $texto = str_replace("\u00a76", "<span class='col07'>", $texto);
                    $texto = str_replace("\u00a77", "<span class='col08'>", $texto);
                    $texto = str_replace("\u00a78", "<span class='col09'>", $texto);
                    $texto = str_replace("\u00a79", "<span class='col10'>", $texto);
                    $texto = str_replace("\u00a7a", "<span class='col11'>", $texto);
                    $texto = str_replace("\u00a7b", "<span class='col12'>", $texto);
                    $texto = str_replace("\u00a7c", "<span class='col13'>", $texto);
                    $texto = str_replace("\u00a7d", "<span class='col14'>", $texto);
                    $texto = str_replace("\u00a7e", "<span class='col15'>", $texto);
                    $texto = str_replace("\u00a7f", "<span class='col16'>", $texto);

                    //CARACTERES
                    for ($i = 0; $i < strlen($texto); $i++) {
                        if ($texto[$i] == "\\") {
                            if ($i + 1 < strlen($texto)) {
                                if ($texto[$i + 1] == "\\") {
                                    $textototal .= $texto[$i];
                                    $i = $i + 1;
                                } else {
                                    if ($i + 5 < strlen($texto)) {
                                        if ($texto[$i + 2] != "\\" && $texto[$i + 3] != "\\" && $texto[$i + 4] != "\\" && $texto[$i + 5] != "\\") {
                                            $unicodeChar = $texto[$i] . $texto[$i + 1] . $texto[$i + 2] . $texto[$i + 3] . $texto[$i + 4] . $texto[$i + 5];
                                            $runicode = json_decode('"' . $unicodeChar . '"');
                                            $textototal .= $runicode;
                                            $i = $i + 5;
                                        }
                                    } else {
                                        $textototal .= $texto[$i];
                                    }
                                }
                            }
                        } else {
                            $textototal .= $texto[$i];
                        }
                    }

                    //RETORNO
                    return $textototal;
                }

                $elerror = 0;
                $retorno = "";

                $elmotd = $_POST['action'];

                $retorno = leermotd($elmotd);

                echo $retorno;
            }
        }
    }
}
