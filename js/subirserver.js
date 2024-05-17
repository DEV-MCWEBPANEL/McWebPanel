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

    document.getElementById("botonsubir").disabled = true;
    document.getElementById("gifloading").style.visibility = "hidden";

    $(".custom-file-input").on("change", function () {
        let eltamano;
        let elerror = 0;
        let fileName = $(this).val().split("\\").pop();
        let res = fileName.substring(fileName.length - 4, fileName.length);
        if (res.toLowerCase() == ".jar") {
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            let elarchivo = document.getElementById('fileName');
            eltamano = elarchivo.files.item(0).size;
        } else {
            alert("Solo se permiten subir archivos .jar");
            document.getElementById("botonsubir").disabled = true;
            document.getElementById("fileName").value = "";
            elerror = 1;
        }

        if (elerror == 0) {

            $.ajax({
                url: 'function/gestorlimituploadfile.php',
                data: {
                    action: eltamano
                },
                type: 'POST',
                success: function (data) {

                    if (data == "OUTGIGAS") {
                        if (document.getElementById('botonsubir') !== null) {
                            document.getElementById("botonsubir").disabled = true;
                        }
                        document.getElementById('fileName').value = "";
                        $('#lvltext').text("Elija el archivo");
                        alert("Error: No puedes subir el archivo, has superado los GB asignados a la carpeta minecraft");

                    } else if (data == "OKGIGAS") {
                        if (document.getElementById('botonsubir') !== null) {
                            document.getElementById("botonsubir").disabled = false;
                        }
                    } else if (data == "ERRORGETSIZE") {
                        if (document.getElementById('botonsubir') !== null) {
                            document.getElementById("botonsubir").disabled = true;

                        }
                        document.getElementById('fileName').value = "";
                        $('#lvltext').text("Elija el archivo");
                        alert("Error: No se puede obtener los GB de la carpeta minecraft");
                    } else if (data == "OUTUPLOAD") {
                        if (document.getElementById('botonsubir') !== null) {
                            document.getElementById("botonsubir").disabled = true;

                        }
                        document.getElementById('fileName').value = "";
                        $('#lvltext').text("Elija el archivo");
                        alert("Error: El archivo supera el límite de subida");
                    }
                }
            });

        }

    });

    $("#form").on('submit', (function (e) {
        document.getElementById("gifloading").style.visibility = "visible";
        e.preventDefault();
        $.ajax({
            url: "function/procesarjar.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                document.getElementById("gifloading").style.visibility = "hidden";

                if (data == "nojar") {
                    alert("No es un archivo .jar");
                } else if (data == "notipovalido") {
                    alert("El archivo no es del tipo válido");
                } else if (data == "nowriteraiz") {
                    alert("El archivo raíz no tiene permisos de escritura");
                } else if (data == "nocarpserver") {
                    alert("La carpeta servidor minecraft no existe");
                } else if (data == "nowriteservmine") {
                    alert("La carpeta servidor minecraft no tiene permisos de escritura");
                } else if (data == "jarexiste") {
                    alert("Ya existe un archivo .jar con ese nombre");
                } else if (data == "errorsubir") {
                    alert("Error al subir el archivo");
                } else if (data == "errprocess") {
                    alert("Error al procesar el archivo");
                } else if (data == "novalidoname") {
                    alert("Nombre no válido");
                } else if (data == "ERRORGETSIZE") {
                    alert("No se puede obtener los GB de la carpeta minecraft");
                } else if (data == "OUTGIGAS") {
                    alert("Has superado los GB asignados a la carpeta minecraft");
                } else if (data == "OK") {
                    alert("Subido con éxito");
                    location.reload();
                }

            },
            error: function () {
                alert("error");
            }
        });
    }));

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