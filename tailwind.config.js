/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ['./resources/views/**/*.blade.php', './public/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        ink: '#0b2447',
        brand: { DEFAULT: '#6bbce9', light: '#a8d9f3', dark: '#1f7fb8', 50: '#eef7fd', 100: '#d9eefa' },
        navy: { DEFAULT: '#0b2447', 600: '#1a3f6b', 700: '#122c4f', 900: '#0b2447' },
        gold: '#e0a526',
      },
      fontFamily: {
        sans: ['Inter', 'Alexandria', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        arabic: ['Alexandria', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
