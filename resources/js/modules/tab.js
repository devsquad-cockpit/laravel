export default (defaultTab) => ({
    currentTab: defaultTab,

    init() {},

    navigateTo(tab) {
        this.currentTab = tab;
    },
});
