// https://nuxt.com/docs/api/configuration/nuxt-config

export default defineNuxtConfig({
  runtimeConfig: {
    // https://titouan.dev/notes/2023/03/10/basic-auth-middleware-nuxt-3
    public: {
      ...process.env,
    },
  },

  devtools: { enabled: true },
  nitro: {
    preset: "vercel",
  },
  modules: [
    [
      "@pinia/nuxt",
      {
        autoImports: ["defineStore", "acceptHMRUpdate"],
      },
      // "nuxt-graphql-client",
    ],
  ],
  build: {
    transpile: [
      "@apollo/client",
      // 'ts-invariant/process',
    ],
  },
  // components: [
  //   {
  //     path: "~/src/components",
  //   },
  // ],
  dir: {
    plugins: "src/app/plugins",
    pages: "src/app/pages",
    layouts: "src/app/layouts",
    middleware: "src/app/middleware",
    assets: "src/app/assets",
  },
  ssr: false,
  css: ["ant-design-vue/dist/reset.css"],
});
