import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css','resources/css/main.css', 'resources/js/app.js', 'resources/js/transaction.js','resources/js/traffic.js','resources/js/income.js', 'resources/js/epayment.js', 'resources/js/testasync.js', 'resources/js/dashboard.js'],
            refresh: true,
        }),
    ],
});
