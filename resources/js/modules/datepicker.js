import flatpickr from "flatpickr";

export default ({value, ref}) => ({
    value: value || new Date(),
    init() {
        let picker = flatpickr(this.$refs[ref], {
            dateFormat: 'y/m/d',
            defaultDate: this.value,
            onReady: (date, dateString) => {
                this.value = dateString;
            },
            onChange: (date, dateString) => {
                this.value = dateString;
            }
        });

        this.$watch('value', () => picker.setDate(this.value))
    }
})
