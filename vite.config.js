import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  server: {
    host: "192.168.1.4",
    port: 5173,
    cors: {
      origin: 'http://192.168.1.4:8000', // ganti sesuai IP dan port Laravel kamu
      credentials: true,
    },
    watch: {
      usePolling: true, // penting untuk Windows atau jaringan lokal
    },
    hmr: {
      host: "192.168.1.4",
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
