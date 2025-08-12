<template>
    <div>
        <top-bar
            :home-url="homeUrl"
            :plans-url="plansUrl"
            :account-url="accountUrl"
            :app-name="appName"
            :initially-minimized="initiallyMinimized"
            include-burger-button="1"
            include-border-line="0"
            @minimize-switched="minimizeSwitch"
            :brand-src="brandSrc"
        ></top-bar>
        <div class="container pb-4">
            <div class="row">
                <div v-if="alertMessage !== ''" class="col-12 mb-4">
                    <div :class="['alert', 'alert-'+alertType]">
                        {{ alertMessage }}
                    </div>
                </div>
                <div v-else-if="Object.keys(errors).length > 0" class="col-12 mb-4">
                    <div class="alert alert-danger">
                        <div v-for="(errorArray, arrayIndex) in errors" :key="arrayIndex">
                            <div v-for="(error, index) in errorArray" :key="index">
                                {{ error }}
                            </div>
                        </div>
                    </div>
                </div>
                <div :class="[
                    'col-12',
                    isMinimized ? 'col-md-auto' : 'col-md-4',
                    isMinimized ? '' : 'col-lg-3',
                    isMinimized ? '' : 'col-xl-2',
                    'no-gutters',
                    'mb-4',
                    'mb-md-0',
                ]">
                    <side-bar
                        :current-url="currentUrl"
                        :wealth-url="wealthUrl"
                        :assets-url="assetsUrl"
                        :debts-url="debtsUrl"
                        :currencies-url="currenciesUrl"
                        :settings-url="settingsUrl"
                        :is-minimized="isMinimized"
                    ></side-bar>
                </div>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        currentUrl: {required: true, type: String},
        homeUrl: {required: true, type: String},
        wealthUrl: {required: true, type: String},
        plansUrl: {required: true, type: String},
        accountUrl: {required: true, type: String},
        currenciesUrl: {required: true, type: String},
        assetsUrl: {required: true, type: String},
        debtsUrl: {required: true, type: String},
        settingsUrl: {required: true, type: String},
        appName: {required: true, type: String},
        brandSrc: {required: true, type: String},
        alertMessage: {required: true, type: String},
        alertType: {required: true, type: String},
        errors: {required: true},
    },
    data() {
        return {
            initiallyMinimized: '0',
            isMinimized: false,
        }
    },
    methods: {
        minimizeSwitch(isMinimized) {
            this.isMinimized = isMinimized;
        }
    }
}
</script>
