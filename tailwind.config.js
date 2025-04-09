import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                plusJakartaSans: ['Plus Jakarta Sans'],
                spaceGrotesk: ['Space Grotesk'],
            },
            colors: {
                'background-cover': '#77547C',
                'background-cover-dark': '#665551',

                'background': '#FFFFFF',
                'background-dark': '#665551',

                'sidebar-background': '#F6D7E4',
                'sidebar-primary': '#77547C',
                'sidebar-background-dark': '#BDAC9C',
                'sidebar-primary-dark': '#665551',

                'primary-color': '#77547C',
                'secondary-color': '#F6D7E4',
                'primary-color-dark': '#665551',
                'secondary-color-dark': '#BDAC9C',
            },
        },
    },

    plugins: [
        forms,
        require('daisyui'),
    ],
};
