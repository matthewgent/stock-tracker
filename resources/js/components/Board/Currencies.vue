<template>
    <div id="currencies" class="col container overflow-hidden">
        <div class="row align-items-stretch g-4">
            <div class="col-12">
                <div class="plate">
                    <div class="plate-title plate-padding">Exchange Rates</div>

                    <div class="plate-padding-x py-2 row align-items-center">
                        <div class="col-12 col-sm-6">
                            <select class="form-select" v-model="chosenCurrencyId">
                                <option
                                    v-for="(currency, index) in currencies"
                                    :key="index"
                                    :value="currency.id"
                                >
                                    {{ currency.code }} - {{ currency.name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 text-right mt-3 mt-sm-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ chosenCurrency.symbol }}</span>
                                </div>
                                <input type="text" class="rate-amount form-control" v-model="chosenAmount">
                            </div>
                        </div>
                    </div>

                    <div class="plate-spacer"></div>

                    <div>
                        <div
                            v-for="(currency, index) in currencies"
                            :key="index"
                            class="
                            exchange-rate-row
                            plate-padding-x
                            py-2
                            d-flex
                            justify-content-between
                            align-items-center
                        "
                        >
                            <div>{{ currency.code }} - {{ currency.name }}</div>
                            <div>{{ exchangeRateFormat(usdRateComparison(currency), currency.id) }}</div>
                        </div>
                    </div>

                    <div class="plate-spacer"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// mixins
import moneyMixin from '../../mixins/money';

export default {
    mixins: [moneyMixin],
    props: {
        currencies: {required: true, type: Array},
        homeCurrencyId: {required: true, type: Number},
    },
    data() {
        return {
            chosenAmount: "1",
            chosenCurrencyId: this.homeCurrencyId,
        }
    },
    computed: {
        chosenCurrency() {
            return this.findCurrency(this.chosenCurrencyId);
        },
        chosenCurrencyUsdRate() {
            return this.chosenCurrency.latest_rate.usd_rate;
        },
        chosenAmountNumber() {
            return isNaN(parseFloat(this.chosenAmount)) ? 0 : parseFloat(this.chosenAmount);
        }
    },
    methods: {
        usdRateComparison(currency) {
            return this.chosenAmountNumber * currency.latest_rate.usd_rate / this.chosenCurrencyUsdRate;
        }
    }
}
</script>
