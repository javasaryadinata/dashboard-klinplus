import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  server: {
    host: "192.168.1.5",
    port: 5173,
    cors: {
      origin: "http://192.168.1.5:8000",
      credentials: true,
    },
    watch: {
      usePolling: true,
    },
    hmr: {
      host: "192.168.1.5",
    },
  },

  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
