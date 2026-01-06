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

$(function () {

    sessionStorage.antiguo = "";
    sessionStorage.actdesroll = 0;

    function redimensionar() {
        let tamano = window.innerHeight;
        tamano = tamano - 150;
        document.getElementById("laconsola").style.height = tamano + "px";
    }

    redimensionar();

    window.addEventListener("resize", redimensionar);

    if (document.getElementById("elcomando").value !== null) {
        document.getElementById('elcomando').value = "";
    }

    $.ajax({
        url: 'function/enviarconsola.php',
        data: {
            action: 'status'
        },
        type: 'POST',
        success: function (data) {

            if (data !== undefined) {
                document.getElementById("laconsola").innerHTML = data;

                if (data.length != sessionStorage.antiguo) {

                    if (data !== "") {
                        document.getElementById("laconsola").innerHTML = data;
                    }

                    if (sessionStorage.actdesroll == 0) {
                        document.getElementById("laconsola").scrollTop = document.getElementById("laconsola").scrollHeight;
                    }
                }
                sessionStorage.antiguo = data.length;
            }
        }
    });

    function myTimer() {

        $.ajax({
            url: 'function/enviarconsola.php',
            data: {
                action: 'status'
            },
            type: 'POST',
            success: function (data) {

                if (data !== undefined) {

                    if (data.length != sessionStorage.antiguo) {

                        if (data !== "") {
                            document.getElementById("laconsola").innerHTML = data;
                        }

                        if (sessionStorage.actdesroll == 0) {
                            document.getElementById("laconsola").scrollTop = document.getElementById("laconsola").scrollHeight;
                        }
                    }
                    sessionStorage.antiguo = data.length;
                }
            }
        });
    }

    setInterval(myTimer, 2000);

    function enviarcomando() {
        let eltexto = "";

        if (document.getElementById("elcomando").value !== null) {

            eltexto = document.getElementById("elcomando").value;

            $.ajax({
                url: 'function/enviarcomando.php',
                data: {
                    action: eltexto
                },
                type: 'POST',
                success: function (data) {

                    if (data == "ok") {
                        document.getElementById("elcomando").value = "";
                    } else if (data == "off") {
                        alert("El servidor esta apagado");
                    } else if (data == "lenmax") {
                        alert("El comando supera los 4096 caracteres");
                    } else if (data == "badchars") {
                        alert("El comando tiene caracteres no válidos");
                    }
                }
            });
        }
    }

    if (document.getElementById('descroll') !== null) {
        $("#descroll").click(function () {
            if (sessionStorage.actdesroll == 0) {
                sessionStorage.actdesroll = 1;
                document.getElementById('descroll').innerText = "Activar Scroll";
            } else {
                sessionStorage.actdesroll = 0;
                document.getElementById('descroll').innerText = "Desactivar Scroll";
            }
        });
    }

    if (document.getElementById('botonconsola') !== null) {
        $("#botonconsola").click(function () {
            enviarcomando();
        });
    }

    function teclaupbuffer() {

        $.ajax({
            url: 'function/buffer.php',
            data: {
                action: 'bufferarriba'
            },
            type: 'POST',
            success: function (data) {

                if (document.getElementById('elcomando') !== null) {
                    document.getElementById('elcomando').value = data;
                }
            }
        });
    }

    function tecladownbuffer() {

        $.ajax({
            url: 'function/buffer.php',
            data: {
                action: 'bufferabajo'
            },
            type: 'POST',
            success: function (data) {

                if (document.getElementById('elcomando') !== null) {
                    document.getElementById('elcomando').value = data;
                }
            }
        });
    }

    if (document.getElementById('elcomando') !== null) {

        document.getElementById('elcomando').addEventListener('keydown', function (event) {
            const key = event.key;
            switch (key) {
                case "Enter":
                    enviarcomando();
                    break;
                case "ArrowUp":
                    teclaupbuffer();
                    break;
                case "ArrowDown":
                    tecladownbuffer();
                    break;
            }
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