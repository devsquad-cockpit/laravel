export default () => ({
    init() {
        let columns = this.$el.querySelectorAll('th');
        let firstColumn = columns[0]
        let lastColumn = columns[columns.length - 1];
        firstColumn.setAttribute('first', 'true');
        lastColumn.setAttribute('last', 'true');
    },
    getThColumnClasses(element) {
        return {
            'py-3.5 pl-4 pr-3 sm:pl-6': element.getAttribute('first') && !element.getAttribute('last'),
            'px-3 py-3.5': !element.getAttribute('first') && !element.getAttribute('last'),
            'text-left text-gray-700 dark:text-gray-400': !element.getAttribute('last'),
            'relative py-3.5 pl-3 pr-4 sm:pr-6': element.getAttribute('last')
        }
    }
});
