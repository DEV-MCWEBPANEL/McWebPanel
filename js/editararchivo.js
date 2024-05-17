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

$(function () {

    $("#guardarfile").click(function () {
        $.ajax({
            url: 'function/editarsavefile.php',
            data: {
                action: this.value,
                eltexto: document.getElementById("eleditor").value
            },
            type: 'POST',
            success: function (data) {

                if (data == "OK") {
                    location.href = "gestorarchivos.php";
                } else if (data == "noexiste") {
                    alert("El archivo no existe");
                } else if (data == "nowrite") {
                    alert("El archivo no tiene permisos de escritura");
                }
            }
        });
    });

    $("#cancelar").click(function () {
        location.href = "gestorarchivos.php";
    });

    function sessionTimer() {

        $.ajax({
            url: 'function/salirsession.php',
            data: {
                action: 'status'
            },
            type: 'POST',
            success: function (data) {
                if (data == "SALIR") {
                    location.href = "index.php";
                }


            }
        });
    }

    setInterval(sessionTimer, 1000);

});