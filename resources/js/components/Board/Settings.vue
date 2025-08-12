<template>
    <div id="settings" class="col container overflow-hidden">
        <div class="row justify-content-center g-4">
            <div class="col-12 col-lg-10 col-xl-9 col-xxl-8">
                <div class="plate plate-padding">
                    <div class="plate-title">Settings</div>
                    <div class="plate-spacer"></div>
                    <form method="post" :action="updateSettingsUrl">
                        <input type="hidden" name="_method" value="patch">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div>
                            <label for="sovereign-state-select">Home country</label>
                            <select id="sovereign-state-select" name="country" class="form-select">
                                <option
                                    v-for="(sovereignState, index) in sovereignStates"
                                    :key="index"
                                    :value="sovereignState.id"
                                    :selected="sovereignState.id === homeSovereignState.id"
                                >
                                    {{ sovereignState.name }}
                                </option>
                            </select>
                        </div>
                        <div class="plate-spacer"></div>
                        <div class="form-group">
                            <label for="currency-select">Home currency</label>
                            <select id="currency-select" name="currency" class="form-select">
                                <option
                                    v-for="(currency, index) in currencies"
                                    :key="index"
                                    :value="currency.id"
                                    :selected="currency.id === homeCurrency.id"
                                >
                                    {{ currency.name }} ({{ currency.code }})
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input
                                id="date_of_birth"
                                type="date"
                                class="form-control"
                                v-model="dateOfBirthModel"
                                name="date_of_birth"
                            >
                        </div>
                        <div class="plate-spacer"></div>
                        <div class="text-right">
                            <input type=submit class="d-inline-block button button-primary" value="Save">
                        </div>
                    </form>
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
    props: [
        'currencies',
        'sovereignStates',
        'homeCurrency',
        'homeSovereignState',
        'updateSettingsUrl',
        'csrfToken',
        'dateOfBirth',
    ],
    data() {
        return {
            dateOfBirthModel: this.dateOfBirth,
        }
    }
}
</script>
