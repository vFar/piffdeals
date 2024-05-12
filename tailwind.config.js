import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
        
    ],

    theme: {
        container: {
            center: true,
        },
        extend: {
            //page colors are taken from: https://www.realtimecolors.com/?colors=070b10-f7f9fc-0068ff-83aeea-4f91ed&fonts=Poppins-Poppins
            colors: {
                primary: "#0068FF",
                secondary: "#83AEEA",
                accent: "#4F91ED",
                textColor: "#525252",
                lightBlueBackground: "#EBF3FF",
                whiter: "#ffffff",
                dark: "",
            },
            fontFamily: {
                sans: ["Montserrat", defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"), 
        require('flowbite/plugin'),
    ],
};
