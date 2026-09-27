import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        vue(), // Pastikan plugin vue diikutsertakan di sini
        tailwindcss(),
    ],
    server: {
        port: 5173,
        strictPort: true, // Berhenti jika port 5173 terpakai agar tidak otomatis pindah ke 5174
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});