<template>
    <div id="wealth" class="col container overflow-hidden">
        <div class="row align-items-stretch g-4">
            <div v-if="items.length === 0" class="col-12">
                <div class="plate plate-padding text-center">
                    <h3 class="d-inline-block">To get started, create your first asset</h3>
                    <a :href="assetsUrl" class="d-inline-block button-primary ms-3">
                        <span>Go to assets</span>
                        <i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="plate plate-padding">
                    <div class="plate-title">Wealth</div>
                    <div class="plate-statistic">{{ moneyFormat(wealth, homeCurrencyId) }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="plate plate-padding">
                    <div class="plate-title">Assets</div>
                    <div class="plate-statistic">{{ moneyFormat(assetsValue, homeCurrencyId) }}</div>
                    <a :href="assetsUrl" class="plate-link">See all <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="plate plate-padding">
                    <div class="plate-title">Debts</div>
                    <div class="plate-statistic">{{ moneyFormat(debtsValue, homeCurrencyId) }}</div>
                    <a :href="debtsUrl" class="plate-link">See all <i class="fas fa-chevron-right ms-1"></i></a>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="plate plate-padding">
                    <div class="plate-title">Avg. Wealth Change</div>
                    <div v-if="minimumDaysReached">
                        <div class="plate-statistic wealth-change-statistic">
                            <span>{{ moneyFormat(wealthGain, homeCurrencyId) }}</span>
                            <span>/ month</span>
                        </div>
                        <div class="wealth-change-information">based on time since first record ({{ firstRecordDateString }})</div>
                    </div>
                    <div v-else>
                        <div class="plate-spacer"></div>
                        <h3 class="text-center color-grey2">
                            Requires at least {{ this.wealthChangeMinimumDays }} days of data.
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <history
                    :items="items"
                    :home-currency-id="homeCurrencyId"
                    :currencies="currencies"
                    :wealth-calculation="true"
                    :has-premium="hasPremium"
                ></history>
            </div>
            <div class="col-xl-3">
                <projector
                    :home-currency-id="homeCurrencyId"
                    :currencies="currencies"
                    :date-of-birth="dateOfBirth"
                    :wealth="wealth"
                    :average-monthly-wealth-gain="wealthGain"
                    :minimum-days-reached="minimumDaysReached"
                    :wealth-change-minimum-days="wealthChangeMinimumDays"
                    :settings-url="settingsUrl"
                ></projector>
            </div>
            <div class="col-12">
                <comparator
                    :sovereign-state="sovereignState"
                    :wealth="wealth"
                    :wealth-percentile-group="wealthPercentileGroup"
                    :home-currency-id="homeCurrencyId"
                    :currencies="currencies"
                    :contact-us-url="contactUsUrl"
                ></comparator>
            </div>
        </div>
    </div>
</template>

<script>
// mixins
import itemsMixin from '../../mixins/items';
import moneyMixin from '../../mixins/money';
import currencyMixin from '../../mixins/currency';
import themeColorsMixin from "../../mixins/themeColors";

export default {
    mixins: [itemsMixin, moneyMixin, currencyMixin, themeColorsMixin],
    props: [
        'assetsUrl',
        'debtsUrl',
        'contactUsUrl',
        'settingsUrl',
        'homeCurrencyId',
        'dateOfBirthString',
        'wealthPercentileGroup',
        'sovereignState',
        'hasPremium',
        'items',
    ],
    data() {
        return {
            wealthChangeMinimumDays: 30,
        };
    },
    computed: {
        dateOfBirth() {
            return this.dateOfBirthString === null ? null : this.$date(this.dateOfBirthString);
        },
        assetsValue() {
            return this.calculateAssetsValue();
        },
        debtsValue() {
            return this.calculateDebtsValue();
        },
        wealth() {
            return this.assetsValue - this.debtsValue;
        },
        wealthGain() {
            return this.averageMonthlyWealthGain(
                this.items,
                this.findEarliestRecord(this.items),
                this.$date()
            );
        },
        firstRecordDateString() {
            const date = this.$date(this.findEarliestRecord(this.items));
            return date.format('YYYY-MM-DD');
        },
        wealthChangeTotalMonths() {
            const firstDate = this.$date(this.findEarliestRecord(this.items));
            const currentDate = this.$date();
            return currentDate.diff(firstDate, 'M', true);
        },
        minimumDaysReached() {
            const firstDate = this.$date(this.findEarliestRecord(this.items));
            const currentDate = this.$date();
            return currentDate.diff(firstDate, 'd') >= this.wealthChangeMinimumDays;
        },
    },
    methods: {
        calculateWealth(date = null) {
            return this.calculateAssetsValue(date) - this.calculateDebtsValue(date);
        },
        calculateAssetsValue(date = null) {
            const assets = this.itemsByCategory('asset', this.items);
            return this.itemsValue(assets, this.homeCurrencyId, date);
        },
        calculateDebtsValue(date = null) {
            const assets = this.itemsByCategory('debt', this.items);
            return this.itemsValue(assets, this.homeCurrencyId, date);
        },
        averageMonthlyWealthGain(items, start, end) {
            const startingWealth = this.calculateWealth(start);
            const endingWealth = this.calculateWealth(end);
            return (endingWealth - startingWealth) / this.wealthChangeTotalMonths;
        },
    },
}
</script>
