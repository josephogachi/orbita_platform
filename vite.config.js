import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // 🟢 Allows access from external devices like your phone
        hmr: {
            host: 'localhost',
        },
        // If you still face issues with the popup, adding this allows the ngrok domain
        allowedHosts: ['.ngrok-free.app'] 
    },
});