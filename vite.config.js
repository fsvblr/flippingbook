import { defineConfig } from 'vite'
import vue from "@vitejs/plugin-vue"
import vuetify from 'vite-plugin-vuetify'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        vue(),
        vuetify(),
        laravel({
            input: [
                'resources/css/flippingbook.css',
                'resources/css/trix.css',
                'resources/js/flippingbook.js',
                'resources/js/flippingbook-admin.js',
                'resources/js/trix.umd.min.js',
            ],
            refresh: true,
        }),
    ],
});
