<template>
    <div class="row w-100">
        <section class="col col-md-6">

            <div class="card" style="max-width: 40rem; min-height: 20rem;">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            <i class="fa fa-pie-chart"></i>
                            Category wise monthly expense
                        </h3>
                    </div>
                </div>
                <div class="card-body">

                    <DoughnutChartVue @monthChange="fetchDoughnutChartDetails" :chartDetails="doughnutChartDetails">
                    </DoughnutChartVue>

                </div>
            </div>
        </section>
        <section class="col col-md-6">

            <div class="card" style="max-width: 40rem; min-height: 30rem;">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            <i class="fa fa-area-chart"></i>
                            Category wise annual expense (<small>{{ year }}</small>)
                        </h3>
                        <select class="form-control form-control-sm w-50" v-model="year" @change="fetchAreaChartDetails();"> w-50
                            <option v-for="year in years" :key="year">{{ year }}</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">

                    <AreaChartVue @yearChange="fetchAreaChartDetails" :chartDetails="areaChartDetails">
                    </AreaChartVue>

                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
import AreaChartVue from './AreaChart.vue';
import DoughnutChartVue from './DoughnutChart.vue';
export default {
    components: {
        AreaChartVue, DoughnutChartVue
    },
    data() {
        return {
            showBarChart: true,
            doughnutChartDetails: {
                labels: [],
                data: [],
                totalExpenseAmount: 0
            },
            areaChartDetails: [],
            year: new Date().getFullYear()
        }
    },
    mounted() {
        this.fetchDoughnutChartDetails();
        this.fetchAreaChartDetails(this.year);

    },
    computed: {
        years() {
            const currentYear = new Date().getFullYear();
            const years = [];
            for (let year = currentYear; year >= 2000; year--) {
                years.push(year);
            }
            return years;
        }
    },
    methods: {
        fetchDoughnutChartDetails(val = { month: new Date().getMonth() + 1, year: new Date().getFullYear() }) {
            axios.get(`/fetch-doughnut-chart-data?month=${val.month}&year=${val.year}`).then((response) => {
                this.doughnutChartDetails.labels = response.data.labels;
                this.doughnutChartDetails.data = response.data.data;
                this.doughnutChartDetails.totalExpenseAmount = response.data.totalExpenseAmount
            })
        },
        fetchAreaChartDetails() {
            axios.get(`/fetch-area-chart-data?year=${this.year}`).then((response) => {
                this.areaChartDetails = response.data.expenseSummary.datasets;
            })
        }

    }

}
</script>