<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

header("Content-Security-Policy: default-src 'self'");
header("Content-Security-Policy: script-src 'self' 'unsafe-inline' script.js");

include_once(dirname(__FILE__) . '/checkupdate.php');
checkupdate();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIUSB</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="form-control">
        <label for="txtDriver">Selecione o dispositivo que deseja remover com segurança</label>
        <select id="txtDriver"></select>
    </div>

    <button type="button" class="btn btn-primary" onclick="removeUSB(event)">Remover com segurança</button>
    <button type="button" class="btn btn-primary" onclick="getDriver()">Atualizar Lista</button>

    <div id="resultado"></div>

    <div style="text-align:center;margin-top:17px">
        <strong>
            Veja como você pode apoiar este software, <a href="javascript:window.externo.rodar('https://mestredainfo.wordpress.com/apoie/');">clique aqui</a>
        </strong>
    </div>

    <script src="/js/script.js"></script>
    <script>
        async function getDriver() {
            await post('driver.php', '', function(response) {
                document.getElementById('txtDriver').innerHTML = response;
            });
        }

        getDriver();

        async function removeUSB(e) {
            let formData = new FormData();
            formData.append("driver", document.getElementById('txtDriver').value);

            document.getElementById('resultado').innerHTML = '<div class="alert alert-info">Removendo o dispositivo...</div>';

            await post('remove.php', formData, function(response) {
                document.getElementById('resultado').innerHTML = response;
            });

            getDriver();

            e.preventDefault();
        }
    </script>
</body>

</html>