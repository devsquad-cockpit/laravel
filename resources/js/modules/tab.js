export default () => ({
    currentTab: null,
    init() {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        let defaultTab = 'stackTrace';
        let firstTab = this.$refs.errorNav.children[0];

        if (firstTab) {
            defaultTab = firstTab.getAttribute('id').replace('link-', '');
        }

        console.log(this.$refs.errorNav.children[0].getAttribute('id'));

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
