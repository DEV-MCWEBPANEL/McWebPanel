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

    if (document.getElementById('selectodasstatus') !== null) {
        $("#selectodasstatus").click(function () {
            pstatusstarserver.checked = true;
            pstatusrestartserver.checked = true;
            pstatusstopserver.checked = true;
            pstatuskillserver.checked = true;
        });
    }

    if (document.getElementById('deselecionarstatus') !== null) {
        $("#deselecionarstatus").click(function () {
            pstatusstarserver.checked = false;
            pstatusrestartserver.checked = false;
            pstatusstopserver.checked = false;
            pstatuskillserver.checked = false;
        });
    }

    if (document.getElementById('selectodasconsola') !== null) {
        $("#selectodasconsola").click(function () {
            pconsolaread.checked = true;
            pconsolaenviar.checked = true;
        });
    }

    if (document.getElementById('deselecionarconsola') !== null) {
        $("#deselecionarconsola").click(function () {
            pconsolaread.checked = false;
            pconsolaenviar.checked = false;
        });
    }

    if (document.getElementById('selectodaspconfigmine') !== null) {
        $("#selectodaspconfigmine").click(function () {
            pconfmine.checked = true;
        });
    }

    if (document.getElementById('deselecionarpconfigmine') !== null) {
        $("#deselecionarpconfigmine").click(function () {
            pconfmine.checked = false;
        });
    }

    if (document.getElementById('selectodasprogtareas') !== null) {
        $("#selectodasprogtareas").click(function () {
            pprogtareas.checked = true;
            pprogtareascrear.checked = true;
            pprogtareaseditar.checked = true;
            pprogtareasactdes.checked = true;
            pprogtareasborrar.checked = true;
            pprogtareaslog.checked = true;
        });
    }

    if (document.getElementById('deselecionarprogtareas') !== null) {
        $("#deselecionarprogtareas").click(function () {
            pprogtareas.checked = false;
            pprogtareascrear.checked = false;
            pprogtareaseditar.checked = false;
            pprogtareasactdes.checked = false;
            pprogtareasborrar.checked = false;
            pprogtareaslog.checked = false;
        });
    }

    if (document.getElementById('selectodaspsysconf') !== null) {
        $("#selectodaspsysconf").click(function () {
            psystemconf.checked = true;
        });
    }

    if (document.getElementById('deselecionarpsysconf') !== null) {
        $("#deselecionarpsysconf").click(function () {
            psystemconf.checked = false;
        });
    }

    if (document.getElementById('selectodaspdesserv') !== null) {
        $("#selectodaspdesserv").click(function () {
            ppagedownserver.checked = true;
            ppagedownvanilla.checked = true;
            pcompilarspigot.checked = true;
            ppagedownpaper.checked = true;
        });
    }

    if (document.getElementById('deselecionarpdesserv') !== null) {
        $("#deselecionarpdesserv").click(function () {
            ppagedownserver.checked = false;
            ppagedownvanilla.checked = false;
            pcompilarspigot.checked = false;
            ppagedownpaper.checked = false;
        });
    }

    if (document.getElementById('selectodaspsubserv') !== null) {
        $("#selectodaspsubserv").click(function () {
            psubirservidor.checked = true;
        });
    }

    if (document.getElementById('deselecionarpsubserv') !== null) {
        $("#deselecionarpsubserv").click(function () {
            psubirservidor.checked = false;
        });
    }

    if (document.getElementById('selectodaspbackups') !== null) {
        $("#selectodaspbackups").click(function () {
            pbackups.checked = true;
            pbackupscrear.checked = true;
            pbackupsdescargar.checked = true;
            pbackupsrestaurar.checked = true;
            pbackupsborrar.checked = true;
            pbackupsdesrotar.checked = true;
        });
    }

    if (document.getElementById('deselecionarpbackups') !== null) {
        $("#deselecionarpbackups").click(function () {
            pbackups.checked = false;
            pbackupscrear.checked = false;
            pbackupsdescargar.checked = false;
            pbackupsrestaurar.checked = false;
            pbackupsborrar.checked = false;
            pbackupsdesrotar.checked = false;
        });
    }

    if (document.getElementById('selectodaspgestarch') !== null) {
        $("#selectodaspgestarch").click(function () {
            pgestorarchivos.checked = true;
            pgestorarchivoscrearcarpeta.checked = true;
            pgestorarchivoscopiar.checked = true;
            pgestorarchivosborrar.checked = true;
            pgestorarchivosdescomprimir.checked = true;
            pgestorarchivoscomprimir.checked = true;
            pgestorarchivosdescargar.checked = true;
            pgestorarchivoseditar.checked = true;
            pgestorarchivosrenombrar.checked = true;
            pgestorarchivossubir.checked = true;
            pgestorarchivosexcludefiles.checked = true;
        });
    }

    if (document.getElementById('deselecionarpgestarch') !== null) {
        $("#deselecionarpgestarch").click(function () {
            pgestorarchivos.checked = false;
            pgestorarchivoscrearcarpeta.checked = false;
            pgestorarchivoscopiar.checked = false;
            pgestorarchivosborrar.checked = false;
            pgestorarchivosdescomprimir.checked = false;
            pgestorarchivoscomprimir.checked = false;
            pgestorarchivosdescargar.checked = false;
            pgestorarchivoseditar.checked = false;
            pgestorarchivosrenombrar.checked = false;
            pgestorarchivossubir.checked = false;
            pgestorarchivosexcludefiles.checked = false;
        });
    }

    $("#btcrearusuario").click(function () {
        let eldata = $("#formedituser :input").serializeArray();

        $.post($("#formedituser").attr("action"), eldata, function (data) {
            if (data == "nohayusuario") {
                alert("No se ha recibido ningún usuario");
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
            } else if (data == "OK") {
                alert("Para que se apliquen los cambios el usuario editado tiene que cerrar sesión");
                location.href = "gestorusers.php";
            }

        });
    });

    $("#formedituser").submit(function () {
        return false;
    });

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