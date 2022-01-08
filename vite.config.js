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
                'app.js': './app.js',
                'login.js': './login.js',
                'admin_panel.js': './admin_panel.js',
                'domain.js': './domain.js',
                'table_search.js': './table_search.js',
            }
        }
    }
})