// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

var sHTML = document.body;
sHTML.innerHTML = `
<a href="index.html?p=sobre" target="_blank" rel="noopener" class="btn btn-primary">Sobre o MiHash</a> <a href="javascript:novaVersao();" class="btn btn-primary">Verificar Atualização</a>
<hr>
<div class="form-control">
    <label for="txtDriver">Selecione o dispositivo que deseja remover com segurança</label>
    <select id="txtDriver"></select>
</div>

<button type="button" class="btn btn-primary" onclick="removeUSB1()">Remover com segurança</button>
&nbsp;&nbsp;
<button type="button" class="btn btn-primary" onclick="getDriver()">Atualizar Lista</button>
<div id="resultado"></div>
<hr>
<div style="text-align:center;margin-top:17px">
<strong>Veja como você pode apoiar este software, <a href="javascript:window.externo.rodar('https://mestredainfo.wordpress.com/apoie/');">clique aqui</a>
</div>`;

var scriptJS = document.createElement('script');
scriptJS.src = 'js/script.js';
document.body.appendChild(scriptJS);