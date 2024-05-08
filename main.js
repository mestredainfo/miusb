// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

const { app, BrowserWindow } = require('electron')

const path = require('path');

// desativa a aceleração de hardware
app.disableHardwareAcceleration();

const createWindow = () => {
    const win = new BrowserWindow({
        width: 800,
        height: 600,
        resizable: false,
        icon: path.join(app.getAppPath(), '/icon/miusb.png'),
        webPreferences: {
            preload: path.join(app.getAppPath(), 'preload.js'),
        }
    });

    win.setMenu(null);

    //win.webContents.openDevTools();

    win.loadFile(path.join(app.getAppPath(), '/app/index.html'));

    win.webContents.setWindowOpenHandler(({ url }) => {
        if (url !== '') {
            return {
                action: 'allow',
                overrideBrowserWindowOptions: {
                    width: 800,
                    height: 600,
                    resizable: false,
                    icon: path.join(app.getAppPath(), '/icon/miusb.png'),
                    webPreferences: {
                        preload: path.join(app.getAppPath(), 'preload.js')
                    }
                }
            }
        }

        return { action: 'deny' }
    });

    app.on("browser-window-created", (e, win) => {
        sInit = true;
        win.removeMenu();
    });
    
    const mifunctions = require(path.join(app.getAppPath(), 'mifunctions.js'));
    mifunctions.mifunctions(win);
}

app.whenReady().then(() => {
    createWindow()

    // Enquanto os aplicativos do Linux e do Windows são encerrados quando não há janelas abertas, os aplicativos do macOS geralmente continuam em execução mesmo sem nenhuma janela aberta, e ativar o aplicativo quando não há janelas disponíveis deve abrir um novo.
    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) createWindow()
    })
})

// Para sair do aplicativo no Windows e Linux
// Se for MACOS não roda esse comando
app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') {
        app.quit();
    }
})
