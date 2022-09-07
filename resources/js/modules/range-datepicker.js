import moment from "moment";

export default ({minValue, maxValue, minRef, maxRef}) => ({
    minValue: null,
    maxValue: null,
    init() {
        this.minValue = minValue.length ? minValue : new Date();
        if (typeof this.minValue === 'object') {
            this.minValue = moment(this.minValue).format('YY/MM/DD');
        }

        this.maxValue = maxValue.length ? maxValue : new Date();
        if (typeof this.maxValue === 'object') {
            this.maxValue = moment(this.maxValue).format('YY/MM/DD');
        }

        let minPicker = flatpickr(this.$refs[minRef], {
            dateFormat: 'y/m/d',
            defaultDate: this.minValue,
            maxDate: 'today',
            onReady: (date, dateString) => {
                this.minValue = dateString;
            },
            onChange: (date, dateString) => {
                this.minValue = dateString;
                maxPicker.set('minDate', this.minValue);
            }
        });

        let maxPicker = flatpickr(this.$refs[maxRef], {
            dateFormat: 'y/m/d',
            defaultDate: this.maxValue,
            minDate: this.$refs[minRef].value,
            maxDate: 'today',
            onReady: (date, dateString) => {
                this.maxValue = dateString;
            },
            onChange: (date, dateString) => {
                this.maxValue = dateString;
            }
        });

        this.$watch('minValue', () => {
            minPicker.setDate(this.minValue);
            let start = moment(this.minValue, 'YY/MM/DD')
            let end = moment(this.maxValue, 'YY/MM/DD')
            if (start.diff(end, 'days') > 0) {
                let maxDate = start.format('YY/MM/DD');
                maxPicker.setDate(maxDate);
                this.maxValue = this.minValue;
            }
        })

        this.$watch('maxValue', () => {
            maxPicker.setDate(this.maxValue);
        })
    }
})
