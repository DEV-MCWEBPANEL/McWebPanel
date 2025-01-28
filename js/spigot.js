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

    if (document.getElementById('compilar') !== null) {
        document.getElementById("compilar").disabled = true;
    }

    if (document.getElementById('killcompilar') !== null) {
        document.getElementById("killcompilar").disabled = true;
    }

    if (document.getElementById('gifloading') !== null) {
        document.getElementById("gifloading").style.visibility = "hidden";
    }

    if (document.getElementById('compilar') !== null) {
        $("#compilar").click(function () {
            let verspigot = document.getElementById('serselectver').value
            $.ajax({
                url: 'function/compilarspigot.php',
                data: {
                    action: 'compilar',
                    laversion: verspigot
                },
                type: 'POST',
                success: function (data) {
                    if (data == "noreadraiz") {
                        alert("Error: No tienes permisos de lectura en la carpeta raíz, revisa los permisos de linux");
                    } else if (data == "nowriteraiz") {
                        alert("Error: No tienes permisos de escritura en la carpeta raíz, revisa los permisos de linux");
                    } else if (data == "yaenmarcha") {
                        alert("Ya existe un proceso compilar en ejecución");
                    } else if (data == "nowritecompilar") {
                        alert("No se puede borrar la carpeta compilar, no hay permisos de escritura");
                    } else if (data == "timeout") {
                        alert("Timeout al obtener versiones de Spigot");
                    } else if (data == "noverfound") {
                        alert("No se encontró la versión seleccionada en el listado de versiones Spigot");
                    } else if (data == "nobuildtools") {
                        alert("Error: No se ha podido descargar la herramienta Buildtools");
                    } else if (data == "nojavaenruta") {
                        alert("Error: Error: El archivo java no se encuentra en la ruta");
                    } else if (data == "rammenoragiga") {
                        alert("Error: Error: La memoria del sistema es menor a 1 GB");
                    } else if (data == "ramavaiableout") {
                        alert("Error: Error: No hay memoria suficiente para ejecutar la compilacion de Spigot");
                    } else if (data == "OUTGIGAS") {
                        alert("Error: Has superado los GB asignados a la carpeta minecraft");
                    } else if (data == "ERRORGETSIZE") {
                        alert("Error: No se puede obtener los GB de la carpeta minecraft");
                    } else if (data == "ramnooutput") {
                        alert("Error: No se ha podido obtener la memoria del sistema");
                    }
                }
            });
        });
    }

    if (document.getElementById('killcompilar') !== null) {

        $("#killcompilar").click(function () {
            $.ajax({
                url: 'function/compilarspigot.php',
                data: {
                    action: 'matarcompilar'
                },
                type: 'POST',
                success: function (data) {

                }
            });
        });

    }

    function myTimer() {

        $.ajax({
            url: 'function/compilarspigot.php',
            data: {
                action: 'consola'
            },
            type: 'POST',
            success: function (data) {
                let textoantiguo = document.getElementById("laconsola").value;

                document.getElementById("laconsola").innerHTML = data;

                if (data !== textoantiguo) {
                    document.getElementById("laconsola").scrollTop = document.getElementById("laconsola").scrollHeight;
                }

            }
        });

        $.ajax({
            url: 'function/compilarspigot.php',
            data: {
                action: 'estado'
            },
            type: 'POST',
            success: function (data) {
                if (data == "ON") {

                    if (document.getElementById('compilar') !== null) {
                        document.getElementById("compilar").disabled = true;
                    }

                    if (document.getElementById('killcompilar') !== null) {
                        document.getElementById("killcompilar").disabled = false;
                    }

                    if (document.getElementById('gifloading') !== null) {
                        document.getElementById("gifloading").style.visibility = "visible";
                    }
                } else if (data == "OFF") {

                    if (document.getElementById('compilar') !== null) {
                        document.getElementById("compilar").disabled = false;
                    }

                    if (document.getElementById('killcompilar') !== null) {
                        document.getElementById("killcompilar").disabled = true;
                    }

                    if (document.getElementById('gifloading') !== null) {
                        document.getElementById("gifloading").style.visibility = "hidden";
                    }
                }
            }
        });


    }

    setInterval(myTimer, 1000);

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