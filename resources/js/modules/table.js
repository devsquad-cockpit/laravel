export default () => ({
    totalColumns: 0,
    init() {
        this.totalColumns = this.$el.querySelectorAll('th').length - 1;
    },
    getThColumnClasses(element) {
        let first = element.cellIndex === 0;
        let last = element.cellIndex === this.totalColumns;

        return {
            'py-3.5 pl-4 pr-3 sm:pl-6': first && !last,
            'px-3 py-3.5': !first && !last,
            'text-left text-gray-700 dark:text-gray-400': !last,
            'relative py-3.5 pl-3 pr-4 sm:pr-6': last
        }
    },
    getTdColumnClasses(element) {
        let first = element.cellIndex === 0;
        let last = element.cellIndex === this.totalColumns;
        let even = (element.parentElement.rowIndex % 2) === 0;

        return {
            'py-4 pl-4 pr-3 sm:pl-6': first && !last,
            'px-3 py-4': !first && !last,
            'text-gray-700 dark:text-gray-400': !last,
            'relative py-4 pl-3 pr-4 sm:pr-6 text-right': last,
            'bg-dark-even': even
        }
    },
    sortBy(sortBy, sortDirection) {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        params.set('sortBy', sortBy);
        params.set('sortDirection', sortDirection);

        console.log(url, `${url.pathname}?${params.toString()}`);
        window.location.href = `${url.pathname}?${params.toString()}`;
    },
    perPage(perPage) {
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        params.set('perPage', perPage);
        
        window.location.href = `${url.pathname}?${params.toString()}`;
    }
});
