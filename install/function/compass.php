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

function test_input($data)
{
    if (isset($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

//VALIDAMOS SESSION
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action']) && !empty($_POST['action'])) {

        $elerror = 0;
        $eltexto = "<div class='alert alert-danger' role='alert'>";
        $pwd = test_input($_POST['action']);

        if (strlen($pwd) < 16) {
            $eltexto .= "¡La contraseña tiene que tener un mínimo de 16 caracteres!";
            $eltexto .= "<br>";
            $elerror = 1;
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $eltexto .= "¡La contraseña tiene que contener al menos un número!";
            $eltexto .= "<br>";
            $elerror = 1;
        }

        if (!preg_match("#[a-z]+#", $pwd)) {
            $eltexto .= "¡La contraseña tiene que contener al menos una letra!";
            $eltexto .= "<br>";
            $elerror = 1;
        }

        if (!preg_match("#[A-Z]+#", $pwd)) {
            $eltexto .= "¡La contraseña tiene que contener al menos una letra mayúscula!";
            $eltexto .= "<br>";
            $elerror = 1;
        }

        if (!preg_match("#\W+#", $pwd)) {
            if (!preg_match('#_+#', $pwd)) {
                $eltexto .= "¡La contraseña tiene que contener al menos un símbolo!";
                $eltexto .= "<br>";
                $elerror = 1;
            }
        }

        $eltexto .= "</div>";
        $elarray = array("texto" => $eltexto, "error" => $elerror);
        echo json_encode($elarray);
    }
}
