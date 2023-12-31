import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy';
import vuePlugin from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        legacy({
            targets: ['defaults', 'not IE 11'],
        }),
        vuePlugin({
            template: {
                compilerOptions: {},
            }
        }),
    ],
    base: 'file_pool',
    build: {
        emptyOutDir: true,
        outDir: './webroot',
        assetsDir: 'assets',
        manifest: true,
        rollupOptions: {
            input: {
                main: './webroot_src/main.ts',
            },
        },
    },
    server: {
        port: 3001,
        strictPort: true,
        hmr: {
            host: 'localhost',
            protocol: 'ws',
        },
    },
});
