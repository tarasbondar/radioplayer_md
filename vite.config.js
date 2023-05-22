import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/admin/app.js',
            ],
            refresh: true,
        }),
    ],

    css: {
        devSourcemap: true
    },

    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },

    //test server
    /*server: {
        hmr: { host: '95.217.159.232' },
        host: "95.217.159.232",
    },*/

});
