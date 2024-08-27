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

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    $("#form-player-idle-timeout").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-max-build-height").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-entity-broadcast-range-percentage").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-simulation-distance").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-spawn-protection").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-max-world-size").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-max-players").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-query-port").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-rconport").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-max-chained-neighbor-updates").keypress(function (e) {
        if (e.keyCode < 48 || e.keyCode > 57) {
            return e.keyCode == 45;
        } else {
            return true;
        }
    });

    $("#form-max-tick-time").keypress(function (e) {
        if (e.keyCode < 48 || e.keyCode > 57) {
            return e.keyCode == 45;
        } else {
            return true;
        }
    });

    $("#form-op-permission-level").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-function-permission-level").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-rate-limit").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-network-compression-threshold").keypress(function (e) {
        if (e.keyCode < 48 || e.keyCode > 57) {
            return e.keyCode == 45;
        } else {
            return true;
        }
    });

    $("#form-view-distance").keypress(function (e) {
        return !(e.keyCode < 48 || e.keyCode > 57);
    });

    $("#form-level-name").keypress(function (e) {
        return !!(e.keyCode >= 48 && e.keyCode <= 57 || e.keyCode >= 65 && e.keyCode <= 90 || e.keyCode >= 97 && e.keyCode <= 122 || e.keyCode == 45 || e.keyCode == 95 || e.keyCode == 13);
    });

    if (document.getElementById("label-gamemode") !== null) {
        if (document.getElementById("form-gamemode") !== null) {
            document.getElementById("label-gamemode").innerHTML = htmlEntities("gamemode=" + document.getElementById("form-gamemode").value);
        }
    }

    if (document.getElementById("label-force-gamemode") !== null) {
        if (document.getElementById("form-force-gamemode") !== null) {
            document.getElementById("label-force-gamemode").innerHTML = htmlEntities("force-gamemode=" + document.getElementById("form-force-gamemode").value);
        }
    }

    if (document.getElementById("label-difficulty") !== null) {
        if (document.getElementById("form-difficulty") !== null) {
            document.getElementById("label-difficulty").innerHTML = htmlEntities("difficulty=" + document.getElementById("form-difficulty").value);
        }
    }

    if (document.getElementById("label-hardcore") !== null) {
        if (document.getElementById("form-hardcore") !== null) {
            document.getElementById("label-hardcore").innerHTML = htmlEntities("hardcore=" + document.getElementById("form-hardcore").value);
        }
    }

    if (document.getElementById("label-pvp") !== null) {
        if (document.getElementById("form-pvp") !== null) {
            document.getElementById("label-pvp").innerHTML = htmlEntities("pvp=" + document.getElementById("form-pvp").value);
        }
    }

    if (document.getElementById("label-spawn-npcs") !== null) {
        if (document.getElementById("form-spawn-npcs") !== null) {
            document.getElementById("label-spawn-npcs").innerHTML = htmlEntities("spawn-npcs=" + document.getElementById("form-spawn-npcs").value);
        }
    }

    if (document.getElementById("label-spawn-animals") !== null) {
        if (document.getElementById("form-spawn-animals") !== null) {
            document.getElementById("label-spawn-animals").innerHTML = htmlEntities("spawn-animals=" + document.getElementById("form-spawn-animals").value);
        }
    }

    if (document.getElementById("label-spawn-monsters") !== null) {
        if (document.getElementById("form-spawn-monsters") !== null) {
            document.getElementById("label-spawn-monsters").innerHTML = htmlEntities("spawn-monsters=" + document.getElementById("form-spawn-monsters").value);
        }
    }

    if (document.getElementById("label-allow-flight") !== null) {
        if (document.getElementById("form-allow-flight") !== null) {
            document.getElementById("label-allow-flight").innerHTML = htmlEntities("allow-flight=" + document.getElementById("form-allow-flight").value);
        }
    }

    if (document.getElementById("label-player-idle-timeout") !== null) {
        if (document.getElementById("form-player-idle-timeout") !== null) {
            document.getElementById("label-player-idle-timeout").innerHTML = htmlEntities("player-idle-timeout=" + document.getElementById("form-player-idle-timeout").value);
        }
    }

    if (document.getElementById("label-resource-pack") !== null) {
        if (document.getElementById("form-resource-pack") !== null) {
            document.getElementById("label-resource-pack").innerHTML = htmlEntities("resource-pack=" + document.getElementById("form-resource-pack").value);
        }
    }

    if (document.getElementById("label-resource-pack-sha1") !== null) {
        if (document.getElementById("form-resource-pack-sha1") !== null) {
            document.getElementById("label-resource-pack-sha1").innerHTML = htmlEntities("resource-pack-sha1=" + document.getElementById("form-resource-pack-sha1").value);
        }
    }

    if (document.getElementById("label-require-resource-pack") !== null) {
        if (document.getElementById("form-require-resource-pack") !== null) {
            document.getElementById("label-require-resource-pack").innerHTML = htmlEntities("require-resource-pack=" + document.getElementById("form-require-resource-pack").value);
        }
    }

    if (document.getElementById("label-resource-pack-prompt") !== null) {
        if (document.getElementById("form-resource-pack-prompt") !== null) {
            document.getElementById("label-resource-pack-prompt").innerHTML = htmlEntities("resource-pack-prompt=" + document.getElementById("form-resource-pack-prompt").value);
        }
    }

    if (document.getElementById("label-level-name") !== null) {
        if (document.getElementById("form-level-name") !== null) {
            document.getElementById("label-level-name").innerHTML = htmlEntities("level-name=" + document.getElementById("form-level-name").value);
        }
    }

    if (document.getElementById("label-level-seed") !== null) {
        if (document.getElementById("form-level-seed") !== null) {
            document.getElementById("label-level-seed").innerHTML = htmlEntities("level-seed=" + document.getElementById("form-level-seed").value);
        }
    }

    if (document.getElementById("label-level-type") !== null) {
        if (document.getElementById("form-level-type") !== null) {
            document.getElementById("label-level-type").innerHTML = htmlEntities("level-type=" + document.getElementById("form-level-type").value);
        }
    }

    if (document.getElementById("label-generator-settings") !== null) {
        if (document.getElementById("form-generator-settings") !== null) {
            document.getElementById("label-generator-settings").innerHTML = htmlEntities("generator-settings=" + document.getElementById("form-generator-settings").value);
        }
    }

    if (document.getElementById("label-region-file-compression") !== null) {
        if (document.getElementById("form-region-file-compression") !== null) {
            document.getElementById("label-region-file-compression").innerHTML = htmlEntities("region-file-compression=" + document.getElementById("form-region-file-compression").value);
        }
    }

    if (document.getElementById("label-max-build-height") !== null) {
        if (document.getElementById("form-max-build-height") !== null) {
            document.getElementById("label-max-build-height").innerHTML = htmlEntities("max-build-height=" + document.getElementById("form-max-build-height").value);
        }
    }

    if (document.getElementById("label-generate-structures") !== null) {
        if (document.getElementById("form-generate-structures") !== null) {
            document.getElementById("label-generate-structures").innerHTML = htmlEntities("generate-structures=" + document.getElementById("form-generate-structures").value);
        }
    }

    if (document.getElementById("label-allow-nether") !== null) {
        if (document.getElementById("form-allow-nether") !== null) {
            document.getElementById("label-allow-nether").innerHTML = htmlEntities("allow-nether=" + document.getElementById("form-allow-nether").value);
        }
    }

    if (document.getElementById("label-entity-broadcast-range-percentage") !== null) {
        if (document.getElementById("form-entity-broadcast-range-percentage") !== null) {
            document.getElementById("label-entity-broadcast-range-percentage").innerHTML = htmlEntities("entity-broadcast-range-percentage=" + document.getElementById("form-entity-broadcast-range-percentage").value);
        }
    }

    if (document.getElementById("label-simulation-distance") !== null) {
        if (document.getElementById("form-simulation-distance") !== null) {
            document.getElementById("label-simulation-distance").innerHTML = htmlEntities("simulation-distance=" + document.getElementById("form-simulation-distance").value);
        }
    }

    if (document.getElementById("label-spawn-protection") !== null) {
        if (document.getElementById("form-spawn-protection") !== null) {
            document.getElementById("label-spawn-protection").innerHTML = htmlEntities("spawn-protection=" + document.getElementById("form-spawn-protection").value);
        }
    }

    if (document.getElementById("label-max-world-size") !== null) {
        if (document.getElementById("form-max-world-size") !== null) {
            document.getElementById("label-max-world-size").innerHTML = htmlEntities("max-world-size=" + document.getElementById("form-max-world-size").value);
        }
    }

    if (document.getElementById("label-online-mode") !== null) {
        if (document.getElementById("form-online-mode") !== null) {
            document.getElementById("label-online-mode").innerHTML = htmlEntities("online-mode=" + document.getElementById("form-online-mode").value);
        }
    }

    if (document.getElementById("label-max-players") !== null) {
        if (document.getElementById("form-max-players") !== null) {
            document.getElementById("label-max-players").innerHTML = htmlEntities("max-players=" + document.getElementById("form-max-players").value);
        }
    }

    if (document.getElementById("label-hide-online-players") !== null) {
        if (document.getElementById("form-hide-online-players") !== null) {
            document.getElementById("label-hide-online-players").innerHTML = htmlEntities("hide-online-players=" + document.getElementById("form-hide-online-players").value);
        }
    }

    if (document.getElementById("label-enable-command-block") !== null) {
        if (document.getElementById("form-enable-command-block") !== null) {
            document.getElementById("label-enable-command-block").innerHTML = htmlEntities("enable-command-block=" + document.getElementById("form-enable-command-block").value);
        }
    }

    if (document.getElementById("label-enable-query") !== null) {
        if (document.getElementById("form-enable-query") !== null) {
            document.getElementById("label-enable-query").innerHTML = htmlEntities("enable-query=" + document.getElementById("form-enable-query").value);
        }
    }

    if (document.getElementById("label-query-port") !== null) {
        if (document.getElementById("form-query-port") !== null) {
            document.getElementById("label-query-port").innerHTML = htmlEntities("query.port=" + document.getElementById("form-query-port").value);
        }
    }

    if (document.getElementById("label-enable-rcon") !== null) {
        if (document.getElementById("form-enable-rcon") !== null) {
            document.getElementById("label-enable-rcon").innerHTML = htmlEntities("enable-rcon=" + document.getElementById("form-enable-rcon").value);
        }
    }

    if (document.getElementById("label-rconport") !== null) {
        if (document.getElementById("form-rconport") !== null) {
            document.getElementById("label-rconport").innerHTML = htmlEntities("rcon.port=" + document.getElementById("form-rconport").value);
        }
    }

    if (document.getElementById("label-rcon-password") !== null) {
        if (document.getElementById("form-rcon-password") !== null) {
            document.getElementById("label-rcon-password").innerHTML = htmlEntities("rcon.password=" + document.getElementById("form-rcon-password").value);
        }
    }

    if (document.getElementById("label-enforce-secure-profile") !== null) {
        if (document.getElementById("form-enforce-secure-profile") !== null) {
            document.getElementById("label-enforce-secure-profile").innerHTML = htmlEntities("enforce-secure-profile=" + document.getElementById("form-enforce-secure-profile").value);
        }
    }

    if (document.getElementById("label-white-list") !== null) {
        if (document.getElementById("form-white-list") !== null) {
            document.getElementById("label-white-list").innerHTML = htmlEntities("white-list=" + document.getElementById("form-white-list").value);
        }
    }

    if (document.getElementById("label-enforce-whitelist") !== null) {
        if (document.getElementById("form-enforce-whitelist") !== null) {
            document.getElementById("label-enforce-whitelist").innerHTML = htmlEntities("enforce-whitelist=" + document.getElementById("form-enforce-whitelist").value);
        }
    }

    if (document.getElementById("label-server-ip") !== null) {
        if (document.getElementById("form-server-ip") !== null) {
            document.getElementById("label-server-ip").innerHTML = htmlEntities("server-ip=" + document.getElementById("form-server-ip").value);
        }
    }

    if (document.getElementById("label-enable-status") !== null) {
        if (document.getElementById("form-enable-status") !== null) {
            document.getElementById("label-enable-status").innerHTML = htmlEntities("enable-status=" + document.getElementById("form-enable-status").value);
        }
    }

    if (document.getElementById("label-broadcast-console-to-ops") !== null) {
        if (document.getElementById("form-broadcast-console-to-ops") !== null) {
            document.getElementById("label-broadcast-console-to-ops").innerHTML = htmlEntities("broadcast-console-to-ops=" + document.getElementById("form-broadcast-console-to-ops").value);
        }
    }

    if (document.getElementById("label-broadcast-rcon-to-ops") !== null) {
        if (document.getElementById("form-broadcast-rcon-to-ops") !== null) {
            document.getElementById("label-broadcast-rcon-to-ops").innerHTML = htmlEntities("broadcast-rcon-to-ops=" + document.getElementById("form-broadcast-rcon-to-ops").value);
        }
    }

    if (document.getElementById("label-use-native-transport") !== null) {
        if (document.getElementById("form-use-native-transport") !== null) {
            document.getElementById("label-use-native-transport").innerHTML = htmlEntities("use-native-transport=" + document.getElementById("form-use-native-transport").value);
        }
    }

    if (document.getElementById("label-accepts-transfers") !== null) {
        if (document.getElementById("form-accepts-transfers") !== null) {
            document.getElementById("label-accepts-transfers").innerHTML = htmlEntities("accepts-transfers=" + document.getElementById("form-accepts-transfers").value);
        }
    }

    if (document.getElementById("label-previews-chat") !== null) {
        if (document.getElementById("form-previews-chat") !== null) {
            document.getElementById("label-previews-chat").innerHTML = htmlEntities("previews-chat=" + document.getElementById("form-previews-chat").value);
        }
    }

    if (document.getElementById("label-prevent-proxy-connections") !== null) {
        if (document.getElementById("form-prevent-proxy-connections") !== null) {
            document.getElementById("label-prevent-proxy-connections").innerHTML = htmlEntities("prevent-proxy-connections=" + document.getElementById("form-prevent-proxy-connections").value);
        }
    }

    if (document.getElementById("label-enable-jmx-monitoring") !== null) {
        if (document.getElementById("form-enable-jmx-monitoring") !== null) {
            document.getElementById("label-enable-jmx-monitoring").innerHTML = htmlEntities("enable-jmx-monitoring=" + document.getElementById("form-enable-jmx-monitoring").value);
        }
    }

    if (document.getElementById("label-snooper-enabled") !== null) {
        if (document.getElementById("form-snooper-enabled") !== null) {
            document.getElementById("label-snooper-enabled").innerHTML = htmlEntities("snooper-enabled=" + document.getElementById("form-snooper-enabled").value);
        }
    }

    if (document.getElementById("label-sync-chunk-writes") !== null) {
        if (document.getElementById("form-sync-chunk-writes") !== null) {
            document.getElementById("label-sync-chunk-writes").innerHTML = htmlEntities("sync-chunk-writes=" + document.getElementById("form-sync-chunk-writes").value);
        }
    }

    if (document.getElementById("label-max-chained-neighbor-updates") !== null) {
        if (document.getElementById("form-max-chained-neighbor-updates") !== null) {
            document.getElementById("label-max-chained-neighbor-updates").innerHTML = htmlEntities("max-chained-neighbor-updates=" + document.getElementById("form-max-chained-neighbor-updates").value);
        }
    }

    if (document.getElementById("label-max-tick-time") !== null) {
        if (document.getElementById("form-max-tick-time") !== null) {
            document.getElementById("label-max-tick-time").innerHTML = htmlEntities("max-tick-time=" + document.getElementById("form-max-tick-time").value);
        }
    }

    if (document.getElementById("label-op-permission-level") !== null) {
        if (document.getElementById("form-op-permission-level") !== null) {
            document.getElementById("label-op-permission-level").innerHTML = htmlEntities("op-permission-level=" + document.getElementById("form-op-permission-level").value);
        }
    }

    if (document.getElementById("label-function-permission-level") !== null) {
        if (document.getElementById("form-function-permission-level") !== null) {
            document.getElementById("label-function-permission-level").innerHTML = htmlEntities("function-permission-level=" + document.getElementById("form-function-permission-level").value);
        }
    }

    if (document.getElementById("label-rate-limit") !== null) {
        if (document.getElementById("form-rate-limit") !== null) {
            document.getElementById("label-rate-limit").innerHTML = htmlEntities("rate-limit=" + document.getElementById("form-rate-limit").value);
        }
    }

    if (document.getElementById("label-network-compression-threshold") !== null) {
        if (document.getElementById("form-network-compression-threshold") !== null) {
            document.getElementById("label-network-compression-threshold").innerHTML = htmlEntities("network-compression-threshold=" + document.getElementById("form-network-compression-threshold").value);
        }
    }

    if (document.getElementById("label-view-distance") !== null) {
        if (document.getElementById("form-view-distance") !== null) {
            document.getElementById("label-view-distance").innerHTML = htmlEntities("view-distance=" + document.getElementById("form-view-distance").value);
        }
    }

    if (document.getElementById("label-motd") !== null) {
        if (document.getElementById("form-motd") !== null) {
            document.getElementById("label-motd").innerHTML = htmlEntities("motd=" + document.getElementById("form-motd").value);
        }
    }

    if (document.getElementById("form-motd") !== null) {
        if (document.getElementById("visormotd") !== null) {
            updatemotd(document.getElementById("form-motd").value);
        }
    }

    $("#form-gamemode").change(function () {
        let envioaction = "gamemode";
        let enviovalor = document.getElementById("form-gamemode").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-gamemode") !== null) {
            document.getElementById("label-gamemode").innerHTML = htmlEntities("gamemode=" + document.getElementById("form-gamemode").value);
        }

    });

    $("#form-force-gamemode").change(function () {
        let envioaction = "force-gamemode";
        let enviovalor = document.getElementById("form-force-gamemode").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-force-gamemode") !== null) {
            document.getElementById("label-force-gamemode").innerHTML = htmlEntities("force-gamemode=" + document.getElementById("form-force-gamemode").value);
        }

    });

    $("#form-difficulty").change(function () {
        let envioaction = "difficulty";
        let enviovalor = document.getElementById("form-difficulty").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-difficulty") !== null) {
            document.getElementById("label-difficulty").innerHTML = htmlEntities("difficulty=" + document.getElementById("form-difficulty").value);
        }

    });

    $("#form-hardcore").change(function () {
        let envioaction = "hardcore";
        let enviovalor = document.getElementById("form-hardcore").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-hardcore") !== null) {
            document.getElementById("label-hardcore").innerHTML = htmlEntities("hardcore=" + document.getElementById("form-hardcore").value);
        }

    });

    $("#form-pvp").change(function () {
        let envioaction = "pvp";
        let enviovalor = document.getElementById("form-pvp").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-pvp") !== null) {
            document.getElementById("label-pvp").innerHTML = htmlEntities("pvp=" + document.getElementById("form-pvp").value);
        }

    });

    $("#form-spawn-npcs").change(function () {
        let envioaction = "spawn-npcs";
        let enviovalor = document.getElementById("form-spawn-npcs").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-spawn-npcs") !== null) {
            document.getElementById("label-spawn-npcs").innerHTML = htmlEntities("spawn-npcs=" + document.getElementById("form-spawn-npcs").value);
        }

    });

    $("#form-spawn-animals").change(function () {
        let envioaction = "spawn-animals";
        let enviovalor = document.getElementById("form-spawn-animals").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-spawn-animals") !== null) {
            document.getElementById("label-spawn-animals").innerHTML = htmlEntities("spawn-animals=" + document.getElementById("form-spawn-animals").value);
        }

    });

    $("#form-spawn-monsters").change(function () {
        let envioaction = "spawn-monsters";
        let enviovalor = document.getElementById("form-spawn-monsters").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-spawn-monsters") !== null) {
            document.getElementById("label-spawn-monsters").innerHTML = htmlEntities("spawn-monsters=" + document.getElementById("form-spawn-monsters").value);
        }

    });

    $("#form-allow-flight").change(function () {
        let envioaction = "allow-flight";
        let enviovalor = document.getElementById("form-allow-flight").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-allow-flight") !== null) {
            document.getElementById("label-allow-flight").innerHTML = htmlEntities("allow-flight=" + document.getElementById("form-allow-flight").value);
        }

    });

    $("#form-player-idle-timeout").change(function () {
        let errores = 0;
        let envioaction = "player-idle-timeout";
        let enviovalor = document.getElementById("form-player-idle-timeout").value;
        if (enviovalor > 2147483647) {
            errores = 1;
        }

        if (errores == 0) {
            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-player-idle-timeout") !== null) {
                document.getElementById("label-player-idle-timeout").innerHTML = htmlEntities("player-idle-timeout=" + document.getElementById("form-player-idle-timeout").value);
            }
        }
    });

    $("#form-resource-pack").keyup(function () {
        let envioaction = "resource-pack";
        let enviovalor = document.getElementById("form-resource-pack").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-resource-pack") !== null) {
            document.getElementById("label-resource-pack").innerHTML = htmlEntities("resource-pack=" + document.getElementById("form-resource-pack").value);
        }

    });

    if (document.getElementById("form-resource-pack") !== null) {
        document.getElementById("form-resource-pack").addEventListener('paste', function (event) {
            let envioaction = "resource-pack";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-resource-pack");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-resource-pack").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-resource-pack") !== null) {
                document.getElementById("label-resource-pack").innerHTML = "resource-pack=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-resource-pack-sha1").keyup(function () {
        let envioaction = "resource-pack-sha1";
        let enviovalor = document.getElementById("form-resource-pack-sha1").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-resource-pack-sha1") !== null) {
            document.getElementById("label-resource-pack-sha1").innerHTML = htmlEntities("resource-pack-sha1=" + document.getElementById("form-resource-pack-sha1").value);
        }

    });

    if (document.getElementById("form-resource-pack-sha1") !== null) {
        document.getElementById("form-resource-pack-sha1").addEventListener('paste', function (event) {
            let envioaction = "resource-pack-sha1";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-resource-pack-sha1");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-resource-pack-sha1").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-resource-pack-sha1") !== null) {
                document.getElementById("label-resource-pack-sha1").innerHTML = "resource-pack-sha1=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-require-resource-pack").change(function () {
        let envioaction = "require-resource-pack";
        let enviovalor = document.getElementById("form-require-resource-pack").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-require-resource-pack") !== null) {
            document.getElementById("label-require-resource-pack").innerHTML = htmlEntities("require-resource-pack=" + document.getElementById("form-require-resource-pack").value);
        }

    });

    $("#form-resource-pack-prompt").keyup(function () {
        let envioaction = "resource-pack-prompt";
        let enviovalor = document.getElementById("form-resource-pack-prompt").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-resource-pack-prompt") !== null) {
            document.getElementById("label-resource-pack-prompt").innerHTML = htmlEntities("resource-pack-prompt=" + document.getElementById("form-resource-pack-prompt").value);
        }

    });

    if (document.getElementById("form-resource-pack-prompt") !== null) {
        document.getElementById("form-resource-pack-prompt").addEventListener('paste', function (event) {
            let envioaction = "resource-pack-prompt";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-resource-pack-prompt");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-resource-pack-prompt").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-resource-pack-prompt") !== null) {
                document.getElementById("label-resource-pack-prompt").innerHTML = "resource-pack-prompt=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-level-name").keyup(function () {
        let envioaction = "level-name";
        let enviovalor = document.getElementById("form-level-name").value;
        enviovalor = enviovalor.toLowerCase();
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-level-name") !== null) {
            document.getElementById("label-level-name").innerHTML = htmlEntities("level-name=" + enviovalor);
        }

    });

    $("#form-level-seed").keyup(function () {
        let envioaction = "level-seed";
        let enviovalor = document.getElementById("form-level-seed").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-level-seed") !== null) {
            document.getElementById("label-level-seed").innerHTML = htmlEntities("level-seed=" + document.getElementById("form-level-seed").value);
        }

    });

    if (document.getElementById("form-level-seed") !== null) {
        document.getElementById("form-level-seed").addEventListener('paste', function (event) {
            let envioaction = "level-seed";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-level-seed");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-level-seed").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-level-seed") !== null) {
                document.getElementById("label-level-seed").innerHTML = "level-seed=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-level-type").change(function () {
        let envioaction = "level-type";
        let enviovalor = document.getElementById("form-level-type").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-level-type") !== null) {
            document.getElementById("label-level-type").innerHTML = htmlEntities("level-type=" + document.getElementById("form-level-type").value);
        }

    });

    $("#form-generator-settings").keyup(function () {
        let envioaction = "generator-settings";
        let enviovalor = document.getElementById("form-generator-settings").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-generator-settings") !== null) {
            document.getElementById("label-generator-settings").innerHTML = htmlEntities("generator-settings=" + document.getElementById("form-generator-settings").value);
        }

    });

    if (document.getElementById("form-generator-settings") !== null) {
        document.getElementById("form-generator-settings").addEventListener('paste', function (event) {
            let envioaction = "generator-settings";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-generator-settings");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-generator-settings").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-generator-settings") !== null) {
                document.getElementById("label-generator-settings").innerHTML = "generator-settings=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-region-file-compression").change(function () {
        let envioaction = "region-file-compression";
        let enviovalor = document.getElementById("form-region-file-compression").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-region-file-compression") !== null) {
            document.getElementById("label-region-file-compression").innerHTML = htmlEntities("region-file-compression=" + document.getElementById("form-region-file-compression").value);
        }

    });

    $("#form-max-build-height").change(function () {
        let errores = 0;
        let envioaction = "max-build-height";
        let enviovalor = document.getElementById("form-max-build-height").value;
        if (enviovalor > 256 || enviovalor < 8) {
            errores = 1;
        }

        if (errores == 0) {
            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-max-build-height") !== null) {
                document.getElementById("label-max-build-height").innerHTML = htmlEntities("max-build-height=" + document.getElementById("form-max-build-height").value);
            }
        }
    });

    $("#form-generate-structures").change(function () {
        let envioaction = "generate-structures";
        let enviovalor = document.getElementById("form-generate-structures").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-generate-structures") !== null) {
            document.getElementById("label-generate-structures").innerHTML = htmlEntities("generate-structures=" + document.getElementById("form-generate-structures").value);
        }

    });

    $("#form-allow-nether").change(function () {
        let envioaction = "allow-nether";
        let enviovalor = document.getElementById("form-allow-nether").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-allow-nether") !== null) {
            document.getElementById("label-allow-nether").innerHTML = htmlEntities("allow-nether=" + document.getElementById("form-allow-nether").value);
        }

    });

    $("#form-entity-broadcast-range-percentage").change(function () {
        let errores = 0;
        let envioaction = "entity-broadcast-range-percentage";
        let enviovalor = document.getElementById("form-entity-broadcast-range-percentage").value;

        if (enviovalor > 1000 || enviovalor < 10) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-entity-broadcast-range-percentage") !== null) {
                document.getElementById("label-entity-broadcast-range-percentage").innerHTML = htmlEntities("entity-broadcast-range-percentage=" + document.getElementById("form-entity-broadcast-range-percentage").value);
            }
        }
    });

    $("#form-simulation-distance").change(function () {
        let errores = 0;
        let envioaction = "simulation-distance";
        let enviovalor = document.getElementById("form-simulation-distance").value;

        if (enviovalor > 32 || enviovalor < 5) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-simulation-distance") !== null) {
                document.getElementById("label-simulation-distance").innerHTML = htmlEntities("simulation-distance=" + document.getElementById("form-simulation-distance").value);
            }
        }
    });

    $("#form-spawn-protection").change(function () {
        let errores = 0;
        let envioaction = "spawn-protection";
        let enviovalor = document.getElementById("form-spawn-protection").value;

        if (enviovalor > 16 || enviovalor < 0) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-spawn-protection") !== null) {
                document.getElementById("label-spawn-protection").innerHTML = htmlEntities("spawn-protection=" + document.getElementById("form-spawn-protection").value);
            }
        }
    });

    $("#form-max-world-size").change(function () {
        let errores = 0;
        let envioaction = "max-world-size";
        let enviovalor = document.getElementById("form-max-world-size").value;

        if (enviovalor > 29999984 || enviovalor < 1) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-max-world-size") !== null) {
                document.getElementById("label-max-world-size").innerHTML = htmlEntities("max-world-size=" + document.getElementById("form-max-world-size").value);
            }
        }
    });

    $("#form-online-mode").change(function () {
        let envioaction = "online-mode";
        let enviovalor = document.getElementById("form-online-mode").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-online-mode") !== null) {
            document.getElementById("label-online-mode").innerHTML = htmlEntities("online-mode=" + document.getElementById("form-online-mode").value);
        }

    });

    $("#form-max-players").change(function () {
        let errores = 0;
        let envioaction = "max-players";
        let enviovalor = document.getElementById("form-max-players").value;

        if (enviovalor > 2147483647 || enviovalor < 1) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-max-players") !== null) {
                document.getElementById("label-max-players").innerHTML = htmlEntities("max-players=" + document.getElementById("form-max-players").value);
            }
        }
    });

    $("#form-hide-online-players").change(function () {
        let envioaction = "hide-online-players";
        let enviovalor = document.getElementById("form-hide-online-players").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-hide-online-players") !== null) {
            document.getElementById("label-hide-online-players").innerHTML = htmlEntities("hide-online-players=" + document.getElementById("form-hide-online-players").value);
        }

    });

    $("#form-enable-command-block").change(function () {
        let envioaction = "enable-command-block";
        let enviovalor = document.getElementById("form-enable-command-block").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enable-command-block") !== null) {
            document.getElementById("label-enable-command-block").innerHTML = htmlEntities("enable-command-block=" + document.getElementById("form-enable-command-block").value);
        }

    });

    $("#form-enable-query").change(function () {
        let envioaction = "enable-query";
        let enviovalor = document.getElementById("form-enable-query").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enable-query") !== null) {
            document.getElementById("label-enable-query").innerHTML = htmlEntities("enable-query=" + document.getElementById("form-enable-query").value);
        }

    });

    $("#form-query-port").change(function () {
        let errores = 0;
        let envioaction = "query.port";
        let enviovalor = document.getElementById("form-query-port").value;

        if (enviovalor > 65535 || enviovalor < 1025) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-query-port") !== null) {
                document.getElementById("label-query-port").innerHTML = htmlEntities("query.port=" + document.getElementById("form-query-port").value);
            }
        }
    });

    $("#form-enable-rcon").change(function () {
        let envioaction = "enable-rcon";
        let enviovalor = document.getElementById("form-enable-rcon").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enable-rcon") !== null) {
            document.getElementById("label-enable-rcon").innerHTML = htmlEntities("enable-rcon=" + document.getElementById("form-enable-rcon").value);
        }

    });

    $("#form-rconport").change(function () {
        let errores = 0;
        let envioaction = "rcon.port";
        let enviovalor = document.getElementById("form-rconport").value;

        if (enviovalor > 65535 || enviovalor < 1025) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-rconport") !== null) {
                document.getElementById("label-rconport").innerHTML = htmlEntities("rcon.port=" + document.getElementById("form-rconport").value);
            }
        }
    });

    $("#form-rcon-password").keyup(function () {
        let envioaction = "rcon.password";
        let enviovalor = document.getElementById("form-rcon-password").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-rcon-password") !== null) {
            document.getElementById("label-rcon-password").innerHTML = htmlEntities("rcon.password=" + document.getElementById("form-rcon-password").value);
        }
    });

    if (document.getElementById("form-rcon-password") !== null) {
        document.getElementById("form-rcon-password").addEventListener('paste', function (event) {
            let envioaction = "rcon.password";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-rcon-password");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-rcon-password").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-rcon-password") !== null) {
                document.getElementById("label-rcon-password").innerHTML = "rcon.password=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-enforce-secure-profile").change(function () {
        let envioaction = "enforce-secure-profile";
        let enviovalor = document.getElementById("form-enforce-secure-profile").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enforce-secure-profile") !== null) {
            document.getElementById("label-enforce-secure-profile").innerHTML = htmlEntities("enforce-secure-profile=" + document.getElementById("form-enforce-secure-profile").value);
        }

    });

    $("#form-white-list").change(function () {
        let envioaction = "white-list";
        let enviovalor = document.getElementById("form-white-list").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-white-list") !== null) {
            document.getElementById("label-white-list").innerHTML = htmlEntities("white-list=" + document.getElementById("form-white-list").value);
        }

    });

    $("#form-enforce-whitelist").change(function () {
        let envioaction = "enforce-whitelist";
        let enviovalor = document.getElementById("form-enforce-whitelist").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enforce-whitelist") !== null) {
            document.getElementById("label-enforce-whitelist").innerHTML = htmlEntities("enforce-whitelist=" + document.getElementById("form-enforce-whitelist").value);
        }

    });

    $("#form-server-ip").keyup(function () {
        const regexipExp = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/gi;
        let envioaction = "server-ip";
        let enviovalor = document.getElementById("form-server-ip").value;

        const validarIP = regexipExp.test(enviovalor);

        if (validarIP == false) {
            enviovalor = "";
        }

        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-server-ip") !== null) {
            document.getElementById("label-server-ip").innerHTML = htmlEntities("server-ip=" + document.getElementById("form-server-ip").value);
        }

    });

    if (document.getElementById("form-server-ip") !== null) {
        document.getElementById("form-server-ip").addEventListener('paste', function (event) {
            const regexipExp = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/gi;
            let envioaction = "server-ip";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-server-ip");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-server-ip").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            const validarIP = regexipExp.test(enviovalor);

            if (validarIP == false) {
                enviovalor = "";
            }

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-server-ip") !== null) {
                document.getElementById("label-server-ip").innerHTML = "server-ip=" + htmlEntities(enviovalor);
            }

        });
    }

    $("#form-enable-status").change(function () {
        let envioaction = "enable-status";
        let enviovalor = document.getElementById("form-enable-status").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enable-status") !== null) {
            document.getElementById("label-enable-status").innerHTML = htmlEntities("enable-status=" + document.getElementById("form-enable-status").value);
        }

    });

    $("#form-broadcast-console-to-ops").change(function () {
        let envioaction = "broadcast-console-to-ops";
        let enviovalor = document.getElementById("form-broadcast-console-to-ops").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-broadcast-console-to-ops") !== null) {
            document.getElementById("label-broadcast-console-to-ops").innerHTML = htmlEntities("broadcast-console-to-ops=" + document.getElementById("form-broadcast-console-to-ops").value);
        }

    });

    $("#form-broadcast-rcon-to-ops").change(function () {
        let envioaction = "broadcast-rcon-to-ops";
        let enviovalor = document.getElementById("form-broadcast-rcon-to-ops").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-broadcast-rcon-to-ops") !== null) {
            document.getElementById("label-broadcast-rcon-to-ops").innerHTML = htmlEntities("broadcast-rcon-to-ops=" + document.getElementById("form-broadcast-rcon-to-ops").value);
        }

    });

    $("#form-use-native-transport").change(function () {
        let envioaction = "use-native-transport";
        let enviovalor = document.getElementById("form-use-native-transport").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-use-native-transport") !== null) {
            document.getElementById("label-use-native-transport").innerHTML = htmlEntities("use-native-transport=" + document.getElementById("form-use-native-transport").value);
        }

    });

    $("#form-accepts-transfers").change(function () {
        let envioaction = "accepts-transfers";
        let enviovalor = document.getElementById("form-accepts-transfers").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-accepts-transfers") !== null) {
            document.getElementById("label-accepts-transfers").innerHTML = htmlEntities("accepts-transfers=" + document.getElementById("form-accepts-transfers").value);
        }

    });

    $("#form-previews-chat").change(function () {
        let envioaction = "previews-chat";
        let enviovalor = document.getElementById("form-previews-chat").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-previews-chat") !== null) {
            document.getElementById("label-previews-chat").innerHTML = htmlEntities("previews-chat=" + document.getElementById("form-previews-chat").value);
        }

    });

    $("#form-prevent-proxy-connections").change(function () {
        let envioaction = "prevent-proxy-connections";
        let enviovalor = document.getElementById("form-prevent-proxy-connections").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-prevent-proxy-connections") !== null) {
            document.getElementById("label-prevent-proxy-connections").innerHTML = htmlEntities("prevent-proxy-connections=" + document.getElementById("form-prevent-proxy-connections").value);
        }

    });

    $("#form-enable-jmx-monitoring").change(function () {
        let envioaction = "enable-jmx-monitoring";
        let enviovalor = document.getElementById("form-enable-jmx-monitoring").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-enable-jmx-monitoring") !== null) {
            document.getElementById("label-enable-jmx-monitoring").innerHTML = htmlEntities("enable-jmx-monitoring=" + document.getElementById("form-enable-jmx-monitoring").value);
        }

    });

    $("#form-snooper-enabled").change(function () {
        let envioaction = "snooper-enabled";
        let enviovalor = document.getElementById("form-snooper-enabled").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-snooper-enabled") !== null) {
            document.getElementById("label-snooper-enabled").innerHTML = htmlEntities("snooper-enabled=" + document.getElementById("form-snooper-enabled").value);
        }

    });

    $("#form-sync-chunk-writes").change(function () {
        let envioaction = "sync-chunk-writes";
        let enviovalor = document.getElementById("form-sync-chunk-writes").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-sync-chunk-writes") !== null) {
            document.getElementById("label-sync-chunk-writes").innerHTML = htmlEntities("sync-chunk-writes=" + document.getElementById("form-sync-chunk-writes").value);
        }

    });

    $("#form-max-chained-neighbor-updates").change(function () {
        let errores = 0;
        let envioaction = "max-chained-neighbor-updates";
        let enviovalor = document.getElementById("form-max-chained-neighbor-updates").value;

        if (enviovalor > 2147483647 || enviovalor < -1) {
            document.getElementById("form-max-chained-neighbor-updates").value = 60000;
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-max-chained-neighbor-updates") !== null) {
                document.getElementById("label-max-chained-neighbor-updates").innerHTML = htmlEntities("max-chained-neighbor-updates=" + document.getElementById("form-max-chained-neighbor-updates").value);
            }
        }
    });



    $("#form-max-tick-time").change(function () {
        let errores = 0;
        let envioaction = "max-tick-time";
        let enviovalor = document.getElementById("form-max-tick-time").value;

        if (enviovalor > 300000 || enviovalor < -1) {
            document.getElementById("form-max-tick-time").value = 60000;
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-max-tick-time") !== null) {
                document.getElementById("label-max-tick-time").innerHTML = htmlEntities("max-tick-time=" + document.getElementById("form-max-tick-time").value);
            }
        }
    });

    $("#form-op-permission-level").change(function () {
        let errores = 0;
        let envioaction = "op-permission-level";
        let enviovalor = document.getElementById("form-op-permission-level").value;

        if (enviovalor > 4 || enviovalor < 1) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-op-permission-level") !== null) {
                document.getElementById("label-op-permission-level").innerHTML = htmlEntities("op-permission-level=" + document.getElementById("form-op-permission-level").value);
            }
        }
    });

    $("#form-function-permission-level").change(function () {
        let errores = 0;
        let envioaction = "function-permission-level";
        let enviovalor = document.getElementById("form-function-permission-level").value;

        if (enviovalor > 4 || enviovalor < 1) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-function-permission-level") !== null) {
                document.getElementById("label-function-permission-level").innerHTML = htmlEntities("function-permission-level=" + document.getElementById("form-function-permission-level").value);
            }
        }
    });

    $("#form-rate-limit").change(function () {
        let envioaction = "rate-limit";
        let enviovalor = document.getElementById("form-rate-limit").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-rate-limit") !== null) {
            document.getElementById("label-rate-limit").innerHTML = htmlEntities("rate-limit=" + document.getElementById("form-rate-limit").value);
        }

    });

    $("#form-network-compression-threshold").change(function () {
        let errores = 0;
        let envioaction = "network-compression-threshold";
        let enviovalor = document.getElementById("form-network-compression-threshold").value;

        if (enviovalor > 256 || enviovalor < -1) {
            document.getElementById("form-network-compression-threshold").value = 256;
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-network-compression-threshold") !== null) {
                document.getElementById("label-network-compression-threshold").innerHTML = htmlEntities("network-compression-threshold=" + document.getElementById("form-network-compression-threshold").value);
            }
        }
    });

    $("#form-view-distance").change(function () {
        let errores = 0;
        let envioaction = "view-distance";
        let enviovalor = document.getElementById("form-view-distance").value;

        if (enviovalor > 32 || enviovalor < 3) {
            errores = 1;
        }

        if (errores == 0) {

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-view-distance") !== null) {
                document.getElementById("label-view-distance").innerHTML = htmlEntities("view-distance=" + document.getElementById("form-view-distance").value);
            }
        }
    });

    function updatemotd(textomotd) {
        $.ajax({
            type: "POST",
            url: "function/visormotd.php",
            data: {
                action: textomotd
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }

                if (document.getElementById("visormotd") !== null) {
                    document.getElementById("visormotd").innerHTML = data;
                }
            }
        });
    }

    $("#form-motd").keyup(function () {
        let envioaction = "motd";
        let enviovalor = document.getElementById("form-motd").value;
        $.ajax({
            type: "POST",
            url: "function/guardarproperties.php",
            data: {
                action: envioaction,
                valor: enviovalor
            },
            success: function (data) {
                let getdebug = 0;
                if (getdebug == 1) {
                    alert(data);
                }
            }
        });

        if (document.getElementById("label-motd") !== null) {
            document.getElementById("label-motd").innerHTML = htmlEntities("motd=" + document.getElementById("form-motd").value);
        }

        if (document.getElementById("form-motd") !== null) {
            updatemotd(document.getElementById("form-motd").value);
        }

    });

    if (document.getElementById("form-motd") !== null) {
        document.getElementById("form-motd").addEventListener('paste', function (event) {
            let envioaction = "motd";

            let enviovalor = "";
            let eltext = "";
            let textini = "";
            let textfinal = "";
            let enviar = "";

            let text = document.getElementById("form-motd");

            let startPosition = text.selectionStart;
            let endPosition = text.selectionEnd;
            let longitud = text.leng;

            eltext = document.getElementById("form-motd").value;
            textini = eltext.substring(0, startPosition);
            textfinal = eltext.substring(endPosition, longitud);

            enviar = textini + event.clipboardData.getData('text') + textfinal;
            enviovalor = enviar;

            $.ajax({
                type: "POST",
                url: "function/guardarproperties.php",
                data: {
                    action: envioaction,
                    valor: enviovalor
                },
                success: function (data) {
                    let getdebug = 0;
                    if (getdebug == 1) {
                        alert(data);
                    }
                }
            });

            if (document.getElementById("label-motd") !== null) {
                document.getElementById("label-motd").innerHTML = "motd=" + htmlEntities(enviovalor);
            }

            if (document.getElementById("visormotd") !== null) {
                updatemotd(enviovalor);
            }

        });
    }

    if (document.getElementById("restablecer") !== null) {
        $("#restablecer").click(function () {
            let eleccion = confirm("¡Confirmacion de restablecer configuración por defecto!\n\n¡Esta acción creará un nuevo archivo de configuración server.properties, una vez realizado no se podrá cancelar!\n\n¿Seguro que quieres continuar?");
            if (eleccion) {

                $.ajax({
                    url: 'function/restablecerproperties.php',
                    data: {
                        action: 'status'
                    },
                    type: 'POST',
                    success: function (data) {
                        if (data == "OK") {
                            location.reload();
                        } else if (data == "nowriteconfig") {
                            alert("La carpeta config no tiene permisos de escritura");
                        } else if (data == "nowriteproperties") {
                            alert("El archivo server.properties no tiene permisos de escritura");
                        }
                    }
                });
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