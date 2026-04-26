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
                sans: ['Inter', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'user-primary': {
                    DEFAULT: '#3b82f6',
                    dark: '#1e3a8a',
                    light: '#60a5fa',
                    accent: '#2563eb',
                    bg: '#eff6ff',
                },
                'admin-primary': {
                    DEFAULT: '#f97316',
                    dark: '#9a3412',
                    light: '#fb923c',
                    accent: '#ea580c',
                    bg: '#fff7ed',
                },
            },
            animation: {
                'slide-up': 'slideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards',
                'slide-down': 'slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards',
                'fade-in': 'fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards',
                'scale-in': 'scaleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards',
            },
            keyframes: {
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                slideDown: {
                    '0%': { transform: 'translateY(-20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
            },
        },
    },

    plugins: [forms],
};
