<template>
    <div id="wealth-history" class="plate plate-padding">
        <div class="d-flex justify-content-between align-items-center">
            <div class="plate-title">History</div>
            <div class="d-flex">
                <div v-for="(period, index) in periods" :key="index">
                    <input
                        type="radio"
                        class="btn-check"
                        name="wealth-line-chart-period"
                        :id="'period' + index"
                        autocomplete="off"
                        v-model="selectedPeriodIndex"
                        :value="index"
                        :disabled="periodDisabled(period)"
                    >
                    <label class="period-buttons button-primary period" :for="'period' + index">
                        <span>{{ period.title }}</span>
                        <span class="lock" v-if="periodDisabled(period)">
                            <i class="fas fa-lock"></i>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="p-2">
            <canvas id="wealth-history-chart"></canvas>
        </div>
    </div>
</template>

<script>
// mixins
import itemsMixin from '../../mixins/items';
import themeColorsMixin from "../../mixins/themeColors";
import moneyMixin from "../../mixins/money";
import currencyMixin from "../../mixins/currency";

// libraries
import Chart from "chart.js/auto";

export default {
    mixins: [itemsMixin, themeColorsMixin, moneyMixin, currencyMixin],
    props: {
        homeCurrencyId: {
            required: true,
            type: Number,
        },
        wealthCalculation: {
            required: false,
            type: Boolean,
            default: false,
        },
        items: {
            required: true,
            type: Array,
        },
        hasPremium: {
            type: Boolean,
            required: true,
        }
    },
    data() {
        return {
            selectedPeriodIndex: 0,
            periods: [
                {
                    title: '1M',
                    totalDuration: 'M',
                    totalQuantity: 1,
                    dividerDuration: 'd',
                    dividerQuantity: 1,
                    subscription: false
                },
                {
                    title: '3M',
                    totalDuration: 'M',
                    totalQuantity: 3,
                    dividerDuration: 'd',
                    dividerQuantity: 3,
                    subscription: false
                },
                {
                    title: '1Y',
                    totalDuration: 'y',
                    totalQuantity: 1,
                    dividerDuration: 'w',
                    dividerQuantity: 2,
                    subscription: false
                },
                {
                    title: '5Y',
                    totalDuration: 'y',
                    totalQuantity: 5,
                    dividerDuration: 'M',
                    dividerQuantity: 2,
                    subscription: false
                },
            ],
            chart: null,
        }
    },
    computed: {
        selectedPeriod() {
            return this.periods[this.selectedPeriodIndex];
        },
    },
    methods: {
        periodDates(period) {
            const datesArray = [];
            let dynamicDate = this.$date();
            let startDate = this.$date().subtract(
                period.totalQuantity,
                period.totalDuration
            );
            while (dynamicDate >= startDate) {
                datesArray.push(dynamicDate);

                dynamicDate = dynamicDate.subtract(
                    period.dividerQuantity,
                    period.dividerDuration
                );
            }
            return datesArray.reverse();
        },
        chartValues(period) {
            const values = [];

            for (const date of this.periodDates(period)) {
                let value;
                if (this.wealthCalculation) {
                    const assets = this.itemsByCategory('asset', this.items);
                    const debts = this.itemsByCategory('debt', this.items);
                    const assetsValue = this.itemsValue(assets, this.homeCurrencyId, date);
                    const debtsValue = this.itemsValue(debts, this.homeCurrencyId, date);
                    value = assetsValue - debtsValue;
                } else {
                    value = this.itemsValue(this.items, this.homeCurrencyId, date);
                }
                values.push(value);
            }

            return values;
        },
        chartLabels(period) {
            const labels = [];

            for (const date of this.periodDates(period)) {
                labels.push(date.format('D MMM YY'));
            }

            return labels;
        },
        periodDisabled(period) {
            return !this.hasPremium && period.subscription;
        }
    },
    watch: {
        selectedPeriodIndex() {
            this.chart.data.labels = this.chartLabels(this.selectedPeriod);
            this.chart.data.datasets[0].data = this.chartValues(this.selectedPeriod);
            this.chart.update();
        }
    },
    mounted() {
        const that = this;
        this.chart = new Chart(
            document.getElementById('wealth-history-chart'),
            {
                type: 'line',
                data: {
                    labels: this.chartLabels(this.selectedPeriod),
                    datasets: [{
                        data: this.chartValues(this.selectedPeriod),
                        borderColor: this.palette.theme1,
                    }],
                },
                options: {
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function (value, index, values) {
                                    return that.moneyFormat(value, that.homeCurrencyId);
                                }
                            }
                        },
                    },
                    elements: {
                        point: {
                            hitRadius: 10,
                            hoverRadius: 10,
                            radius: 0,
                        }
                    }
                }
            }
        );
    }
}
</script>
