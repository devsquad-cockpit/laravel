export default (defaultState = false) => ({
    defaultState,
    darkMode: defaultState,
    init() {
        let dark = localStorage.getItem('dark');
        if (dark !== null) {
            this.darkMode = dark === 'true';
        }
        
        this.$watch('darkMode', val => localStorage.setItem('dark', val));
    }
})
