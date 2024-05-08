// Copyright (C) 2004-2024 Murilo Gomes Julio
// SPDX-License-Identifier: GPL-2.0-only

// Organização: Mestre da Info
// Site: https://linktr.ee/mestreinfo

const { ipcMain, dialog } = require('electron')

module.exports = {
    mifunctions: function (win) {
        function trim(str, chr) {
            var rgxtrim = (!chr) ? new RegExp('^\\s+|\\s+$', 'g') : new RegExp('^' + chr + '+|' + chr + '+$', 'g');
            return str.replace(rgxtrim, '');
        }

        function rtrim(str, chr) {
            var rgxtrim = (!chr) ? new RegExp('\\s+$') : new RegExp(chr + '+$');
            return str.replace(rgxtrim, '');
        }

        function sleep(ms) {
            // add ms millisecond timeout before promise resolution
            return new Promise(resolve => setTimeout(resolve, ms))
        }

        // Abrir aplicativo externo
        ipcMain.handle('appExterno', async (event, url) => {
            require('electron').shell.openExternal(url);
        });

        // Obter dispositivos USB
        ipcMain.handle('getDrivers', async (event) => {
            var os = require('os');
            var sCurrentUser = os.userInfo().username;

            var childProcess = require('child_process');
            const child = childProcess.exec('ls /media/' + sCurrentUser + '/');

            child.stdout.on('data', (d) => {
                win.webContents.send('config:data', d);
            });

            child.stdout.on('close', () => {
                child.unref();
                child.kill();
            });
        });

        // Remove dispositivo USB
        ipcMain.handle('removeUSB', async (event, c) => {
            try {
                var os = require('os');
                var sCurrentUser = os.userInfo().username;

                var childProcess = require('child_process');
                const child1 = childProcess.execSync('lsblk -l').toString();

                var reg = new RegExp('\.*/media\/' + sCurrentUser + '\/' + rtrim(c), 'gi');
                var sDisp1 = reg.exec(child1);
                var sDisp2 = sDisp1[0].split(' ');
                var sUSB = trim(sDisp2[0]);

                await sleep(1000);

                const child2 = childProcess.execSync('udisksctl unmount -b /dev/' + sUSB);
                await sleep(1000);

                const child3 = childProcess.execSync('udisksctl power-off -b /dev/' + sUSB);
                await sleep(1000);

                setTimeout(() => {
                    win.webContents.send('driver:msg', '<div class="alert alert-success">Dispositivo removido com segurança!</div>');
                }, 1000)
            } catch (e) {
                setTimeout(() => {
                    win.webContents.send('driver:msg', '<div class="alert alert-danger">Dispositivo em uso, não foi possível remover o dispositivo!</div>');
                }, 1000)

            }
        });

        // Verifica nova versão para atualização
        ipcMain.handle('verificaAtualizacao', async () => {
            checkUpdate(true);
        });

        // Função para verificar novas atualizações do software
        async function checkUpdate(a) {
            try {
                const axios = require('axios');
                const cheerio = require('cheerio');

                // URL da página HTML que você deseja analisar
                const url = 'https://mestredainfo.wordpress.com/miusb/';

                const versaoatual = require('electron').app.getVersion();

                // Realiza a requisição HTTP
                const response = await axios.get(url);

                // Carrega o HTML retornado usando a biblioteca cheerio
                const $ = cheerio.load(response.data);

                // Extrai os dados desejados
                const versaonova = $('#appversion').text();

                if (versaonova > versaoatual) {
                    const options = {
                        type: 'question',
                        buttons: ['Mais tarde', 'Atualizar Agora'],
                        title: 'Deseja baixar a nova versão?',
                        message: 'A versão ' + versaonova + ' já está disponível.'
                    };

                    require('electron').dialog.showMessageBox(null, options).then(retorno => {
                        if (retorno.response === 1) {
                            require('electron').shell.openExternal(url);
                        }
                    });
                } else {
                    if (a) {
                        const options = {
                            type: "info",
                            buttons: ['Continuar'],
                            title: 'Verificação de Atualização',
                            message: 'O software já está na versão mais recente.'
                        };

                        require('electron').dialog.showMessageBox(null, options);
                    }
                }
            } catch (error) {
                console.error('Erro ao buscar os dados:', error);
            }
        }

        checkUpdate(false);
    }
}