require('./modules/tooltip');
import Alpine from 'alpinejs';
import stackTrace from "./modules/stack-trace";
import toggleTheme from "./modules/toggle-theme";
import datepicker from "./modules/datepicker";
import rangeDatepicker from "./modules/range-datepicker";
import table from "./modules/table";
import tab from './modules/tab';
import toast from './modules/toast';
import copyable from './modules/copyable';
import filter from "./modules/filter";
import chartArea from './modules/chartArea';
import ApexCharts from 'apexcharts'

window.Alpine = Alpine;
window.ApexCharts = ApexCharts;

Alpine.data('toggleTheme', toggleTheme);
Alpine.data('stackTrace', stackTrace);
Alpine.data('datepicker', datepicker);
Alpine.data('rangeDatepicker', rangeDatepicker);
Alpine.data('table', table);
Alpine.data('filter', filter);
Alpine.data('tab', tab);
Alpine.data('toast', toast);
Alpine.data('copyable', copyable);
Alpine.data('chartArea', chartArea);

Alpine.start();
