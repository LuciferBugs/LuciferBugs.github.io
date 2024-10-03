// https://nuxt.com/docs/api/configuration/nuxt-config
import { nodePolyfills } from "@bangjelkoski/vite-plugin-node-polyfills";
import tsconfigPaths from 'vite-tsconfig-paths'
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: false },
  app: {
    head: {
      link: [
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap' },
      ],
      script: [
        { src: 'https://telegram.org/js/telegram-web-app.js' }
      ],
    }
  },
  css: ['~/assets/css/tailwind.css'],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  ssr: false, // whether to pre-render your application
  modules: [ // nuxtjs modules
    "@pinia/nuxt",
    "@vueuse/nuxt",
  ],

  plugins: ['~/plugins/ton',{ // import the buffer plugin we've made
    src: "./plugins/buffer.client.ts",
    ssr: false
  }],
  sourcemap: {
    server: false,
    client: true,
  },
  vite: {
    plugins: [ // setting up node + crypto polyfils + vite TS path resolution
      tsconfigPaths(),
      nodePolyfills({ protocolImports: false })
    ],

    build: {
      sourcemap: false, // we don't generate

      // default rollup options
      rollupOptions: {
        cache: false,
        output: {
          manualChunks: (id: string) => {
            //
          },
        },
      },
    },

    // needed for some Vite related issue for the
    // @bangjelkoski/vite-plugin-node-polyfills plugin
    optimizeDeps: {
      exclude: ["fsevents"],
    },
  },
})
