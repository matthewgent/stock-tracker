import currencyMixin from "./currency";

export default {
    mixins: [currencyMixin],
    methods: {
        moneyFormat (amount, currencyId) {
            const currency = this.findCurrency(currencyId);
            const nf = new Intl.NumberFormat('en-US');
            if (amount > 100) {
                amount = amount.toFixed(0);
            }
            return currency.symbol + nf.format(amount);
        },
        exchangeRateFormat (amount, currencyId) {
            const currency = this.findCurrency(currencyId);
            return currency.symbol + amount.toPrecision(5);
        },
        stockFormat(amount, currencyId) {
            const currency = this.findCurrency(currencyId);
            let decimals = 2;
            if (amount > 0 && amount < 0.01) {
                decimals = 4;
            }
            return currency.symbol + amount.toFixed(decimals);
        },
    }
};
