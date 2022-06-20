export default () => ({
    show: false,

    init() {
        setTimeout(() => this.show = true, 100);
        setTimeout(() => this.close(), 3000);
    },

    close() {
        this.show = false;
    }
});
