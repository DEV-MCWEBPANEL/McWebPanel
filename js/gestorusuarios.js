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

    $("#crearadmin").click(function () {
        location.href = "gestadmincreate.php";
    });

    $("#crearuser").click(function () {
        location.href = "gestusercreate.php";
    });

    let actdesuserbuttons = document.getElementsByClassName('actdesuser');
    for (const element of actdesuserbuttons) {
        element.addEventListener("click", function () {
            let indexarray = String(this.value);
            $.ajax({
                url: 'function/gestuseractdesusuario.php',
                data: {
                    action: indexarray
                },
                type: 'POST',
                success: function (data) {

                    if (data == "nohayusuario") {
                        alert("No has introducido ningun usuario");
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
                        location.href = "gestorusers.php";
                    }
                }
            });
        });
    }

    let cambiartemabuttons = document.getElementsByClassName('cambiartema');
    for (const element of cambiartemabuttons) {
        element.addEventListener("click", function () {
            let indexarray = String(this.value);
            $.ajax({
                url: 'function/gestusercambiartema.php',
                data: {
                    action: indexarray
                },
                type: 'POST',
                success: function (data) {

                    if (data == "nohayusuario") {
                        alert("No has introducido ningun usuario");
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
                        location.reload();
                    }
                }
            });
        });
    }

    let edituserbuttons = document.getElementsByClassName('edituser');
    for (const element of edituserbuttons) {
        element.addEventListener("click", function () {
            let indexarray = String(this.value);
            $.ajax({
                url: 'function/gestusercalleditaruser.php',
                data: {
                    action: indexarray
                },
                type: 'POST',
                success: function (data) {

                    if (data == "nohayusuario") {
                        alert("No has introducido ningun usuario");
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
                    } else if (data == "usernoexiste") {
                        alert("El usuario no existe");
                    } else if (data == "OKSUPER") {
                        location.href = "gestadmineditar.php";
                    } else if (data == "OKADMIN") {
                        location.href = "gestusereditar.php";
                    }
                }
            });
        });
    }

    let deluserbuttons = document.getElementsByClassName('deluser');
    for (const element of deluserbuttons) {
        element.addEventListener("click", function () {
            let indexarray = String(this.value);
            $.ajax({
                url: 'function/gestusereliminarusuario.php',
                data: {
                    action: indexarray
                },
                type: 'POST',
                success: function (data) {

                    if (data == "nohayusuario") {
                        alert("No has introducido ningun usuario");
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
                        location.href = "gestorusers.php";
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