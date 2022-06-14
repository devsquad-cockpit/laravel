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
                'dark-primary': '#27292B',
                'dark-secondary': '#121212',
                'dark-even': '#393D3F',
                'light-green': {
                    DEFAULT: '#6FCF97',
                    'dark': '#277748'
                }
            }
        },
    },
    plugins: [],
}
