export default (defaultTab) => ({
    currentTab: null,
    init() {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        this.currentTab = params.tab || defaultTab;
    },
    navigateTo(tab) {
        this.currentTab = tab;
        window.history.pushState(null, null, `?tab=${tab}`);
    },
    isActive(tab) {
        return this.currentTab === tab;
    }
});
