<template>
    <div id="comparator" class="plate plate-padding">
        <div class="plate-title">Wealth Rank in {{ sovereignState.short_name }}</div>
        <div class="plate-spacer"></div>
        <div v-if="wealthPercentileGroup">
            <div class="row justify-content-between align-items-center">
                <div class="col-12 col-md-4 text-center">
                    <p>Rank</p>
                    <div class="plate-statistic">{{ rank }}%</div>
                </div>
                <div class="col-12 col-md-4 text-center mt-3 mt-md-0">
                    <p>Your Wealth</p>
                    <div class="plate-statistic">{{ moneyFormat(wealth, homeCurrencyId) }}</div>
                </div>
                <div class="col-12 col-md-4 text-center mt-3 mt-md-0">
                    <p>Next Rank</p>
                    <div v-if="nextRank === 100" class="plate-statistic">None</div>
                    <div v-else class="plate-statistic">{{ moneyFormat(nextRankWealth, homeCurrencyId) }} ({{ nextRank }}%)</div>
                </div>
            </div>
            <div class="plate-spacer"></div>
            <div class="text-center">You are wealthier than {{ rank }}% of individuals in your country.</div>
            <div class="plate-spacer"></div>
            <canvas id="comparator-chart"></canvas>
            <div class="plate-spacer"></div>
            <h6 class="text-center">Data sourced from {{ wealthPercentileGroup.source }}, {{ sourceDate }}.</h6>
        </div>
        <div v-else>
            <div class="plate-spacer"></div>
            <div class="plate-spacer"></div>
            <h3 class="text-center color-grey2">
                Individual wealth data for {{ sovereignState.short_name }} not available yet.
            </h3>
            <div class="plate-spacer"></div>
            <h5 class="text-center color-grey2">
                Please <a :href="contactUsUrl" class="link-primary">contact us</a> if you would like this to be available.
            </h5>
        </div>
    </div>
</template>

<script>
// mixins
import themeColorsMixin from "../../../mixins/themeColors";
import moneyMixin from "../../../mixins/money";
import currencyMixin from "../../../mixins/currency";

// libraries
import Chart from "chart.js/auto";

export default {
    mixins: [themeColorsMixin, moneyMixin, currencyMixin],
    props: [
        'homeCurrencyId',
        'sovereignState',
        'wealth',
        'wealthPercentileGroup',
        'contactUsUrl',
    ],
    data() {
        return {
            month: [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
            chart: null,
        }
    },
    computed: {
        homeCurrency() {
            this.findCurrency(this.homeCurrencyId);
        },
        wealthPercentiles() {
            const array = [...this.wealthPercentileGroup.wealth_percentiles];
            array.unshift({percentile: 100});
            return array;
        },
        sourceDate() {
            const date = this.$date(this.wealthPercentileGroup.source_date);
            return date.format('MMMM YYYY');
        },
        currentPercentileIndex() {
            let currentPercentileIndex = 0;
            const length = this.wealthPercentiles.length;
            for (let i = 0; i < length; i ++) {
                const wealthPercentile = this.wealthPercentiles[i];
                const amount = this.wealthPercentileHomeValue(wealthPercentile)
                if (amount <= this.wealth) {
                    currentPercentileIndex = i;
                }
            }
            return currentPercentileIndex;
        },
        currentPercentile() {
            return this.wealthPercentiles[this.currentPercentileIndex];
        },
        nextPercentile() {
            return this.wealthPercentiles[this.currentPercentileIndex + 1];
        },
        rank() {
            let rank = 100 - this.currentPercentile.percentile;
            if (rank === 0) {
                rank = '<10';
            }
            return rank;
        },
        rankWealth() {
            return this.wealthPercentileHomeValue(this.currentPercentile);
        },
        nextRank() {
            let nextRank = 100;
            if (this.nextPercentile) {
                nextRank = 100 - this.nextPercentile.percentile;
            }
            return nextRank;
        },
        nextRankWealth() {
            let wealth = 0;
            if (this.nextPercentile) {
                wealth = this.wealthPercentileHomeValue(this.nextPercentile);
            }
            return wealth;
        },
        chartLabels() {
            return Array.from(
                this.wealthPercentileGroup.wealth_percentiles,
                (wealthPercentile) => 100 - wealthPercentile.percentile + '%'
            );
        },
        chartBarValues() {
            const that = this;
            return Array.from(
                this.wealthPercentileGroup.wealth_percentiles,
                (wealthPercentile) => that.wealthPercentileHomeValue(wealthPercentile)
            );
        },
        chartBarColors() {
            const that = this;
            const palette = this.palette;
            const wealth = this.wealth;
            return Array.from(
                this.wealthPercentileGroup.wealth_percentiles,
                function (wealthPercentile) {
                    let color = 'rgba(160, 160, 160, 0.4)';
                    if (that.wealthPercentileHomeValue(wealthPercentile) <= wealth) {
                        color = palette.theme1;
                    }
                    return color;
                }
            );
        },
        chartLineValues() {
            const that = this;
            const wealth = this.wealth;
            return Array.from(
                that.wealthPercentileGroup.wealth_percentiles,
                () => wealth
            );
        }
    },
    methods: {
        wealthPercentileHomeValue(wealthPercentile) {
            return this.exchangeAmount(
                wealthPercentile.value,
                this.wealthPercentileGroup.currency_id,
                this.homeCurrencyId
            )
        }
    },
    mounted() {
        const that = this;
        if (this.wealthPercentileGroup) {
            this.chart = new Chart(
                document.getElementById('comparator-chart'),
                {
                    data: {
                        labels: this.chartLabels,
                        datasets: [
                            {
                                type: 'bar',
                                data: this.chartBarValues,
                                backgroundColor: this.chartBarColors,
                            },
                            {
                                type: 'line',
                                data: this.chartLineValues,
                                borderColor: this.palette.theme1,
                                pointRadius: 0,
                            }
                        ],
                    },
                    options: {
                        scales: {
                            y: {
                                ticks: {
                                    callback: function (value, index, values) {
                                        return that.moneyFormat(value, that.homeCurrencyId);
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return that.findCurrency(that.homeCurrencyId).symbol + context.formattedValue;
                                    }
                                }
                            }
                        }
                    }
                }
            );
        }
    }
}
</script>
