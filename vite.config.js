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
    build: {
        emptyOutDir: true,
        outDir: './webroot',
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
