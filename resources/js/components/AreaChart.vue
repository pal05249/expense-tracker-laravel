<template>
     <div class="chart-container" v-if="this.chartData.labels.length != 0">
        <Line id="line-chart" :options="chartOptions" :data="chartData" />
    </div>

    <div class="no-data" v-else>
        <div class="alert alert-info text-center" role="alert">
            <i class="fa fa-warning"></i> <b> No data available</b>
        </div>
    </div>
</template>

<script>
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale)

export default {
    name: 'AreaChart',
    components: { Line },
    props: {
        chartDetails: Array,
    }
    ,
    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false, // This ensures the chart adjusts its size based on container size
                elements: {
                    line: {
                        tension: 0.5
                    }
                }
            }
        }
    },
    computed: {
        chartData() {
            return {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: this.chartDetails
            }
        }
    }
}
</script>
<style scoped>
.chart-container {
    width: auto; /*Ensure the chart container fills its parent*/
    height: 100%; /* Allow the height to adjust based on the chart content */
    min-height: 200px;
}

.no-data {
    text-align: center; /* Center the "No data available" message */
    margin-top: 20px; /* Add some space between the message and the chart */
}
</style>