export default () => ({
    setUrlParams(newParams) {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        Object.entries(newParams).forEach((param) => {
            let [key, value] = param;
            params.set(key, value);
        });

        return `${url.pathname}?${params.toString()}`;
    },
    filter(element, value = null) {
        let param = Object();
        param[element.name] = value ?? element.value;
        window.location.href = this.setUrlParams(param);
    }
})
