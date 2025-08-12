<template>
    <div :class="[
        'plate',
        'plate-padding',
        showProjector ? 'd-flex' : '',
        showProjector ? 'flex-column' : '',
        showProjector ? 'justify-content-between' : '',
    ]">
        <div class="plate-title">Projector</div>
        <div v-if="showProjector" class="text-center">
            <label for="age" class="form-label">Age</label>
            <input
                type="range"
                v-model="selectedAge"
                class="form-range"
                :min="minimumAge"
                :max="maximumAge"
                step="1"
                id="age"
            >
            <h1 class="text-primary">{{ selectedAge }}</h1>
        </div>
        <div v-if="showProjector" class="text-center">
            <div>Estimated Wealth</div>
            <h1 class="text-primary">{{ moneyFormat(projectedWealth, this.homeCurrencyId) }}</h1>
        </div>
        <div v-if="showProjector" class="text-center">
            <h5>
                *Based on an average wealth change of {{ moneyFormat(averageMonthlyWealthGain, this.homeCurrencyId) }}
                per month for the next {{ yearsToSelectedAge }}
                year{{ yearsToSelectedAge > 1 ? 's' : '' }}.
            </h5>
        </div>
        <div v-if="!showProjector">
            <div class="plate-spacer"></div>
            <div class="plate-spacer"></div>
            <h3 class="text-center color-grey2">
                <span v-if="!minimumDaysReached">Requires at least {{ this.wealthChangeMinimumDays }} days of data.</span>
                <span v-else>Requires date of birth. Update in <a :href="settingsUrl" class="link-primary">settings</a>.</span>
            </h3>
        </div>
    </div>
</template>

<script>
import itemsMixin from "../../../mixins/items";
import moneyMixin from "../../../mixins/money";
import currencyMixin from "../../../mixins/currency";
import dayjs
    from "dayjs";

export default {
    mixins: [itemsMixin, moneyMixin, currencyMixin],
    props: {
        homeCurrencyId: {
            required: true,
            type: Number,
        },
        dateOfBirth: {
            required: true,
            type: dayjs,
            default: null,
        },
        averageMonthlyWealthGain: {
            required: true,
            type: Number,
        },
        wealth: {
            required: true,
            type: Number,
        },
        minimumDaysReached: {
            required: true,
            type: Boolean,
        },
        wealthChangeMinimumDays: {
            required: true,
            type: Number,
        },
        settingsUrl: {
            required: true,
            type: String,
        }
    },
    data() {
        return {
            targetValue: null,
            selectedAge: this.getMinimumAge(),
            maximumAge: 70,
        }
    },
    computed: {
        minimumAge() {
            return this.getMinimumAge();
        },
        yearsToSelectedAge() {
            return this.selectedAge - this.minimumAge + 1;
        },
        monthsToSelectedAge() {
            return this.yearsToSelectedAge * 12;
        },
        projectedWealth() {
            return this.wealth + (this.averageMonthlyWealthGain * this.monthsToSelectedAge);
        },
        homeCurrency() {
            return this.findCurrency(this.homeCurrencyId);
        },
        showProjector() {
            return this.minimumDaysReached && this.dateOfBirth;
        },
    },
    methods: {
        age(date) {
            return this.$date(date).diff(this.dateOfBirth, 'year');
        },
        getMinimumAge() {
            return this.age(new Date()) + 1;
        },
    }
}
</script>
