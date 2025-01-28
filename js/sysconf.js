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

    $("#guardaserver").click(function () {
        document.getElementById("result").innerHTML = '<img src="img/guardando.gif" alt="Guardando">'

        let eldata = $("#formconf :input").serializeArray();

        document.getElementById('finpage').scrollIntoView();

        $.post($("#formconf").attr("action"), eldata, function (data) {

            if (data == "novalidoname") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Nombre Servidor Jar no válido.</div>";
            } else if (data == "noexistejar") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El Servidor Jar no existe.</div>";
            } else if (data == "notipovalido") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El Servidor Jar no es válido.</div>";
            } else if (data == "portnonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El puerto introducido no es numérico.</div>";
            } else if (data == "portoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El puerto está fuera de rango (1024-65535).</div>";
            } else if (data == "portvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo puerto está vacío.</div>";
            } else if (data == "ramnonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ram introducida no es numérica.</div>";
            } else if (data == "raminsuficiente") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Ram insuficiente.</div>";
            } else if (data == "ramoutrange") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ram introducida supera a la del sistema.</div>";
            } else if (data == "ramnooutput") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se ha podido obtener la memoria del sistema.</div>";
            } else if (data == "ramvacia") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo ram está vacío.</div>";
            } else if (data == "badtipserv") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El tipo de servidor no es válido.</div>";
            } else if (data == "tipservvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo tipo servidor está vacío.</div>";
            } else if (data == "badmaxupload") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Los MB de upload no son válidos.</div>";
            } else if (data == "maxuploadvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo Upload MB está vacío.</div>";
            } else if (data == "nomservvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo nombre servidor está vacío.</div>";
            } else if (data == "bootconfvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo config boot está vacío.</div>";
            } else if (data == "lineasconsolanonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Las lineas consola no es numérico.</div>";
            } else if (data == "lineasconsolaoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Lineas consola fuera de rango (0-1000).</div>";
            } else if (data == "linconsolavacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo linea consola está vacío.</div>";
            } else if (data == "buffernonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El buffer no es numérico.</div>";
            } else if (data == "bufferoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Buffer fuera de rango (0-500).</div>";
            } else if (data == "buffervacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo Buffer está vacío.</div>";
            } else if (data == "typenonumero") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo tipo consola no es numérico.</div>";
            } else if (data == "typeoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo tipo consola fuera de rango (0-2).</div>";
            } else if (data == "typeconsolavacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo tipo consola está vacío.</div>";
            } else if (data == "nowriteconf") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta config no tiene permisos de escritura.</div>";
            } else if (data == "nocarpetaconf") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La carpeta conf no existe.</div>";
            } else if (data == "nowritehtaccess") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo .htaccess en la raíz, no tiene permisos de escritura.</div>";
            } else if (data == "nojavaenruta") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo java no se encuentra en la ruta.</div>";
            } else if (data == "nojavaencontrado") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El archivo java no encontrado en el sistema.</div>";
            } else if (data == "nojavaoutput") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se ha podido obtener las versiones java del sistema.</div>";
            } else if (data == "datolimitebacksuperior") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Has asignado más gigas en backups de lo permitido.</div>";
            } else if (data == "datolimiteminesuperior") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Has asignado más gigas en minecraft de lo permitido.</div>";
            } else if (data == "valornonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: Has asignado un valor incorrecto no numérico.</div>";
            } else if (data == "novalido") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ruta introducida no es válida.</div>";
            } else if (data == "inpanel") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se puede asignar una ruta dentro del panel.</div>";
            } else if (data == "elargmanuininovalid") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El argumento del inicio no es válido.</div>";
            } else if (data == "elargmanufinalnovalid") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El argumento del final no es válido.</div>";
            } else if (data == "ramxmsoutrange") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ram introducida en Xms supera a la del sistema.</div>";
            } else if (data == "xmsuperiorram") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ram Xms no puede ser superior a la ram Xmx.</div>";
            } else if (data == "xmsmodexternal") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ram Xms introducida ha sido manipulada.</div>";
            } else if (data == "xmxmodexternal") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La ram Xmx introducida ha sido manipulada.</div>";
            } else if (data == "backupmultinonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo usó cpu no es numérico.</div>";
            } else if (data == "backupmultioutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo usó cpu fuera de rango (1-2).</div>";
            } else if (data == "backupmultinopigz") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: No se puede elegir Multinucleo, PIGZ no instalado en el servidor.</div>";
            } else if (data == "backupmultivacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo usó cpu está vacío.</div>";
            } else if (data == "backupcompressnonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo compresión no es numérico.</div>";
            } else if (data == "backupcompressoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo compresión fuera de rango (0-9).</div>";
            } else if (data == "backupcompressvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo compresión está vacío.</div>";
            } else if (data == "backuphilosnonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo hilos no es numérico.</div>";
            } else if (data == "backuphilosoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo hilos fuera de rango.</div>";
            } else if (data == "backuphilosexcedcores") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo hilos supera el número de hilos del servidor.</div>";
            } else if (data == "backuphilosvacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo hilos está vacío.</div>";
            } else if (data == "backuprotatenonumerico") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo rotación backups no es numérico.</div>";
            } else if (data == "backuprotatesoutrango") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo rotación backups fuera de rango.</div>";
            } else if (data == "backuprotatevacio") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: El campo rotación backups está vacío.</div>";
            } else if (data == "zonanoenlista") {
                document.getElementById("result").innerHTML = "<div class='alert alert-danger' role='alert'>Error: La zona horaria no existe o es incorrecta.</div>";
            } else if (data == "saveconf") {
                document.getElementById("result").innerHTML = "<div class='alert alert-success' role='alert'>Configuración Guardada.</div>";
                document.getElementById("guardaserver").disabled = true;
            } else {
                document.getElementById("result").innerHTML = "";
            }
        });

    });

    $("#formconf").submit(function () {
        return false;
    });

    if (document.getElementById('guardaserver') !== null) {
        document.getElementById("guardaserver").disabled = true;
    }

    if (document.getElementById('elnomserv') !== null) {
        $("#elnomserv").keyup(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";

            if (this.value == "") {
                document.getElementById("guardaserver").disabled = true;
            }

        });

        document.getElementById("elnomserv").addEventListener('paste', function () {
            document.getElementById("guardaserver").disabled = false;
        });
    }

    if (document.getElementById('eltipserv') !== null) {
        $("#eltipserv").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('elmaxupload') !== null) {
        $("#elmaxupload").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('elraminicial') !== null) {
        $("#elraminicial").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('elram') !== null) {
        $("#elram").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('listadojars') !== null) {
        $("#listadojars").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('elbootconf') !== null) {
        $("#elbootconf").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('zona_horaria') !== null) {
        $("#zona_horaria").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('basura0') !== null) {
        $("#basura0").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('basura1') !== null) {
        $("#basura1").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('basura2') !== null) {
        $("#basura2").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('opforceupgrade') !== null) {
        $("#opforceupgrade").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('operasecache') !== null) {
        $("#operasecache").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('gestorshowsizefolder') !== null) {
        $("#gestorshowsizefolder").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('gestorignoreram') !== null) {
        $("#gestorignoreram").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('backupmulti') !== null) {
        $("#backupmulti").change(function () {

            if (document.getElementById('backupmulti').value == 1) {
                document.getElementById('backuphilos').disabled = true;
                document.getElementById('backuphilos').value = 1;
            }

            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('backupcompress') !== null) {
        $("#backupcompress").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('backuphilos') !== null) {
        $("#backuphilos").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('backuprotate') !== null) {
        $("#backuprotate").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('argmanualinicio') !== null) {
        document.getElementById('argmanualinicio').addEventListener('keydown', function (event) {
            const key = event.key;

            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";

            document.getElementById("argmanualinicio").addEventListener('paste', function () {
                document.getElementById("guardaserver").disabled = false;
            });

        });
    }

    if (document.getElementById('argmanualinicio') !== null) {

        document.getElementById("argmanualinicio").addEventListener('paste', function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('argmanualfinal') !== null) {
        document.getElementById('argmanualfinal').addEventListener('keydown', function (event) {
            const key = event.key;

            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";

            document.getElementById("argmanualfinal").addEventListener('paste', function () {
                document.getElementById("guardaserver").disabled = false;
            });

        });
    }

    if (document.getElementById('argmanualfinal') !== null) {

        document.getElementById("argmanualfinal").addEventListener('paste', function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }


    if (document.getElementById('modomantenimiento') !== null) {
        $("#modomantenimiento").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('configjavaselect0') !== null) {
        $("#configjavaselect0").change(function () {
            if (document.getElementById('javamanual') !== null) {
                document.getElementById('javamanual').value = "";
            }
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('configjavaselect1') !== null) {
        $("#configjavaselect1").change(function () {
            if (document.getElementById('javamanual') !== null) {
                document.getElementById('javamanual').value = "";
            }
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('configjavaselect2') !== null) {
        $("#configjavaselect2").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('selectedjavaver') !== null) {
        $("#selectedjavaver").change(function () {
            if (document.getElementById('configjavaselect1') !== null) {
                document.getElementById('configjavaselect1').checked = true;
            }
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('javamanual') !== null) {
        $("#javamanual").keypress(function () {
            if (document.getElementById('configjavaselect2') !== null) {
                document.getElementById('configjavaselect2').checked = true;
            }
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('javamanual') !== null) {
        document.getElementById("javamanual").addEventListener('paste', function () {

            if (document.getElementById('configjavaselect2') !== null) {
                document.getElementById('configjavaselect2').checked = true;
            }

            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";

        });
    }

    if (document.getElementById('elport') !== null) {
        $("#elport").change(function () {
            let elnumero = document.getElementById("elport").value;
            document.getElementById("result").innerHTML = "";

            if (elnumero >= 1024 && elnumero <= 65535) {
                document.getElementById("guardaserver").disabled = false;
            } else {
                document.getElementById("elport").value = "";
            }
        });
    }

    if (document.getElementById('elport') !== null) {
        $("#elport").keypress(function (e) {
            return !(e.keyCode < 48 || e.keyCode > 57);
        });
    }

    if (document.getElementById('linconsola') !== null) {
        $("#linconsola").change(function () {
            let elnumero = document.getElementById("linconsola").value;
            document.getElementById("result").innerHTML = "";

            if (elnumero < 0 || elnumero > 1000) {
                document.getElementById("linconsola").value = "100";
            } else {
                document.getElementById("guardaserver").disabled = false;
            }
        });
    }

    if (document.getElementById('linconsola') !== null) {
        $("#linconsola").keypress(function (e) {
            return !(e.keyCode < 48 || e.keyCode > 57);
        });
    }

    if (document.getElementById('bufferlimit') !== null) {
        $("#bufferlimit").change(function () {
            let elnumero = document.getElementById("bufferlimit").value;
            document.getElementById("result").innerHTML = "";

            if (elnumero < 0 || elnumero > 500) {
                document.getElementById("bufferlimit").value = "100";
            } else {
                document.getElementById("guardaserver").disabled = false;
            }
        });
    }

    if (document.getElementById('bufferlimit') !== null) {
        $("#bufferlimit").keypress(function (e) {
            return !(e.keyCode < 48 || e.keyCode > 57);
        });
    }

    if (document.getElementById('eltipoconsola') !== null) {
        $("#eltipoconsola").change(function () {
            document.getElementById("guardaserver").disabled = false;
            document.getElementById("result").innerHTML = "";
        });
    }

    if (document.getElementById('limitbackupgb') !== null) {
        $("#limitbackupgb").change(function () {
            let elnumero = document.getElementById("limitbackupgb").value;
            document.getElementById("result").innerHTML = "";

            if (elnumero < 0 || elnumero > 100) {
                document.getElementById("limitbackupgb").value = "";
            } else {
                document.getElementById("guardaserver").disabled = false;
            }
        });
    }

    if (document.getElementById('limitbackupgb') !== null) {
        $("#limitbackupgb").keypress(function (e) {
            return !(e.keyCode < 48 || e.keyCode > 57);
        });
    }

    if (document.getElementById('limitminecraftgb') !== null) {
        $("#limitminecraftgb").change(function () {
            let elnumero = document.getElementById("limitminecraftgb").value;
            document.getElementById("result").innerHTML = "";

            if (elnumero < 0 || elnumero > 100) {
                document.getElementById("limitminecraftgb").value = "";
            } else {
                document.getElementById("guardaserver").disabled = false;
            }
        });
    }

    if (document.getElementById('limitminecraftgb') !== null) {
        $("#limitminecraftgb").keypress(function (e) {
            return !(e.keyCode < 48 || e.keyCode > 57);
        });
    }

    if (document.getElementById('backuprotate') !== null) {
        $("#backuprotate").keypress(function (e) {
            return !(e.keyCode < 48 || e.keyCode > 57);
        });
    }

    function obtenerhora() {

        $.ajax({
            url: 'function/gethora.php',
            data: {
                action: 'status'
            },
            type: 'POST',
            success: function (lahora) {
                if (document.getElementById('horazonahorariaactual') !== null) {
                    document.getElementById("horazonahorariaactual").innerHTML = "<span id='horazonahorariaactual' name='horazonahorariaactual'>Hora Actual: " + lahora;
                }
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
    setInterval(obtenerhora, 1000);

});