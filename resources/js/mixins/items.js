import currencyMixin from "./currency";

export default {
    mixins: [currencyMixin],
    props: ['itemTypes', 'itemCategories'],
    data() {
        return {
            itemTypeStyles: {
                bankAccount: {
                    iconClass: 'fas fa-university',
                    singularTitle: 'Bank Account',
                    pluralTitle: 'Bank Accounts',
                    inactiveTitle: 'Closed',
                    exampleName: 'Lloyds savings account',
                    color: '#2a7a3e',
                },
                bond: {
                    iconClass: 'fas fa-money-check-alt',
                    singularTitle: 'Bond',
                    pluralTitle: 'Bonds',
                    inactiveTitle: 'Sold',
                    exampleName: 'Treasury bond',
                    color: '#28acbd',
                },
                car: {
                    iconClass: 'fas fa-car',
                    singularTitle: 'Car',
                    pluralTitle: 'Cars',
                    inactiveTitle: 'Sold',
                    exampleName: 'Toyota corolla',
                    color: '#bd2828',
                },
                cash: {
                    iconClass: 'fas fa-money-bill-wave-alt',
                    singularTitle: 'Cash',
                    pluralTitle: 'Cash',
                    inactiveTitle: 'Disposed',
                    exampleName: 'Emergency cash',
                    color: '#7ac240',
                },
                commodity: {
                    iconClass: 'fas fa-seedling',
                    singularTitle: 'Commodity',
                    pluralTitle: 'Commodities',
                    inactiveTitle: 'Sold',
                    exampleName: 'Gold bullion',
                    color: '#bdbd28',
                },
                cryptoCurrency: {
                    iconClass: 'fab fa-btc',
                    singularTitle: 'CryptoCurrency',
                    pluralTitle: 'CryptoCurrencies',
                    inactiveTitle: 'Sold',
                    exampleName: 'BitCoin wallet',
                    color: '#cf8f23',
                },
                pension: {
                    iconClass: 'fas fa-piggy-bank',
                    singularTitle: 'Pension',
                    pluralTitle: 'Pensions',
                    inactiveTitle: 'Withdrawn',
                    exampleName: 'National pension',
                    color: '#b328bd',
                },
                property: {
                    iconClass: 'fas fa-home',
                    singularTitle: 'Property',
                    pluralTitle: 'Property',
                    inactiveTitle: 'Sold',
                    exampleName: 'Family home',
                    color: '#874c1f',
                },
                stock: {
                    iconClass: 'fas fa-building',
                    singularTitle: 'Stock or ETF',
                    pluralTitle: 'Stocks & ETFs',
                    inactiveTitle: 'Sold',
                    exampleName: 'Robinhood account',
                    color: '#2843bd',
                },
                other: {
                    iconClass: 'fas fa-plus',
                    singularTitle: 'Other',
                    pluralTitle: 'Other',
                    inactiveTitle: 'Sold',
                    exampleName: 'Antique watch',
                    color: '#7c28bd',
                },
                creditCard: {
                    iconClass: 'fas fa-credit-card',
                    singularTitle: 'Credit Card',
                    pluralTitle: 'Credit Cards',
                    inactiveTitle: 'Closed',
                    exampleName: 'Lloyds credit card',
                    color: '#bd2828',
                },
                loan: {
                    iconClass: 'fas fa-hand-holding-usd',
                    singularTitle: 'Loan',
                    pluralTitle: 'Loans',
                    inactiveTitle: 'Settled',
                    exampleName: 'Car loan',
                    color: '#bdbd28',
                },
                mortgage: {
                    iconClass: 'fas fa-home',
                    singularTitle: 'Mortgage',
                    pluralTitle: 'Mortgages',
                    inactiveTitle: 'Closed',
                    exampleName: 'Lloyds mortgage',
                    color: '#874c1f',
                },
                tax: {
                    iconClass: 'fas fa-university',
                    singularTitle: 'Tax (owed/due)',
                    pluralTitle: 'Taxes',
                    inactiveTitle: 'Settled',
                    exampleName: 'Company tax bill',
                    color: '#b328bd',
                },
            },
            itemCategoryStyles: {
                asset: {
                    iconClass: 'fas fa-piggy-bank',
                    singularTitle: 'Asset',
                    pluralTitle: 'Assets',
                },
                debt: {
                    iconClass: 'fas fa-hand-holding-usd',
                    singularTitle: 'Debt',
                    pluralTitle: 'Debts',
                }
            },
        };
    },
    methods: {
        itemClass(item) {
            // 0 - basic
            // 1 - tracked stock
            let itemClass = 0;
            if (item.stock !== null) {
                itemClass = 1;
            }
            return itemClass;
        },
        itemName(item) {
            const itemClass = this.itemClass(item);
            let itemName = item.name;
            switch (itemClass) {
                case 0:
                    itemName = item.name;
                    break;
                case 1:
                    itemName = item.stock.ticker.name;
                    break;
            }
            return itemName;
        },
        orderItemsByValue(items) {
            return items.sort((a, b) =>
                a.item_values[0].home_currency_value >
                b.item_values[0].home_currency_value
            );
        },
        itemsByCategory(category, items) {
            return items.filter(
                item => item.item_type.item_category.name === category
            );
        },
        itemsByType(type, items) {
            return items.filter(
                item => item.item_type.name === type
            );
        },
        typesByCategory(category) {
            return this.itemTypes.filter(
                type => type.item_category.name === category
            );
        },
        itemActive(item) {
            const latestSnapshot = this.appropriateSnapshot(item);
            let active = true;
            if (latestSnapshot !== null && latestSnapshot.value === 0.0) {
                active = false;
            }
            return active;
        },
        itemValue(item, currencyId, date = null) {
            const snapshot = this.appropriateSnapshot(item, date);
            return this.exchangeSnapshot(snapshot, currencyId);
        },
        itemsValue(items, currencyId, date = null) {
            let value = 0;
            for (let item of items) {
                value += this.itemValue(item, currencyId, date);
            }
            return value;
        },
        appropriateSnapshot(item, date = null) {
            if (!this.$date.isDayjs(date)) {
                date = this.$date();
            }
            let snapshot = null;
            if (this.itemClass(item) === 1) {
                const tickerPrice = this.tickerPrice(item, date);
                const stockQuantity = this.stockQuantity(item, date);
                const priceCoefficient = item.stock.ticker.security_exchange.price_coefficient;
                snapshot = null;
                if (tickerPrice !== null && stockQuantity !== null) {
                    snapshot = {
                        value: stockQuantity.quantity * tickerPrice.price * priceCoefficient,
                        currencyId: item.stock.ticker.security_exchange.currency_id,
                        date: this.$date(tickerPrice.time),
                    };
                }
            } else {
                const itemValueLength = item.item_values.length;
                for (let i = itemValueLength - 1; i >= 0; i--) {
                    const itemValue = item.item_values[i];
                    const itemValueDate = this.$date(itemValue.time);
                    if (itemValueDate.unix() <= date.unix()) {
                        snapshot = {
                            value: itemValue.value,
                            currencyId: itemValue.currency_id,
                            date: itemValueDate,
                        };
                    }
                }
            }
            return snapshot;
        },
        tickerPrice(item, date) {
            let finalPrice = null;
            let tickerPrices = item.stock.ticker.chart_prices;
            if (typeof tickerPrices === 'undefined') {
                tickerPrices = [item.stock.ticker.latest_price];
            }
            const tickerPricesLength = tickerPrices.length;
            for (let i = tickerPricesLength - 1; i >= 0; i--) {
                const tickerPrice = tickerPrices[i];
                const tickerPriceDate = this.$date(tickerPrice.time);
                if (tickerPriceDate.unix() <= date.unix()) {
                    finalPrice = tickerPrice;
                }
            }
            return finalPrice;
        },
        stockQuantity(item, date) {
            let finalQuantity = null;
            const quantities = item.stock.stock_quantities;
            const quantitiesLength = quantities.length;
            for (let i = quantitiesLength - 1; i >= 0; i--) {
                const quantity = quantities[i];
                const quantityDate = this.$date(quantity.time);
                if (quantityDate.unix() <= date.unix()) {
                    finalQuantity = quantity;
                }
            }
            return finalQuantity;
        },
        findEarliestRecord(items) {
            let record = this.$date();
            for (const item of items) {
                const itemValuesQuantity = item.item_values.length;
                if (item.item_values.length > 0) {
                    const earliestItemValue = item.item_values[itemValuesQuantity - 1];
                    const time = this.$date(earliestItemValue.time);
                    if (time.unix() < record.unix()) {
                        record = time;
                    }
                }
            }
            return record;
        },
    },
};
