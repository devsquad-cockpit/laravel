export default (defaultTab) => ({
    currentTab: defaultTab,
    show: true,

    navigateTo(tab) {
        this.show = false;

        this.currentTab = tab;
        this.show = true;
    },
});
