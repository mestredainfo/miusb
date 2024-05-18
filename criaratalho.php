<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

$sFolder = '/home/' . get_current_user() . '/.local/share/applications/';
if (file_exists($sFolder)) {
    $sDesktop = "[Desktop Entry]\n
Version=" . file_get_contents(dirname(__FILE__) . '/version') . "\n
Name=MIUSB\n
Comment=Remove dispositivos USB com segurança\n
Type=Application\n
Exec=" . dirname(__FILE__, 4) . "/miusb\n
Icon=" . dirname(__FILE__, 2) . "/icon/miusb.png\n
Categories=Utility;";

    $sCreateFile = file_put_contents($sFolder . 'miusb.desktop', $sDesktop);
    if ($sCreateFile) {
        echo '<script>window.alert(\'Atalho criado no menu iniciar!\');window.location.assign(\'index.php\');</script>';
    } else {
        echo '<script>window.alert(\'Não foi possível criar o atalho no menu iniciar!\');window.location.assign(\'index.php\');</script>';
    }
} else {
    echo '<script>window.alert(\'Não foi possível criar o atalho no menu iniciar!\');window.location.assign(\'index.php\');</script>';
}
