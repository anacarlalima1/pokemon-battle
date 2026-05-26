export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',

  css: ['~/assets/css/main.css'],

    app: {
    head: {
      title: 'Pokémon Battle',
      meta: [
        {
          name: 'description',
          content: 'Simulador de batalha Pokémon por HP usando PokéAPI.',
        },
      ],
    },
  },

  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL || 'http://localhost:8000/api',
    },
  },
})