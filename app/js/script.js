// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

const txtDriver = document.getElementById('txtDriver');
const sResult = document.getElementById('resultado');

// Obter lista de drivers
async function getDriver() {
    txtDriver.innerHTML = '';
    window.drivers.getAll();
    window.drivers.getDados((jsonData) => {
        var sDriver = jsonData.split("\n")
        sDriver.forEach((row) => {
            if (row != '') {
                var opt = document.createElement('option');
                opt.value = row;
                opt.innerHTML = row;
                txtDriver.appendChild(opt);
            }
        });
    });
}

getDriver();

// Remover dispositivo USB
async function removeUSB1() {
    var sResult = document.getElementById('resultado');

    sResult.innerHTML = '<div class="alert alert-info">Removendo dispositivo com segurança...</div>';

    await window.driver.remove(txtDriver.value);
  
    window.driver.getDados((jsonData) => {
        if (jsonData != '') {
            sResult.innerHTML = '' + jsonData + '</div>';
        }
    });

    getDriver();
}

function abrirExterno(url) {
    window.externo.rodar(url);
}

function novaVersao() {
    window.atualizacao.verificar();
}