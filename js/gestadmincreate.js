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

    $("#elpass").keyup(function () {
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
                    } else {
                        document.getElementById("textoretorno").innerHTML = "";
                    }
                }
            });
        }
    });

    $("#verpassword").click(function () {
        let x = document.getElementById("elpass");
        let y = document.getElementById("elrepass");

        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

        if (y.type === "password") {
            y.type = "text";
        } else {
            y.type = "password";
        }
    });

    if (document.getElementById('selectodaspsysconf') !== null) {
        $("#selectodaspsysconf").click(function () {
            psystemconfpuerto.checked = true;
            psystemconfmemoria.checked = true;
            psystemconftipo.checked = true;
            psystemconfsubida.checked = true;
            psystemconfnombre.checked = true;
            psystemconfzonahoraria.checked = true;
            psystemconfavanzados.checked = true;
            psystemconfjavaselect.checked = true;
            psystemconffoldersize.checked = true;
            psystemconflinconsole.checked = true;
            psystemconfbuffer.checked = true;
            psystemconftypeconsole.checked = true;
            psystemconfbackup.checked = true;
            psystemstartonboot.checked = true;
            psystemcustomarg.checked = true;
            psystemconfignoreramlimit.checked = true;
        });
    }

    if (document.getElementById('deselecionarpsysconf') !== null) {
        $("#deselecionarpsysconf").click(function () {
            psystemconfpuerto.checked = false;
            psystemconfmemoria.checked = false;
            psystemconftipo.checked = false;
            psystemconfsubida.checked = false;
            psystemconfnombre.checked = false;
            psystemconfzonahoraria.checked = false;
            psystemconfavanzados.checked = false;
            psystemconfjavaselect.checked = false;
            psystemconffoldersize.checked = false;
            psystemconflinconsole.checked = false;
            psystemconfbuffer.checked = false;
            psystemconftypeconsole.checked = false;
            psystemconfbackup.checked = false;
            psystemstartonboot.checked = false;
            psystemcustomarg.checked = false;
            psystemconfignoreramlimit.checked = false;
        });
    }

    $("#formcreateadmin").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: "function/gestusercrearadmin.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                if (data == "nohayusuario") {
                    alert("No has introducido ningún usuario");
                } else if (data == "nolenuser") {
                    alert("El usuario tiene más de 255 caracteres");
                } else if (data == "nohaypassword") {
                    alert("No has introducido ninguna contraseña");
                } else if (data == "nohayrepass") {
                    alert("No has introducido el confirmar contraseña");
                } else if (data == "passwordsdiferentes") {
                    alert("Las contraseñas introducidas son diferentes");
                } else if (data == "nocumplereq") {
                    alert("La contraseña no cumple los requisitos");
                } else if (data == "errarchnoconfig") {
                    alert("Carpeta Config no existe");
                } else if (data == "errconfignoread") {
                    alert("Carpeta Config no tiene permisos de lectura");
                } else if (data == "errconfignowrite") {
                    alert("Carpeta Config no tiene permisos de escritura");
                } else if (data == "errjsonnoexist") {
                    alert("El archivo de usuarios no existe");
                } else if (data == "errjsonnoread") {
                    alert("El archivo de usuarios no tiene permisos de lectura");
                } else if (data == "errjsonnowrite") {
                    alert("El archivo de usuarios no tiene permisos de escritura");
                } else if (data == "useryaexiste") {
                    alert("El usuario ya existe");
                } else if (data == "OK") {
                    location.href = "gestorusers.php";
                }

            },
            error: function () {
                alert("error");
            }
        });
    }));

    $("#btcancelar").click(function () {
        location.href = "gestorusers.php";
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