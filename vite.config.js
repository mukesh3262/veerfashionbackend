import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';
import { getLocalIPAddress } from './resources/js/utils/network';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': path.resolve('./resources/js'),
            '@image': path.resolve('./resources/images'),
            '@helpers': path.resolve('./resources/js/helpers'),
        },
    },
    server: {
        host: true, // Allows access from local network
        port: 5173, // Ensure this matches your preferred Vite port
        strictPort: true,
        hmr: {
            host: getLocalIPAddress(), // Your local IP address
        },
    },
});
