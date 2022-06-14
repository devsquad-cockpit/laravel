import Alpine from 'alpinejs';
import stackTrace from "./modules/stack-trace";
import toggleTheme from "./modules/toggle-theme";
import datepicker from "./modules/datepicker";
import rangeDatepicker from "./modules/range-datepicker";

window.Alpine = Alpine;

Alpine.data('toggleTheme', toggleTheme);
Alpine.data('stackTrace', stackTrace);
Alpine.data('datepicker', datepicker);
Alpine.data('rangeDatepicker', rangeDatepicker);

Alpine.start();
