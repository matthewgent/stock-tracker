<template>
    <div id="side-bar" class="py-2 px-3">
        <ul :class="[
            'list-unstyled',
            isMinimized ? 'd-flex' : 'd-block',
            isMinimized ? 'justify-content-between' : '',
            'd-md-block',
        ]">
            <side-bar-link
                :current-url="currentUrl"
                :url="wealthUrl"
                icon-class="fas fa-chart-bar"
                :minimized="isMinimized"
            >
                Wealth
            </side-bar-link>
            <side-bar-link
                :current-url="currentUrl"
                :url="assetsUrl"
                icon-class="fas fa-piggy-bank"
                :minimized="isMinimized"
            >
                Assets
            </side-bar-link>
            <side-bar-link
                :current-url="currentUrl"
                :url="debtsUrl"
                icon-class="fas fa-hand-holding-usd"
                :minimized="isMinimized"
            >
                Debts
            </side-bar-link>
            <side-bar-link
                :current-url="currentUrl"
                :url="currenciesUrl"
                icon-class="fas fa-exchange-alt"
                :minimized="isMinimized"
            >
                Currencies
            </side-bar-link>
            <li :class="['separator', isMinimized ? 'd-none' : '', 'd-md-block']"></li>
            <side-bar-link
                :current-url="currentUrl"
                :url="settingsUrl"
                icon-class="fas fa-cog"
                :minimized="isMinimized"
            >
                Settings
            </side-bar-link>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        wealthUrl: {required: true, type: String},
        assetsUrl: {required: true, type: String},
        debtsUrl: {required: true, type: String},
        currenciesUrl: {required: true, type: String},
        settingsUrl: {required: true, type: String},
        currentUrl: {required: true, type: String},
        isMinimized: {required: true, type: Boolean},
    },
}
</script>
