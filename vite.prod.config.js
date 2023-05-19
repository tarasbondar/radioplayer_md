import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'public/scss/main.scss',
                'resources/js/app.js',
                'public/js/main.js',
                /*'public/dist/public/!*',*/
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        }
    },

    server: {
        hot: true,
        https: true,
        //port: 8000
        origin: 'radioplayer.d.meta-sistem.md:8000',
    },

    css: {
        devSourcemap: true
    },

    build: {
        // target: 'safari12',
        outDir: 'public/dist',
        emptyOutDir: true,

        rollupOptions: {
            /*input: {
                index: 'public/js/main.js',
            },*/

            output: {
                entryFileNames: 'public/[name].js',
                chunkFileNames: 'public/main.js',
                assetFileNames: 'public/main.[ext]'
            }

        },
    },
});
