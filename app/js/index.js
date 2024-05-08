// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

document.addEventListener('DOMContentLoaded', function () {
    // Obtém a string da querystring da URL atual
    const sRouteQS = window.location.search;

    // Cria um objeto URLSearchParams a partir da string da querystring
    const sRouteParams = new URLSearchParams(sRouteQS);

    // Obtém o valor do parâmetro "id"
    const sRoute = sRouteParams.get('p');

    // Cria uma tag <script> para incluir script2.js
    function setRouteJS(filepath) {
        var script = document.createElement('script');
        script.src = 'js/' + filepath + '.js';
        document.body.appendChild(script);
    }

    if (sRoute == null) {
        setRouteJS('home');
    } else if (sRoute == 'sobre') {
        setRouteJS('sobre');
    }
});