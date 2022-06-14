import moment from "moment";

export default ({minValue, maxValue, minRef, maxRef}) => ({
    minValue: minValue || new Date(),
    maxValue: maxValue || new Date(),
    init() {
        console.log(minRef, maxRef, this.$refs[minRef], this.$refs[maxRef]);
        let minPicker = flatpickr(this.$refs[minRef], {
            dateFormat: 'y/m/d',
            defaultDate: this.minValue,
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
            if (start.diff(end, 'days')) {
                let maxDate = start.format('YY/MM/DD');
                maxPicker.setDate(maxDate);
            }
        })
        this.$watch('maxValue', () => {
            maxPicker.setDate(this.maxValue);
        })
    }
})
