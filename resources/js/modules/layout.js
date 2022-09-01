export default (defaultState = false) => ({
    defaultState,
    errorLayoutNavBar: defaultState,
    init() {
        let layout = localStorage.getItem('errorLayoutNavBar');
        if (layout !== null) {
            this.errorLayoutNavBar = layout === 'true';
        }

        this.$watch('errorLayoutNavBar', val => localStorage.setItem('errorLayoutNavBar', val));
    }
})
