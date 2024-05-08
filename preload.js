// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

const { contextBridge, ipcRenderer } = require('electron')

ipcRenderer.setMaxListeners(20);

// Abrir aplicativo externo
contextBridge.exposeInMainWorld('externo', {
    rodar: (url) => ipcRenderer.invoke('appExterno', url)
});

// Obter lista de drivers
let listenerAdded = false;
contextBridge.exposeInMainWorld('drivers', {
    getAll: () => ipcRenderer.invoke('getDrivers'),
    getDados: (listener) => {
        const eventHandler = (event, ...args) => listener(...args);

        if (!listenerAdded) {
            ipcRenderer.on('config:data', eventHandler);
            listenerAdded = true;
        }

        const removerOuvinte = () => {
            ipcRenderer.removeListener('config:data', eventHandler);
            listenerAdded = false;
        };

        return removerOuvinte;
    },
});

// Remover driver
contextBridge.exposeInMainWorld('driver', {
    remove: (c) => ipcRenderer.invoke('removeUSB', c),
    getDados: (listener) => ipcRenderer.on('driver:msg', (event, ...args) => {
        return listener(...args);
    })
});

// Verifica nova versão para atualização
contextBridge.exposeInMainWorld('atualizacao', {
    verificar: () => ipcRenderer.invoke('verificaAtualizacao')
});
