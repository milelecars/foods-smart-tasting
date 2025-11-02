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
        colors: {
          'milele-orange': '#FF6B35',
          'milele-blue': '#007BFF',
          'milele-green': '#2F6A39',
          'milele-red': '#DC3545',
          'milele-yellow': '#FFC107',
          'milele-purple': '#6F42C1',
          'milele-gray': '#6C757D',
        },
      },
    },
    plugins: [],
}