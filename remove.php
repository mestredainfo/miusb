<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

header("Content-Security-Policy: default-src 'self'");
header("Content-Security-Policy: script-src 'self' 'unsafe-inline' script.js");

if (miRequestPost()) {
    function cmd($cmd, $tipo = 'array')
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );

        flush();

        $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());

        if ($tipo == 'array') {
            $txt = [];
        } else {
            $txt = false;
        }

        if (is_resource($process)) {
            while ($s = fgets($pipes[1])) {
                if (!empty($s)) {
                    if ($tipo == 'array') {
                        $txt[] = $s;
                    } else {
                        $txt = true;
                    }
                }
                flush();
            }
        }

        proc_close($process);

        return $txt;
    }

    $sDriver = miCleanPost('driver');
    if (!empty($sDriver)) {
        //echo '<div class="alert alert-info">Obtendo dispositivo...</div>';
        // Obtém o dispositivo
        $sDisp1 = cmd('lsblk -l');
        $sDisp2 = preg_grep('/\/media\/' . miUsername(). '\/' . rtrim($sDriver) . '/', $sDisp1);
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

                echo '<div class="alert alert-success">' . miTranslate('Dispositivo removido com segurança!') . '</div>';
            } else {
                echo '<div class="alert alert-danger">' . miTranslate('Dispositivo em uso, não foi possível remover o dispositivo!') . '</div>';
            }
        }
    }
}
