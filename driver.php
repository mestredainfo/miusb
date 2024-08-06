<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

use MISistema\idioma\traduzir;
use MISistema\seguranca\post;
use MISistema\sistema\ambiente;
use MISistema\sistema\exec;

$post = new post();
$env = new ambiente();
$traduzir = new traduzir();

if ($post->solicitado()) {
    $exec = new exec();
    $exec->comando('ls /media/' . $env->usuario() . '/')
        ->consultar();

    while ($s = $exec->valores()) {
        if (!empty($s)) {
            $txtDriver .= '<option value="' . $s . '">' . $s . '</option>';
        }

        $exec->limpar();
    }

    $exec->fechar();

    echo $txtDriver;
}
