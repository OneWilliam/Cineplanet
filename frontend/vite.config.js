import tailwindcss from "@tailwindcss/vite";
import { sveltekit } from "@sveltejs/kit/vite";
import { defineConfig } from "vite";

export default defineConfig({
  plugins: [tailwindcss(), sveltekit()],
  server: {
    proxy: {
      "/api": {
        target: "http://cineplanet.local.com/",
        changeOrigin: true,
        secure: false,
      },
      "/uploads": {
        target: "http://cineplanet.local.com/",
        changeOrigin: true,
        secure: false,
      },
    },
  },
});
