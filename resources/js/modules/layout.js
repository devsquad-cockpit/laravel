export default (defaultState = []) => ({
    defaultState,
    config: defaultState,
    darkMode: defaultState['dark'],
    preferredEditor: defaultState['editor'],
    errorTopBarNavigation: false,
    init() {
        this.errorTopBarNavigation = defaultState['layout']['error'] === 'navbar';
        this.theme();
        this.layout();
    },
    theme() {
        let dark = localStorage.getItem('dark');
        if (dark !== null) {
            this.darkMode = dark === 'true';
        }

        this.$watch('darkMode', val => localStorage.setItem('dark', val));
    },
    layout() {
        let keys = [
            'errorTopBarNavigation',
            'preferredEditor',
        ]

        keys.forEach(key => {
            let value = localStorage.getItem(key);

            if (value !== null) {
                this[key] = key === 'preferredEditor' ? value : (value === 'true');
            }

            this.$watch(key, val => localStorage.setItem(key, val));
        });
    },
    opening(file = '', line = 0) {

        let links = {
            'phpstorm' :  `phpstorm://open?file=${file}&line=${line}`,
            'vscode' : `vscode://file${file}:${line}`,
        };

        return links[this.preferredEditor];
    },
})
