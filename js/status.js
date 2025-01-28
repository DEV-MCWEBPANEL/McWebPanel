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

    $("#binicio").click(function () {
        $.ajax({
            url: 'function/startserver.php',
            data: {
                action: 'eltexto'
            },
            type: 'POST',
            success: function (data) {
                if (data == "ok") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-success' role='alert'>Servidor iniciado correctamente.</div>";
                } else if (data == "yaenejecucion") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El servidor ya está en ejecución.</div>";
                } else if (data == "noexistecarpetaminecraft") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No existe la carpeta del servidor minecraft.</div>";
                } else if (data == "noeula") {
                    location.href = "eula.php";
                } else if (data == "noconfjar") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No has seleccionado un servidor .jar.</div>";
                } else if (data == "noexistejar") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El servidor .jar seleccionado no existe.</div>";
                } else if (data == "notipovalido") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo no es válido.</div>";
                } else if (data == "noescritura") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Carpeta Servidor Minecraft no tiene permisos de escritura.</div>";
                } else if (data == "noescrituraservproperties") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo server.properties no tiene permisos de escritura.</div>";
                } else if (data == "puertoenuso") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Puerto en uso.</div>";
                } else if (data == "nolecturamine") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta del servidor minecraft no tiene permisos de lectura.</div>";
                } else if (data == "noejecutable") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta del servidor minecraft no tiene permisos de ejecución.</div>";
                } else if (data == "eulanowrite") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo eula.txt del servidor minecraft no tiene permisos de escritura.</div>";
                } else if (data == "nojavaenruta") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo java no se encuentra en la ruta.</div>";
                } else if (data == "nojavaselect") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No hay ningún java seleccionado en System Config.</div>";
                } else if (data == "nojavadefault") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Java no encontrado en el servidor.</div>";
                } else if (data == "rammenoragiga") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La memoria del sistema es menor a 1 GB.</div>";
                } else if (data == "ramselectout") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La memoria seleccionada supera la memoria total del sistema.</div>";
                } else if (data == "ramavaliableout") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No hay memoria suficiente para ejecutar el servidor minecraft.</div>";
                } else if (data == "ramnooutput") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se ha podido obtener la memoria del sistema.</div>";
                } else if (data == "noscreenconf") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo /config/screen.conf no existe.</div>";
                } else if (data == "nolibforge") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Faltan las librerias necesarias para iniciar el servidor de Forge.</div>";
                } else if (data == "noforgenew") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se encontro la carpeta /libraries/net/minecraftforge/forge/ del nuevo forge.</div>";
                } else if (data == "forgenewtoomany") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Se ha encontrado más de una versión en /libraries/net/minecraftforge/forge/ revisa la carpeta y deja solamente la versión a utilizar.</div>";
                } else if (data == "noforgenewargfile") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se ha encontrado el archivo unix_args.txt con las librerías para poder iniciar forge.</div>";
                } else if (data == "noforgenewfound") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se ha podido obtener correctamente la versión de forge.</div>";
                }
            }
        });
    });

    $("#breiniciar").click(function () {
        $.ajax({
            url: 'function/restartserver.php',
            data: {
                action: 'eltexto'
            },
            type: 'POST',
            success: function (data) {

                if (data == "ok") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-success' role='alert'>Reiniciando Servidor.</div>";
                }
            }
        });
    });

    $("#bparar").click(function () {
        $.ajax({
            url: 'function/stopserver.php',
            data: {
                action: 'eltexto'
            },
            type: 'POST',
            success: function (data) {

                if (data == "ok") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-success' role='alert'>Señal apagar servidor enviado correctamente.</div>";
                }
            }
        });
    });

    $("#bkill").click(function () {
        $.ajax({
            url: 'function/killserver.php',
            data: {
                action: 'eltexto'
            },
            type: 'POST',
            success: function (data) {

                if (data == "ok") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-success' role='alert'>El servidor fue matado correctamente.</div>";
                } else if (data == "killnooutput") {
                    document.getElementById("textoretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se pudo obtener el PID del proceso.</div>";
                }
            }
        });
    });

    if (document.getElementById('binicio') !== null) {
        document.getElementById("binicio").disabled = true;
    }

    if (document.getElementById('breiniciar') !== null) {
        document.getElementById("breiniciar").disabled = true;
    }

    if (document.getElementById('bparar') !== null) {
        document.getElementById("bparar").disabled = true;
    }

    if (document.getElementById('bkill') !== null) {
        document.getElementById("bkill").disabled = true;
    }

    function myTimer() {
        $.ajax({
            url: 'function/funciones.php',
            data: {
                action: 'status'
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {

                document.getElementById("horaserver").innerHTML = "Hora Servidor: " + String(data.hora);

                if (data.encendido == "Apagado") {

                    document.getElementById("textoservidor").innerHTML = "Estado: <span class='cartel offline'>Offline</span>";

                    if (document.getElementById('binicio') !== null) {
                        document.getElementById("binicio").disabled = false;
                    }

                    if (document.getElementById('breiniciar') !== null) {
                        document.getElementById("breiniciar").disabled = true;
                    }

                    if (document.getElementById('bparar') !== null) {
                        document.getElementById("bparar").disabled = true;
                    }

                    if (document.getElementById('bkill') !== null) {
                        document.getElementById("bkill").disabled = true;
                    }

                    document.getElementById("textocpu").innerHTML = "Cpu:";
                    document.getElementById("textoram").innerHTML = "Ram:";
                    document.getElementById("eluptime").innerHTML = "Uptime:";

                } else if (data.encendido == "Encendido") {

                    document.getElementById("textoservidor").innerHTML = "Estado: <span class='cartel online'>Online</span>";

                    document.getElementById("textocpu").innerHTML = "Cpu: " + String(data.cpu) + "%";

                    if (data.memoria !== "") {
                        document.getElementById("textoram").innerHTML = "Ram: " + String(data.memoria) + " / Total: " + String(data.ramconfig / 1024) + " GB";
                    }

                    document.getElementById("eluptime").innerHTML = "Uptime: " + String(data.uptime);

                    if (document.getElementById('binicio') !== null) {
                        document.getElementById("binicio").disabled = true;
                    }

                    if (document.getElementById('breiniciar') !== null) {
                        document.getElementById("breiniciar").disabled = false;
                    }

                    if (document.getElementById('bparar') !== null) {
                        document.getElementById("bparar").disabled = false;
                    }

                    if (document.getElementById('bkill') !== null) {
                        document.getElementById("bkill").disabled = false;
                    }
                }
            }
        });
    }

    setInterval(myTimer, 1000);

    function getuserserver() {

        $.ajax({
            url: 'function/statusgetusers.php',
            data: {
                action: 'status'
            },
            type: 'POST',
            success: function (data) {
                document.getElementById("jugadores").innerHTML = "Jugadores Online: " + String(data);
            }
        });
    }

    setInterval(getuserserver, 1000);

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