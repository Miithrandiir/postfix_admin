import {defineConfig} from 'vite'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [],
    root: './assets',
    base: '/build/',
    server: {
        hmr: {
            protocol: 'ws'
        }
    },
    build: {
        manifest: true,
        assetsDir: '',
        outDir: '../public/build/',
        rollupOptions: {
            output: {
                manualChunks: undefined // On ne veut pas créer un fichier vendors, car on n'a ici qu'un point d'entré
            },
            input: {
                'app': './app.js',
                'login': './login.js',
                'admin_panel': './admin_panel.js',
                'domain': './domain.js',
                'table_search.ts': './table_search.ts',
            }
        }
    }
})