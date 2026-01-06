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

    let uncheckalinicio = document.getElementsByClassName('laseleccion');
    for (const element of uncheckalinicio) {
        element.checked = false;
    }

    if (document.getElementsByClassName('entrar') !== null) {
        let entrarbuttons = document.getElementsByClassName('entrar');
        for (const element of entrarbuttons) {
            element.addEventListener("click", function () {
                $.ajax({
                    type: "POST",
                    url: "function/carpetaentrar.php",
                    data: {
                        action: this.value
                    },
                    success: function (data) {

                        if (data == "OK") {
                            location.reload();
                        } else if (data == "nopermenter") {
                            alert("No tienes permiso de ejecucion/enter en la carpeta");
                        }


                    }
                });

            });
        }
    }

    if (document.getElementsByClassName('atras') !== null) {
        let atrasbuttons = document.getElementsByClassName('atras');
        for (const element of atrasbuttons) {
            element.addEventListener("click", function () {
                $.ajax({
                    type: "POST",
                    url: "function/carpetatras.php",
                    data: {
                        action: this.value
                    },
                    success: function (data) {

                        if (data == "OK") {
                            location.reload();
                        } else if (data == "noatras") {
                            alert("No puedes salir de la carpeta del servidor minecraft");
                        }


                    }
                });

            });
        }
    }

    if (document.getElementsByClassName('excluirbackup') !== null) {
        let excluirbackupbuttons = document.getElementsByClassName('excluirbackup');
        for (const element of excluirbackupbuttons) {
            element.addEventListener("click", function () {
                const elarrayexcfile = [String(this.value)];
                $.ajax({
                    type: "POST",
                    url: "function/gestorexcludebackup.php",
                    data: {
                        action: elarrayexcfile
                    },
                    success: function (data) {

                        if (data == "ok") {
                            location.reload();
                        } else if (data == "nada") {
                            alert("Ningún archivo/carpeta a excluir");
                        } else if (data == "rutacambiada") {
                            alert("Ruta no válida");
                        } else if (data == "noexiste") {
                            alert("Archivo/carpeta no existe");
                        } else if (data == "duplicado") {
                            alert("Archivo/carpeta ya excluido del backup");
                        } else if (data == "noread") {
                            alert("Error: El archivo excludeback.json no se puede leer");
                        } else if (data == "nowriteconfig") {
                            alert("Error: No hay permisos de escritura en config");
                        } else if (data == "nowritearchivo") {
                            alert("Error: El archivo excludeback.json no se puede escribir");
                        }
                    }
                });

            });
        }
    }

    if (document.getElementsByClassName('incluirbackup') !== null) {
        let incluirbackupbuttons = document.getElementsByClassName('incluirbackup');
        for (const element of incluirbackupbuttons) {
            element.addEventListener("click", function () {
                const elarrayincbackup = [String(this.value)];
                $.ajax({
                    type: "POST",
                    url: "function/gestorincludebackup.php",
                    data: {
                        action: elarrayincbackup
                    },
                    success: function (data) {

                        if (data == "ok") {
                            location.reload();
                        } else if (data == "nada") {
                            alert("Ningún archivo/carpeta a excluir");
                        } else if (data == "rutacambiada") {
                            alert("Ruta no válida");
                        } else if (data == "noexiste") {
                            alert("Archivo/carpeta no existe");
                        } else if (data == "noread") {
                            alert("Error: El archivo excludeback.json no se puede leer");
                        } else if (data == "nowriteconfig") {
                            alert("Error: No hay permisos de escritura en config");
                        } else if (data == "nowritearchivo") {
                            alert("Error: El archivo excludeback.json no se puede escribir");
                        }
                    }
                });

            });
        }
    }

    if (document.getElementsByClassName('borrarfile') !== null) {
        let borrarfilebuttons = document.getElementsByClassName('borrarfile');
        for (const element of borrarfilebuttons) {
            element.addEventListener("click", function () {
                let eleccion = confirm("¡ATENCIÓN!\n\n¿Estás seguro de eliminar el archivo: " + this.id + " ?");
                if (eleccion) {
                    const elarrayborrarfile = [String(this.value)];
                    $.ajax({
                        type: "POST",
                        url: "function/gestorborrarfile.php",
                        data: {
                            action: elarrayborrarfile
                        },
                        success: function (data) {

                            if (data == "1") {
                                location.reload();
                            } else if (data == "noexiste") {
                                alert("El archivo no existe");
                                location.reload();
                            } else if (data == "nada") {
                                alert("No hay ruta a borrar");
                            } else if (data == "rutacambiada") {
                                alert("Ruta no válida");
                            } else if (data == "novalido") {
                                alert("Ruta no válida");
                            } else if (data == "nowrite") {
                                alert("El archivo no tiene permisos de escritura");
                            }


                        }
                    });
                }
            });
        }
    }

    if (document.getElementsByClassName('borrarcarpeta') !== null) {
        let borrarcarpetabuttons = document.getElementsByClassName('borrarcarpeta');
        for (const element of borrarcarpetabuttons) {
            element.addEventListener("click", function () {
                let eleccion = confirm("¡ATENCIÓN!\n\n¿Estás seguro de eliminar la carpeta: " + this.id + " ?");
                if (eleccion) {
                    const elarrayborrarcarp = [String(this.value)];
                    $.ajax({
                        type: "POST",
                        url: "function/gestorborrarcarpeta.php",
                        data: {
                            action: elarrayborrarcarp
                        },
                        success: function (data) {

                            if (data == "1") {
                                location.reload();
                            } else if (data == "nada") {
                                alert("No hay ruta");
                            } else if (data == "rutacambiada") {
                                alert("Ruta no válida");
                            } else if (data == "novalido") {
                                alert("Ruta no válida");
                            } else if (data == "noexiste") {
                                alert("La carpeta no existe");
                                location.reload();
                            } else if (data == "nowrite") {
                                alert("La carpeta no tiene permisos de escritura");
                            } else if (data == "nopermenter") {
                                alert("No tienes permiso de ejecucion/enter en la carpeta");
                            } else if (data == "noborrado") {
                                alert("Error: La carpeta no se pudo borrar");
                            }


                        }
                    });
                }
            });
        }
    }

    if (document.getElementsByClassName('renamefile') !== null) {
        let renamefilebuttons = document.getElementsByClassName('renamefile');
        for (const element of renamefilebuttons) {
            element.addEventListener("click", function () {
                let renombrado = prompt("Renombrar fichero:", this.id);
                if (renombrado !== null) {
                    $.ajax({
                        type: "POST",
                        url: "function/gestorenamefile.php",
                        data: {
                            action: this.value,
                            renombre: renombrado
                        },
                        success: function (data) {

                            if (data == "1") {
                                location.reload();
                            } else if (data == "revacio") {
                                alert("El renombre esta vacío");
                            } else if (data == "archvacio") {
                                alert("La ruta esta vacía");
                            } else if (data == "rutacambiada") {
                                alert("Ruta no válida");
                            } else if (data == "novalido") {
                                alert("Ruta no válida");
                            } else if (data == "extensionnovalid") {
                                alert("No se permite modificar o cambiar el nombre de archivo a esta extensión");
                            } else if (data == "renomnovalido") {
                                alert("Renombre no válido");
                            } else if (data == "noexiste") {
                                alert("El archivo no existe");
                                location.reload();
                            } else if (data == "yaexiste") {
                                alert("Ya existe un archivo con ese nombre");
                            } else if (data == "nowrite") {
                                alert("El archivo no tiene permisos de escritura");
                            }

                        }
                    });
                }
            });
        }
    }

    if (document.getElementsByClassName('renamefolder') !== null) {
        let renamefolderbuttons = document.getElementsByClassName('renamefolder');
        for (const element of renamefolderbuttons) {
            element.addEventListener("click", function () {
                let renombrado = prompt("Renombrar carpeta:", this.id);
                if (renombrado !== null) {
                    $.ajax({
                        type: "POST",
                        url: "function/gestorrenamefolder.php",
                        data: {
                            action: this.value,
                            renombre: renombrado
                        },
                        success: function (data) {

                            if (data == "1") {
                                location.reload();
                            } else if (data == "revacio") {
                                alert("El renombre esta vacío");
                            } else if (data == "archvacio") {
                                alert("La ruta esta vacía");
                            } else if (data == "rutacambiada") {
                                alert("Ruta no valida");
                            } else if (data == "novalido") {
                                alert("Ruta no valida");
                            } else if (data == "renomnovalido") {
                                alert("Renombre no válido");
                            } else if (data == "noexiste") {
                                alert("El archivo no existe");
                                location.reload();
                            } else if (data == "yaexiste") {
                                alert("Ya existe una carpeta con ese nombre");
                            } else if (data == "nowrite") {
                                alert("La carpeta no tiene permisos de escritura");
                            }

                        }
                    });
                }
            });
        }
    }

    if (document.getElementsByClassName('editarfile') !== null) {
        let editarbuttons = document.getElementsByClassName('editarfile');
        for (const element of editarbuttons) {
            element.addEventListener("click", function () {
                $.ajax({
                    type: "POST",
                    url: "function/gestoreditarfile.php",
                    data: {
                        action: this.value
                    },
                    success: function (data) {

                        if (data == "OK") {
                            location.href = "editararchivo.php";
                        } else if (data == "noruta") {
                            alert("No se ha pasado ningún archivo a editar");
                        } else if (data == "noextvalid") {
                            alert("Extensión archivo no aceptada");
                        } else if (data == "nowrite") {
                            alert("El archivo no tiene permisos de escritura");
                        } else if (data == "noexiste") {
                            alert("El archivo no existe");
                            location.reload();
                        }


                    }
                });

            });
        }
    }

    if (document.getElementsByClassName('descargarfile') !== null) {
        let descargarbuttons = document.getElementsByClassName('descargarfile');
        for (const element of descargarbuttons) {
            element.addEventListener("click", function () {
                window.open('function/gestordownfile.php?action=' + this.value, '_blank', 'noopener noreferrer', "toolbar=no,scrollbars=yes,resizable=yes,top=400,left=500,width=400,height=100");
            });
        }
    }

    if (document.getElementsByClassName('descomprimirtar') !== null) {
        let descomprimirtarbuttons = document.getElementsByClassName('descomprimirtar');
        for (const element of descomprimirtarbuttons) {
            element.addEventListener("click", function () {
                let eleccion = confirm("¡DESCOMPRIMIR TAR!\n\n¿Seguro que quieres descomprimir el archivo: " + this.value)
                if (eleccion == true) {
                    $.ajax({
                        url: 'function/gestordescomprimirtar.php',
                        data: {
                            action: this.value
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) {
                            if (data.eserror == "nada") {
                                alert("No se ha pasado ningún archivo a descomprimir");
                            } else if (data.eserror == "notargz") {
                                alert("El archivo no es .tag.gz");
                            } else if (data.eserror == "notarbz2") {
                                alert("El archivo no es .tag.bz2");
                            } else if (data.eserror == "notar") {
                                alert("El archivo no es .tar");
                            } else if (data.eserror == "noexiste") {
                                alert("El archivo tar a descomprimir no existe");
                                location.reload();
                            } else if (data.eserror == "carpyaexiste") {
                                alert("No se puede descomprimir, la carpeta: " + data.carpeta + " ya existe");
                            } else if (data.eserror == "no") {
                                alert("Error al descomprimir");
                            } else if (data.eserror == "ERRORGETSIZE") {
                                alert("Error: No se puede obtener los GB de la carpeta minecraft");
                            } else if (data.eserror == "OUTGIGAS") {
                                alert("Error: Has superado los GB asignados a la carpeta minecraft");
                            } else if (data.eserror == "notempexiste") {
                                alert("Error: La carpeta temp no existe");
                            } else if (data.eserror == "notempwritable") {
                                alert("Error: La carpeta temp no tiene permisos de escritura.");
                            } else if (data.eserror == "processenejecucion") {
                                alert("Error: Existe un proceso en ejecución.");
                            } else if (data.eserror == "novaltipe") {
                                alert("Error: El tipo de archivo no es válido o es falso.");
                            }

                        }
                    });
                }
            });
        }
    }

    if (document.getElementsByClassName('descomprimirzip') !== null) {
        let descomprimirzipbuttons = document.getElementsByClassName('descomprimirzip');
        for (const element of descomprimirzipbuttons) {
            element.addEventListener("click", function () {
                let eleccion = confirm("¡DESCOMPRIMIR ZIP!\n\n¿Seguro que quieres descomprimir el archivo: " + this.value)
                if (eleccion == true) {
                    $.ajax({
                        url: 'function/gestordescomprimirzip.php',
                        data: {
                            action: this.value
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) {
                            if (data.eserror == "nada") {
                                alert("No se ha pasado ningún archivo a descomprimir");
                            } else if (data.eserror == "noexiste") {
                                alert("El archivo a descomprimir no existe");
                                location.reload();
                            } else if (data.eserror == "nozip") {
                                alert("El archivo no es .zip");
                            } else if (data.eserror == "carpyaexiste") {
                                alert("No se puede descomprimir, la carpeta: " + data.carpeta + " ya existe");
                            } else if (data.eserror == "ERRORGETSIZE") {
                                alert("Error: No se puede obtener los GB de la carpeta minecraft");
                            } else if (data.eserror == "OUTGIGAS") {
                                alert("Error: Has superado los GB asignados a la carpeta minecraft");
                            } else if (data.eserror == "fallo") {
                                alert("Error al descomprimir");
                            } else if (data.eserror == "notempexiste") {
                                alert("Error: La carpeta temp no existe");
                            } else if (data.eserror == "notempwritable") {
                                alert("Error: La carpeta temp no tiene permisos de escritura.");
                            } else if (data.eserror == "processenejecucion") {
                                alert("Error: Existe un proceso en ejecución.");
                            } else if (data.eserror == "novaltipe") {
                                alert("Error: El tipo de archivo no es válido o es falso.");
                            }

                        }
                    });
                }
            });
        }
    }

    if (document.getElementsByClassName('comprimirzipfolder') !== null) {
        let comprimircarpetazipbuttons = document.getElementsByClassName('comprimirzipfolder');
        for (const element of comprimircarpetazipbuttons) {
            element.addEventListener("click", function () {
                let eleccion = confirm("¡COMPRIMIR CARPETA!\n\n¿Seguro que quieres comprimir la carpeta: " + this.value +" en ZIP?")
                if (eleccion == true) {
                    $.ajax({
                        url: 'function/gestorcomprimircarpetazip.php',
                        data: {
                            action: this.value
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) {

                            if (data.eserror == "nada") {
                                alert("No se ha pasado ningún archivo a descomprimir");
                            } else if (data.eserror == "noexiste") {
                                alert("La carpeta a comprimir no existe");
                                location.reload();
                            } else if (data.eserror == "carpyaexiste") {
                                alert("No se puede comprimir, la carpeta: " + data.carpeta + " ya existe");
                            } else if (data.eserror == "fallo") {
                                alert("Error al comprimir");
                            } else if (data.eserror == "nopermenter") {
                                alert("No tienes permiso de ejecucion/enter en la carpeta");
                            } else if (data.eserror == "ERRORGETSIZE") {
                                alert("Error: No se puede obtener los GB de la carpeta minecraft");
                            } else if (data.eserror == "OUTGIGAS") {
                                alert("Error: Has superado los GB asignados a la carpeta minecraft");
                            } else if (data.eserror == "notempexiste") {
                                alert("Error: La carpeta temp no existe");
                            } else if (data.eserror == "notempwritable") {
                                alert("Error: La carpeta temp no tiene permisos de escritura.");
                            } else if (data.eserror == "processenejecucion") {
                                alert("Error: Existe un proceso en ejecución.");
                            }

                        }
                    });
                }
            });
        }
    }

    if (document.getElementById('botonsubir') !== null) {
        document.getElementById("botonsubir").disabled = true;
    }

    if (document.getElementById('gifuploading') !== null) {
        document.getElementById("gifuploading").style.visibility = "hidden";
    }

    $(".custom-file-input").on("change", function () {
        let fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

        let elarchivo = document.getElementById('fileName');
        let eltamano = elarchivo.files.item(0).size;

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
                } else if (data == "OUTLIMITE") {
                    if (document.getElementById('botonsubir') !== null) {
                        document.getElementById("botonsubir").disabled = true;

                    }
                    document.getElementById('fileName').value = "";
                    $('#lvltext').text("Elija el archivo");
                    alert("Error: El archivo supera los GB asignados a la carpeta minecraft");
                } else if (data == "NOFREESPACE") {
                    if (document.getElementById('botonsubir') !== null) {
                        document.getElementById("botonsubir").disabled = true;

                    }
                    document.getElementById('fileName').value = "";
                    $('#lvltext').text("Elija el archivo");
                    alert("Error: No hay espacio libre suficiente en la carpeta minecraft para subir el archivo");
                }
            }
        });

    });

    if (document.getElementById('bnactualizar') !== null) {
        $("#bnactualizar").click(function () {
            location.reload();
        });
    }

    if (document.getElementById('bnnuevacarpeta') !== null) {
        $("#bnnuevacarpeta").click(function () {
            let renombrado = prompt("Nombre nueva carpeta:");
            const elarraycrear = [String(renombrado)];
            if (renombrado !== null) {
                $.ajax({
                    url: 'function/gestorcrearcarpeta.php',
                    data: {
                        action: elarraycrear
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data == "nowrite") {
                            alert("No hay permisos de escritura");
                        } else if (data == "carpyaexiste") {
                            alert("La carpeta ya existe");
                        } else if (data == "norenom") {
                            alert("Renombre no válido");
                        } else if (data == "novalido") {
                            alert("Nombre no válido");
                        } else if (data == "OUTGIGAS") {
                            alert("Error: Has superado los GB asignados a la carpeta minecraft");
                        } else if (data == "OK") {
                            location.reload();
                        }
                    }
                });
            }
        });
    }

    if (document.getElementById('bcopiar') !== null) {
        $("#bcopiar").click(function () {
            let arrayseleccion = [];
            let elindice = 0;
            let checkseleccionados = document.getElementsByClassName('laseleccion');
            for (const element of checkseleccionados) {
                if (element.checked == true) {
                    arrayseleccion[elindice] = element.value;
                    elindice = elindice + 1;
                }
            }

            if (arrayseleccion == "") {
                alert("No has seleccionado ningún elemento");
            } else {
                $.ajax({
                    url: 'function/gestorcopiarfiles.php',
                    data: {
                        action: arrayseleccion
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data == "nocopy") {
                            alert("Nada que copiar");
                        } else if (data == "noexiste") {
                            alert("Hay archivos que no existen");
                            location.reload();
                        } else if (data == "nopermenter") {
                            alert("Hay carpetas que no tienen permiso de ejecucion/enter");
                        } else if (data == "OUTGIGAS") {
                            alert("Error: Has superado los GB asignados a la carpeta minecraft");
                        } else if (data == "OK") {
                            location.reload();
                        }

                    }
                });
            }
        });
    }

    if (document.getElementById('bpegar') !== null) {
        $("#bpegar").click(function () {
            let eleccion = confirm("¡CONFIRMAR ACCION!\n\nEn caso de existir un archivo con el mismo nombre se sobrescribirá.\n\n¿Seguro que quieres continuar?");
            if (eleccion == true) {

                $.ajax({
                    url: 'function/gestorpegarfiles.php',
                    data: {
                        action: 'ok'
                    },
                    type: 'POST',
                    success: function (data) {

                        if (data == "nocopy") {
                            alert("Nada que pegar");
                        } else if (data == "noexiste") {
                            alert("Se cancela el pegado, el archivo no existe");
                        } else if (data == "nowrite") {
                            alert("La ruta a pegar no tiene permisos de escritura");
                        } else if (data == "ERRORGETSIZE") {
                            alert("Error: No se puede obtener los GB de la carpeta minecraft");
                        } else if (data == "OUTGIGAS") {
                            alert("Error: Has superado los GB asignados a la carpeta minecraft");
                        } else if (data == "notempexiste") {
                            alert("Error: La carpeta temp no existe");
                        } else if (data == "notempwritable") {
                            alert("Error: La carpeta temp no tiene permisos de escritura.");
                        } else if (data == "processenejecucion") {
                            alert("Error: Existe un proceso en ejecución.");
                        }
                    }
                });
            }
        });
    }

    if (document.getElementById('beliminarseleccion') !== null) {
        $("#beliminarseleccion").click(function () {
            let arrayseleccion = [];
            let elindice = 0;
            let checkseleccionados = document.getElementsByClassName('laseleccion');

            for (const element of checkseleccionados) {
                if (element.checked == true) {
                    arrayseleccion[elindice] = element.value;
                    elindice = elindice + 1;
                }
            }

            if (arrayseleccion == "") {
                alert("No has seleccionado ningún elemento");
            } else {
                let eleccion = confirm("¡ELIMINAR CONFIRMAR ACCIÓN!\n\n¡Vas a eliminar las carpetas o archivos seleccionados!\n\n¿Seguro que quieres continuar?");
                if (eleccion == true) {
                    $.ajax({
                        url: 'function/gestorborrarmultiple.php',
                        data: {
                            action: arrayseleccion
                        },
                        type: 'POST',
                        success: function (data) {

                            if (data == "nocopy") {
                                alert("Nada que borrar");
                            } else if (data == "rutacambiada") {
                                alert("Ruta no válida");
                            } else if (data == "novalido") {
                                alert("Ruta no válida");
                            } else if (data == "noexiste") {
                                alert("Hay archivos que no existen");
                                location.reload();
                            } else if (data == "nowrite") {
                                alert("Hay archivos sin permisos de escritura");
                            } else if (data == "nopermenter") {
                                alert("Hay carpetas sin permiso de ejecucion/enter en la carpeta");
                            } else if (data == "OK") {
                                location.reload();
                            }

                        }
                    });
                }
            }
        });
    }

    if (document.getElementById('bselectall') !== null) {
        $("#bselectall").click(function () {
            let checkseleccionados = document.getElementsByClassName('laseleccion');
            for (const element of checkseleccionados) {
                if (element.disabled == false) {
                    element.checked = true;
                }
            }
        });
    }

    if (document.getElementById('bunselectall') !== null) {
        $("#bunselectall").click(function () {
            let checkseleccionados = document.getElementsByClassName('laseleccion');
            for (const element of checkseleccionados) {
                element.checked = false;
            }
        });
    }

    if (document.getElementById('resetexcluidos') !== null) {
        $("#resetexcluidos").click(function () {
            let eleccion = confirm("Borrar listado excluidos!\n\n¡Vas a eliminar el listado de archivos excluidos!\n\n¿Seguro que quieres continuar?");
            if (eleccion == true) {
                $.ajax({
                    url: 'function/gestorborrarexcluidos.php',
                    data: {
                        action: 'status'
                    },
                    type: 'POST',
                    success: function (data) {

                        if (data == "ok") {
                            location.reload();
                        } else if (data == "nowriteconfig") {
                            alert("Error: La carpeta config no tiene permisos de escritura");
                        } else if (data == "nowriteexcluido") {
                            alert("Error: El archivo excludeback.json no tiene permisos de escritura");
                        }
                    }
                });
            }

        });
    }



    //INICIAR VARIABLE SESSION
    sessionStorage.pulsorecuadro = 0;

    if (document.getElementsByClassName('elclick1') !== null) {
        let clickhover = document.getElementsByClassName('elclick1');
        let checkseleccionados = document.getElementsByClassName('laseleccion');
        for (const element of clickhover) {
            element.addEventListener("click", function () {

                if (sessionStorage.pulsorecuadro == 0) {

                    if (checkseleccionados[this.id - 1].disabled == false) {
                        if (checkseleccionados[this.id - 1].checked == false) {
                            checkseleccionados[this.id - 1].checked = true;
                        } else {
                            checkseleccionados[this.id - 1].checked = false;
                        }
                    }

                } else {
                    sessionStorage.pulsorecuadro = 0;
                }

            });
        }
    }

    if (document.getElementsByClassName('laseleccion') !== null) {
        let checkseleccionados = document.getElementsByClassName('laseleccion');
        for (const element of checkseleccionados) {
            element.addEventListener("click", function () {

                sessionStorage.pulsorecuadro = 1;
            });
        }
    }

    $("#form").on('submit', (function (e) {
        if (document.getElementById('gifuploading') !== null) {
            document.getElementById("gifuploading").style.visibility = "visible";
        }
        e.preventDefault();
        $.ajax({
            url: "function/gestoruploadfile.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                if (document.getElementById('gifuploading') !== null) {
                    document.getElementById("gifuploading").style.visibility = "hidden";
                }

                if (data == "nowrite") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta no tiene permisos de escritura.</div>";
                } else if (data == "yaexiste") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo ya existe.</div>";
                } else if (data == "novalido") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se acepta ese tipo de archivo.</div>";
                } else if (data == "novaltipe") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se acepta ese tipo de archivo.</div>";
                } else if (data == "novalidoname") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Nombre archivo no válido.</div>";
                } else if (data == "ERRORGETSIZE") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se puede obtener los GB de la carpeta minecraft.</div>";
                } else if (data == "OUTGIGAS") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Has superado los GB asignados a la carpeta minecraft.</div>";
                } else if (data == "errprocess") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Errores en el proceso de subida del archivo.</div>";
                } else if (data == "errorupload") {
                    document.getElementById("textouploadretorno").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Ha habido un error subiendo el archivo.</div>";
                } else if (data == "subidook") {
                    location.reload();
                }

            }
        });
    }));

    function sessionTimer() {

        $.ajax({
            url: 'function/gestorstate.php',
            data: {
                action: 'estado'
            },
            type: 'POST',
            success: function (data) {

                if (data == "ON") {

                    if (document.getElementById('gifstatus') !== null) {
                        document.getElementById('gifstatus').innerHTML = '<img class="" src="img/loading.gif" id="gifloading" alt="loading">';
                        document.getElementById("gifstatus").style.visibility = "visible";
                    }
                } else if (data == "OFF") {

                    if (document.getElementById('gifstatus') !== null) {
                        document.getElementById("gifstatus").style.visibility = "hidden";
                        document.getElementById('gifstatus').innerHTML = '';
                    }
                } else if (data == "REFRESH") {
                    location.reload();
                }
            }
        });

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