export default (initialStackTrace = []) => ({
    stackTrace: initialStackTrace,
    exceptionSelected: {},
    selectedIndex: 0,
    show: false,
    showVendorFrames: false,
    filteredFrames: [],

    selectException(index) {
        this.show = false;

        setTimeout(() => {
            this.exceptionSelected = this.filteredFrames[index];
            this.selectedIndex = index;
            this.show = true
        }, 100);
    },

    init() {
        this.filterFrames();

        this.exceptionSelected = this.filteredFrames[this.selectedIndex];
        this.show = true;
    },

    toggleVendorFrames() {
        this.showVendorFrames = !this.showVendorFrames;

        this.filterFrames();
        this.selectException(0);
    },

    filterFrames() {
        this.filteredFrames = (this.showVendorFrames)
            ? this.stackTrace
            : this.stackTrace.filter(item => item.application_frame === true);
    }
})
