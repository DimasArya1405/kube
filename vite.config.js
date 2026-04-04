import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        // Di dalam vite.config.js
        tailwindcss({
            config: "./tailwind.config.js", // Tambahkan baris ini jika ingin pakai cara lama
        }),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
