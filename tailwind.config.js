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
                sans: ['Open Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                soft: {
                    primary: '#cb0c9f',
                    secondary: '#8392ab',
                    info: '#17c1e8',
                    success: '#82d616',
                    warning: '#fbcf33',
                    danger: '#ea0606',
                    dark: '#344767',
                    bg: '#f8f9fa'
                }
            },
            boxShadow: {
                'soft': '0 20px 27px 0 rgba(0,0,0,0.05)',
                'soft-sm': '0 8px 26px -4px rgba(0,0,0,0.08)',
                'soft-hover': '0 20px 27px 0 rgba(0,0,0,0.12)',
            }
        },
    },

    plugins: [forms],
};
