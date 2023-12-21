import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        container: {
            center: true,
            padding: '2rem',
        },
        extend: {
            //page colors are taken from: https://www.realtimecolors.com/?colors=070b10-f7f9fc-0068ff-83aeea-4f91ed&fonts=Poppins-Poppins
            colors: {
                primary: '#0068FF',
                secondary: '#83AEEA', 
                accent: '#4F91ED',
                textColor: '#525252',
                lightBlueBackground: '#EBF3FF',
                whiter: '#f3f8ff',
              },
            fontFamily: {
                sans: ['Montserrat', defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
