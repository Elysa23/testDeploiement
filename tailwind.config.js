import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors:{
                'violet-très-clair' : '#ddb4eb',
                'violet-plus-calir': '#efdcf8'  ,  
                'bleu-violet' : '#8695F9'     ,   
                'blanc-violet'    :'#ddbcf3',
            }
        },
    },

    plugins: [forms],

    plugins: [
        require('@tailwindcss/typography'),
        // ...
    ],
};
