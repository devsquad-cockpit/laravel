export default (initialStackTrace = []) => ({
    stackTrace: initialStackTrace,
    exceptionSelected: {},
    selectedIndex: 0,
    show: false,

    selectException(index) {
        this.show = false;

        setTimeout(() => {
            this.exceptionSelected = this.stackTrace[index];
            this.selectedIndex = index;
            this.show = true
        }, 200);
    },

    init() {
        this.exceptionSelected = this.stackTrace[this.selectedIndex];
        this.show = true;
    }
})
