import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig(
    ({command, mode, ssrBuild}) => {

        return {
            root: __dirname,

            define: { 'process.env.NODE_ENV': '"' + mode + '"' },

            plugins: [
                vue()
            ],

            build: {
                outDir: 'public/dist',

                lib: {
                    name: 'sieve',
                    formats: ['iife'],
                    entry: 'resources/js/main.js',
                    fileName: 'sieve'
                }
            }
        }
    }
);
