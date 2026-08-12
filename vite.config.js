import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import fs from 'node:fs';

const certificateKeyPath = './docker/certificates/local-key.pem';
const certificateCertPath = './docker/certificates/local-cert.pem';
const hasLocalCertificates = fs.existsSync(certificateKeyPath) && fs.existsSync(certificateCertPath);

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/project99.css', 'resources/js/project99.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        ...(hasLocalCertificates ? {
            https: {
                key: fs.readFileSync(certificateKeyPath),
                cert: fs.readFileSync(certificateCertPath),
            },
            hmr: {
                host: 'project99.local',
            },
        } : {}),
    },
});
