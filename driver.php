<?php
// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

header("Content-Security-Policy: default-src 'self'");
header("Content-Security-Policy: script-src 'self' 'unsafe-inline' script.js");

$sServer = filter_var($_SERVER['REQUEST_METHOD'], FILTER_DEFAULT, FILTER_NULL_ON_FAILURE);
if ($sServer == 'POST') {
    $cmd = 'ls /media/' . get_current_user() . '/';

    $descriptorspec = array(
        0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
        2 => array("pipe", "w")    // stderr is a pipe that the child will write to
    );

    flush();

    $process = proc_open($cmd, $descriptorspec, $pipes, realpath('./'), array());

    $txtDriver = '';
    if (is_resource($process)) {
        while ($s = fgets($pipes[1])) {
            if (!empty($s)) {
                $txtDriver .= '<option value="' . $s . '">' . $s . '</option>';
            }
            flush();
        }
    }

    proc_close($process);

    echo $txtDriver;
}
