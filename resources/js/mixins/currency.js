export default {
    props: {
        currencies: {
            type: Array,
            required: true,
        },
    },
    methods: {
        findCurrency(currencyId) {
            return this.currencies.find(currency => currency.id === currencyId);
        },
        exchangeAmount(value, valueCurrencyId, desiredCurrencyId, date = null) {
            if (valueCurrencyId !== desiredCurrencyId) {
                const baseCurrencyRate = this.appropriateRate(valueCurrencyId, date);
                const desiredCurrencyRate = this.appropriateRate(desiredCurrencyId, date);

                return value / baseCurrencyRate * desiredCurrencyRate;
            } else {
                return value;
            }
        },
        exchangeSnapshot(snapshot, desiredCurrencyId) {
            if (snapshot !== null) {
                return this.exchangeAmount(
                    snapshot.value,
                    snapshot.currencyId,
                    desiredCurrencyId,
                    snapshot.date
                );
            } else {
                return 0.0;
            }
        },
        appropriateRate(currencyId, date = null) {
            if (date === null) {
                date = this.$date();
            }
            const currency = this.findCurrency(currencyId);
            const ratesLength = currency.chart_rates.length;
            let latestRate = currency.chart_rates[0].usd_rate;
            for (let i = ratesLength - 1; i >= 0; i--) {
                const currencyRate = currency.chart_rates[i];
                const currencyRateDate = this.$date(currencyRate.time);
                if (currencyRateDate.unix() <= date.unix()) {
                    latestRate = currencyRate.usd_rate;
                } else {
                    break;
                }
            }
            return latestRate;
        }
    }
};
