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
    function cmd(string $cmd, string $tipo = 'array'): array|bool
    {
        $exec = new exec();
        $exec->comando($cmd)->consultar();

        if ($tipo == 'array') {
            $txt = [];
        } else {
            $txt = false;
        }

        while ($s = $exec->valores()) {
            if (!empty($s)) {
                if ($tipo == 'array') {
                    $txt[] = $s;
                } else {
                    $txt = true;
                }
            }
            $exec->limpar();
        }

        $exec->fechar();

        return $txt;
    }

    $sDriver = $post->obter('driver');
    if (!empty($sDriver)) {
        //echo '<div class="alert alert-info">Obtendo dispositivo...</div>';
        // Obtém o dispositivo
        $sDisp1 = cmd('lsblk -l');
        $sDisp2 = preg_grep('/\/media\/' . $env->usuario() . '\/' . rtrim($sDriver) . '/', $sDisp1);
        $sDisp3 = array_values($sDisp2);
        $sDisp4 = array_filter($sDisp3);
        $sDisp5 = explode(' ', $sDisp4[0]);

        if (!empty($sDisp5[0])) {
            $sUSB = str_replace(array(' ', "\n"), '', $sDisp5[0]);

            sleep(1);
            flush();

            // Remove com segurança
            $sRemovido = cmd('udisksctl unmount -b /dev/' . $sUSB, 'bool');

            sleep(1);
            flush();

            if ($sRemovido) {
                // Desliga com segurança
                $sDesliga = cmd('udisksctl power-off -b /dev/' . $sUSB);

                sleep(1);
                flush();

                echo '<div class="alert alert-success">' . $traduzir->obter('Dispositivo removido com segurança!') . '</div>';
            } else {
                echo '<div class="alert alert-danger">' . $traduzir->obter('Dispositivo em uso, não foi possível remover o dispositivo!') . '</div>';
            }
        }
    } else {
        echo '<div class="alert alert-success">' . $traduzir->obter('Selecione um dispositivo para removê-lo!') . '</div>';
    }
}
