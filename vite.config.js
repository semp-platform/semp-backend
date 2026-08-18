import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
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

        cors: {
            origin: [
                'http://127.0.0.1:8000',
                'http://localhost:8000',
                'http://10.105.165.152:8000',
            ],
        },

        hmr: {
    host: 'localhost',
    port: 5173,
},
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
