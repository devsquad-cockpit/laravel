export default (initialStackTrace = []) => ({
    stackTrace: initialStackTrace,
    exceptionSelected: {},

    selectException(index) {
        this.exceptionSelected = this.stackTrace[index]
    },

    init() {
        this.exceptionSelected = this.stackTrace[0]

        console.log(this.exceptionSelected.line_preview);
    }
})
