module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                'primary': {
                    DEFAULT: '#F2C94C',
                    'dark': '#94720a'
                },
                'dark': '#27292B',
                'light-green': {
                    DEFAULT: '#6FCF97',
                    'dark': '#277748'
                }
            }
        },
    },
    plugins: [],
}
