import stackTrace from "./modules/stack-trace";

window._ = require('lodash');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Alpine from 'alpinejs';
import StackTrace from "./modules/stack-trace";

window.Alpine = Alpine;

Alpine.data('darkMode', (defaultState = false) => ({
    defaultState,
    darkMode() {
        let dark = localStorage.getItem('dark');
        if (dark !== null) {
            return dark === 'true';
        }

        return this.defaultState;
    },
    init() {
        this.$watch('darkMode', val => localStorage.setItem('dark', val));
    }
}))

Alpine.data('stackTrace', StackTrace);

Alpine.start();
