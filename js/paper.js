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

$(function () {

    document.getElementById("gifloading").style.visibility = "hidden";
    document.getElementById("descargar").disabled = true;

    $("#selectproyecto").on('change', function () {
        let tipoproyecto = this.value;

        document.getElementById("gifloading").style.visibility = "visible";
        document.getElementById("textoretorno").innerHTML = "";
        document.getElementById("descargar").disabled = true;

        document.getElementById("serselectver").innerHTML = "";
        document.getElementById("buildversion").innerHTML = "";

        $.ajax({
            url: 'function/descargarpaper.php',
            data: {
                action: 'getversion',
                elproyecto: tipoproyecto
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {

                document.getElementById("serselectver").innerHTML = "";
                document.getElementById("gifloading").style.visibility = "hidden";

                if (data.retorno == "okbuild") {

                    $('#serselectver').append('<option selected disabled hidden>No hay ninguna version seleccionada</option>');
                    for (const element of data.lasbuild) {
                        let textbuild = element;
                        $('#serselectver').append(new Option(textbuild, textbuild, false, false));
                    }

                } else if (data.retorno == "nopostaction") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor action.</div>";
                } else if (data.retorno == "nopostproyect") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor proyecto.</div>";
                } else if (data.retorno == "errrorgetversions") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Error al obtener versiones del proyecto.</div>";
                }
            }
        });
    });

    $("#serselectver").on('change', function () {
        let tipoproyecto = document.getElementById("selectproyecto").value;
        let tipoversion = this.value;

        document.getElementById("gifloading").style.visibility = "visible";
        document.getElementById("textoretorno").innerHTML = "";
        document.getElementById("descargar").disabled = true;

        $.ajax({
            url: 'function/descargarpaper.php',
            data: {
                action: 'getbuild',
                elproyecto: tipoproyecto,
                elversion: tipoversion
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {

                document.getElementById("buildversion").innerHTML = "";
                document.getElementById("gifloading").style.visibility = "hidden";

                if (data.retorno == "okbuild") {

                    $('#buildversion').append('<option selected disabled hidden>No hay ninguna build seleccionada</option>');
                    for (const element of data.lasbuild) {
                        let textbuild = element;
                        $('#buildversion').append(new Option(textbuild, textbuild, false, false));
                    }

                } else if (data.retorno == "nopostaction") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor action.</div>";
                } else if (data.retorno == "nopostproyect") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor proyecto.</div>";
                } else if (data.retorno == "nopostver") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor versión.</div>";
                } else if (data.retorno == "errrorgetbuilds") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Error al obtener builds del proyecto.</div>";
                }
            }
        });
    });

    $("#buildversion").on('change', function () {
        document.getElementById("descargar").disabled = false;
        document.getElementById("textoretorno").innerHTML = "";
    });

    if (document.getElementById('descargar') !== null) {
        $("#descargar").click(function () {
            let tipoproyecto = document.getElementById("selectproyecto").value;
            let tipoversion = document.getElementById("serselectver").value;
            let tipobuild = document.getElementById("buildversion").value;

            document.getElementById("gifloading").style.visibility = "visible";

            $.ajax({
                url: 'function/descargarpaper.php',
                data: {
                    action: 'descargar',
                    elproyecto: tipoproyecto,
                    elversion: tipoversion,
                    elbuild: tipobuild
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {

                    document.getElementById("gifloading").style.visibility = "hidden";

                    if (data.retorno == "ok") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-success' role='alert'>Servidor descargado correctamente.</div>";
                    } else if (data.retorno == "nopostaction") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor action.</div>";
                    } else if (data.retorno == "nopostproyect") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor proyecto.</div>";
                    } else if (data.retorno == "nopostver") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor versión.</div>";
                    } else if (data.retorno == "nopostbuild") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se envió valor build.</div>";
                    } else if (data.retorno == "nodirwrite") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta temp no tiene permisos de escritura.</div>";
                    } else if (data.retorno == "nominewrite") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta minecraft no tiene permisos de escritura.</div>";
                    } else if (data.retorno == "errorgetbuildinfo") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Error al obtener información de la build.</div>";
                    } else if (data.retorno == "filenodownload") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Error: No se ha descargado el servidor paper.</div>";
                    } else if (data.retorno == "nogoodsha256") {
                        document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Verificación SHA256 errónea, descarga incorrecta.<br>Vuelve a intentarlo.</div>";
                    } else if (data.retorno == "renamerror") {
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