
export default (totalErrors, unresolvedErrors, labels) => ({
    totalErrors,
    unresolvedErrors,
    labels,
    init() {
        let chart = new ApexCharts(this.$refs.chartArea, this.options)

        chart.render()

        this.$watch('totalErrors', () => {
            chart.updateOptions(this.options)
        })
        this.$watch('unresolvedErrors', () => {
            chart.updateOptions(this.options)
        })
    },
    get options() {
        return {
            chart: {
                height: 350,
                type: 'area',
                foreColor: this.darkMode ? '#ffffff' : '#4b5563',
                toolbar: {
                    show: false,
                    tools: {
                        download: false
                    }
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
            xaxis: {
                type: 'date',
                categories: this.labels
            },
            series: [{
                name: 'Total Errors',
                data: this.totalErrors,
                color: '#6FCF97'
            }, {
                name: 'Unresolved Errors',
                data: this.unresolvedErrors,
                color: '#F2C94C'
            }],
        }
    }

});
