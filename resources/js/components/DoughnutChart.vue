<template>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body" style="
                            max-height: 100%;
                            display: flex;
                            overflow-x: auto;
                            justify-content: space-between;
                            align-items:center
                        ">
                    <!-- Add left arrow -->
                    <div class="arrow arrow-left" @click="scrollLeft" v-if="currentYear > minYear">
                        <i class="fa fa-chevron-left"></i>
                    </div>
                    <!-- Add right arrow -->
                    <div class="arrow arrow-right float-right" @click="scrollRight" v-if="currentYear < maxYear">
                        <i class="fa fa-chevron-right"></i>
                    </div>
                    <div class="col" v-for="i in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]" :key="i"
                        style="flex: 1; display: flex; white-space: nowrap">
                        <!-- Card content -->
                        <div class="text-center p-2 rounded" :class="
                        // Condition for bg-primary text-white class
                        selectedMonthBlock == i && selectedYearBlock == currentYear
                            ? 'bg-primary text-white'
                            : 'bg-light text-secondary'
                        " @click="selectMonth(i)" role="button">
                            <div>

                                {{
                        new Date(
                            currentYear,
                            i - 1,
                            1
                        ).toLocaleString("default", {
                            month: "short",
                        })
                    }}
                            </div>

                            <div>{{ currentYear }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-between" v-if="this.chartData.labels.length != 0">
        <div class="col col-md-6">
            <Doughnut id="doughnut-chart" :options="chartOptions" :data="chartData" />

        </div>

        <div class="col col-md-6">
            <div class="row">
                <div class="col">
                    <div class="row align-items-center">
                        <div class="col" v-for="(item, index) in chartDetails?.labels">

                            <p class="text-gray font-weight-bold mb-0">{{ item }} (<small>
                                <i class="fa fa-rupee"></i>  {{  Number(this.chartDetails?.data[index]) }}
                            </small>)</p>
                            <p :style="{ color: this.generateBackgroundColors(this.chartDetails?.labels)[index] }"><i
                                    class="fa fa-tag"></i> <i class="fa fa-percent"></i>{{
                        (Number(this.chartDetails?.data[index]) * 100 /
                            Number(this.chartDetails.totalExpenseAmount)).toFixed(2)
                    }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>
                        <p class="text-gray font-weight-bold mb-0">Total expense:<i class="fa fa-rupee"></i>{{  Number(this.chartDetails.totalExpenseAmount) }}</p>
                    </p>
                </div>
            </div>

        </div>
    </div>
    <div class="row justify-content-center" v-if="this.chartData.labels.length == 0">
        <div class="col col-md-6">
            <div class="alert alert-info text-center" role="alert">
                <i class="fa fa-warning"></i> <b> No data available</b>
            </div>
        </div>
    </div>

</template>

<script>
import { Doughnut } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, DoughnutController } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, DoughnutController, CategoryScale, LinearScale)

export default {
    name: 'DoughnutChart',
    components: { Doughnut },
    props: {
        chartDetails: Object
    },
    data() {
        return {
            currentYear: new Date().getFullYear(),
            minYear: 2000, // Set the minimum year
            maxYear: new Date().getFullYear(), // Set the maximum year
            selectedMonthBlock: new Date().getMonth() + 1,
            selectedYearBlock: new Date().getFullYear(),
            financialYear: new Date().getFullYear(),
            chartOptions: {
                responsive: true,
                tooltips: {
                    enabled: true // Show tooltips all the time
                },
            }
        }
    },
    computed: {
        chartData() {
            return {
                labels: this.chartDetails?.labels,
                datasets: [{
                    data: this.chartDetails?.data,
                    hoverOffset: 10,
                    backgroundColor: this.generateBackgroundColors(this.chartDetails?.labels)
                }]
            }
        }
    },
    methods: {
        selectMonth(month) {
            this.selectedMonthBlock = month;
            this.selectedYearBlock = this.currentYear;
            this.$emit('monthChange', { month: this.selectedMonthBlock, year: this.selectedYearBlock })
        },
        scrollLeft() {
            if (this.currentYear > this.minYear) {
                this.currentYear--;
            }
            this.currentFinancialYear = this.currentYear + 1;
        },
        scrollRight() {
            if (this.currentYear < this.maxYear) {
                this.currentYear++;
            }
            this.currentFinancialYear = this.currentYear + 1;
        },
        generateBackgroundColors(labels) {
            // Generate dynamic background colors based on label count
            return labels.map((label, index) => {
                const hue = (index * 137.5) % 360; // Adjust the multiplier for different color ranges
                return `hsl(${hue}, 70%, 50%)`;
            });
        }
    },


}
</script>