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

    document.getElementById("binstalar").disabled = false;

    const select = document.getElementById("zonahoraria");

    if (select && select.options.length > 0) {
        const lazonahoraria = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if (lazonahoraria) select.value = lazonahoraria;
    }

    $("#verpassword").click(function () {
        let x1 = document.getElementById("elpass");
        if (x1.type === "password") {
            x1.type = "text";
        } else {
            x1.type = "password";
        }
        let x2 = document.getElementById("elrepass");
        if (x2.type === "password") {
            x2.type = "text";
        } else {
            x2.type = "password";
        }
    });

    $("#elpass").change(function () {
        let getpass = document.getElementById("elpass").value;
        if (getpass == "") {
            document.getElementById("textoretorno").innerHTML = "";
        } else {
            $.ajax({
                url: 'function/compass.php',
                data: {
                    action: getpass
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.error == 1) {
                        document.getElementById("textoretorno").innerHTML = data.texto;
                        document.getElementById("binstalar").disabled = true;
                    } else {
                        document.getElementById("textoretorno").innerHTML = "";
                        document.getElementById("binstalar").disabled = false;
                    }
                }
            });
        }
    });

    $("#elport").change(function () {
        let elnumero = document.getElementById("elport").value;

        if (elnumero < 1025 || elnumero > 65535) {
            document.getElementById("elport").value = "25565";
        }

    });

    $("#elport").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#login-install2").submit(function () {
        let elerror = 0;
        let eltexto = "<div class='alert alert-danger' role='alert'>";

        if (document.getElementById("eluser").value == "") {
            eltexto = eltexto + "No has introducido ningún nombre de usuario";
            eltexto = eltexto + "<br>";
            elerror = 1;
        }

        if (document.getElementById("elnomserv").value == "") {
            eltexto = eltexto + "No has introducido ningún nombre al servidor";
            eltexto = eltexto + "<br>";
            elerror = 1;
        }

        if (document.getElementById("elport").value == "") {
            eltexto = eltexto + "No has introducido ningún puerto";
            eltexto = eltexto + "<br>";
            elerror = 1;
        }

        if (document.getElementById("elpass").value != document.getElementById("elrepass").value) {
            eltexto = eltexto + "Las contraseñas no coinciden";
            eltexto = eltexto + "<br>";
            elerror = 1;
        }

        if (document.getElementById("elram").value == 0) {
            eltexto = eltexto + "No cumples el mínimo de memoria ram";
            eltexto = eltexto + "<br>";
            elerror = 1;
        }

        eltexto = eltexto + "</div>";

        if (elerror == 0) {
            return true;
        } else {
            document.getElementById("errorsubmit").innerHTML = eltexto;
            return false;
        }
    });

});