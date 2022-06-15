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
        let url = '';
        let param = Object();
        if (typeof element === 'object' && !(element instanceof HTMLElement)) {
            url = this.setUrlParams(element);
        } else {
            param[element.name] = value ?? element.value;
            url = this.setUrlParams(param);
        }
        
        window.location.href = url;
    },
    isElement(object) {
        try {
            return object instanceof HTMLElement;
        } catch (exception) {
            return (typeof object === "object") &&
                (object.nodeType === 1) && (typeof object.style === "object") &&
                (typeof object.ownerDocument === "object");
        }
    }
})
