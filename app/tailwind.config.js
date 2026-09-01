/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        colors: {
        brand: {
            800: '#8C2323',
            900: 'var(--brand-primary)',
            950: 'var(--brand-primary-hover)',
        },
        },
    },
    },
  plugins: [],
}