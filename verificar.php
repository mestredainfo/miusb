<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\idioma\traduzir;
use MISistema\sistema\ambiente;

$env = new ambiente();
$traduzir = new traduzir();
?>
<!DOCTYPE html>
<html lang="<?php echo $env->idioma(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIUSB</title>
    <link rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="mb-3">
        <label for="txtDriver"><?php echo $traduzir->obter('Selecione o dispositivo que deseja remover com segurança'); ?></label>
        <select id="txtDriver" class="form-control"></select>
    </div>

    <button type="button" class="btn btn-success" onclick="removeUSB(event)"><?php echo $traduzir->obter('Remover com segurança'); ?></button>
    <button type="button" class="btn btn-primary" onclick="getDriver()"><?php echo $traduzir->obter('Atualizar Lista'); ?></button>

    <div id="resultado"></div>

    <div style="text-align:center;margin-top:17px">
        <strong>
        <?php echo $traduzir->obter('Veja como você pode apoiar este software,'); ?> <a href="javascript:misistema.abrirURL('https://www.mestredainfo.com.br/2024/07/miusb.html');"><?php echo $traduzir->obter('clique aqui'); ?></a>
        </strong>
    </div>

    <script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
    <script>
        async function getDriver() {
            await post('/driver.php', '', function(response) {
                document.getElementById('txtDriver').innerHTML = response;
            });
        }

        getDriver();

        async function removeUSB(e) {
            let formData = new FormData();
            formData.append("driver", document.getElementById('txtDriver').value);

            misistema.traduzir('Removendo o dispositivo...').then((result) => {
                document.getElementById('resultado').innerHTML = '<div class="alert alert-info">' + result + '</div>';
            })

            await post('/remove.php', formData, function(response) {
                document.getElementById('resultado').innerHTML = response;
            });

            getDriver();

            e.preventDefault();
        }
    </script>
</body>

</html>