export default (defaultTab) => ({
    currentTab: defaultTab,
    navigateTo(tab) {
        this.currentTab = tab;
    },
    isActive(tab) {
        return this.currentTab === tab;
    }
});
