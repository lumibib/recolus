import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build:{
        outDir: 'public',
        assetsDir: '',
        emptyOutDir: false,
        rollupOptions: {
            input: {
                script: 'resources/scripts/script.js',
            },
            output: {
                entryFileNames : '[name].js',
                sourcemap: true,
            }
        }
    },
});
