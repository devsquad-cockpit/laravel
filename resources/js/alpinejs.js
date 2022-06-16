import Alpine from 'alpinejs';
import stackTrace from "./modules/stack-trace";
import toggleTheme from "./modules/toggle-theme";
import datepicker from "./modules/datepicker";
import rangeDatepicker from "./modules/range-datepicker";
import table from "./modules/table";
import tab from './modules/tab';
import toast from './modules/toast';

import filter from "./modules/filter";

window.Alpine = Alpine;

Alpine.data('toggleTheme', toggleTheme);
Alpine.data('stackTrace', stackTrace);
Alpine.data('datepicker', datepicker);
Alpine.data('rangeDatepicker', rangeDatepicker);
Alpine.data('table', table);
Alpine.data('filter', filter);
Alpine.data('tab', tab);
Alpine.data('toast', toast);

Alpine.start();
