<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\app\atualizacao;
use MISistema\app\config;
use MISistema\sistema\ambiente;

$env = new ambiente();
?>
<!DOCTYPE html>
<html lang="<?php echo $env->idioma(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Atualização</title>
</head>

<body>
    <?php
    $config = new config();
    $update = new atualizacao();
    
    $update->nome('MIUSB')
        ->versao($config->obter('sistema', 'versao'))
        ->url('https://www.mestredainfo.com.br/2024/07/miusb.html')
        ->exibir()
        ->verificar();
    ?>
</body>

</html>