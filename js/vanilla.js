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

    if (document.getElementById('gifloading') !== null) {
        document.getElementById("gifloading").style.visibility = "hidden";
    }

    if (document.getElementById('descargar') !== null) {
        $("#descargar").click(function () {
            document.getElementById("textoretorno").innerHTML = "";
            document.getElementById("gifloading").style.visibility = "visible";
            let vervanilla = document.getElementById('serselectver').value
            $.ajax({
                url: 'function/descargarvanilla.php',
                data: {
                    action: 'descargar',
                    laversion: vervanilla
                },
                type: 'POST',
                success: function (data) {
                    document.getElementById("gifloading").style.visibility = "hidden";

                    if(data == "ok"){
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-success' role='alert'>Servidor Vanilla descargado correctamente.</div>";
                    } else if (data == "nodirwrite") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta temp no tiene permisos de escritura.</div>";
                    } else if (data == "nominewrite") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta minecraft no tiene permisos de escritura.</div>";
                    } else if (data == "timeoutmanifest") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Timeout al obtener manifest de Minecraft.</div>";
                    } else if (data == "noverfound") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se encontró la versión en el manifest.</div>";
                    } else if (data == "noserverfound") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No existe servidor para descargar.</div>";
                    } else if (data == "filenodownload") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se ha descargado el servidor vanilla.</div>";
                    } else if (data == "nogoodsha1") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Verificación SHA1 errónea, descarga incorrecta.<br>Vuelve a intentarlo.</div>";
                    } else if (data == "renamerror") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Al mover servidor a la carpeta Minecraft.</div>";
                    }
                }
            });
        });
    }

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