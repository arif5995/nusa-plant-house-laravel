/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                forest: {
                    50: '#F4F9F5',
                    100: '#E2EFE3',
                    600: '#40916C',
                    800: '#1B4332',
                },
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'], // Mengatur Inter sebagai font default
                serif: ['Poppins', 'serif'], // Mengatur Poppins sebagai serif alternatif jika digunakan
            },
        },
    },
    plugins: [],
}