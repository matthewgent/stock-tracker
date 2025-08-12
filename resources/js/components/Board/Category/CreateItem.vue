<template>
    <div>
        <form ref="createForm" method="post" :action="url">
            <input type="hidden" name="_method" value="post">
            <input type="hidden" name="_token" :value="csrfToken">
            <div class="plate-padding-x d-flex justify-content-between align-items-center py-2">
                <div>
                    <select class="form-select" name="category" v-model="typeId">
                        <option
                            v-for="(type, index) in types"
                            :key="index"
                            :value="type.id"
                            :selected="index === 0"
                        >
                            {{ itemTypeStyles[type.name].singularTitle }}
                        </option>
                    </select>
                </div>
                <div>
                    <input
                        v-if="selectedType.id !== 10 || stockTrackingType !== 'automatic'"
                        class="form-control"
                        type="text"
                        name="name"
                        :placeholder="'e.g. ' + itemTypeStyles[selectedType.name].exampleName"
                    >
                </div>
            </div>
            <div v-if="selectedType.id === 10">
                <div class="plate-padding-x mt-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="stock_tracking_type"
                            id="stock_tracking_type_manual"
                            v-model="stockTrackingType"
                            value="manual"
                        >
                        <label class="form-check-label" for="stock_tracking_type_manual">
                            Manually enter values
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="stock_tracking_type"
                            id="stock_tracking_type_automatic"
                            v-model="stockTrackingType"
                            value="automatic"
                            :disabled="!hasPremium && stocksOwned >= freeStocks"
                        >
                        <label class="form-check-label" for="stock_tracking_type_automatic">
                            Track automatically
                        </label>
                    </div>
                    <h5 v-if="!hasPremium" :class="['color-grey4', 'mt-2', !automaticAvailable ? 'mb-2' : '']">
                        Your basic plan includes automatic tracking of {{ freeStocks }}
                        {{ freeStocks === 1 ? itemTypeStyles.stock.singularTitle : itemTypeStyles.stock.pluralTitle }}.
                        Upgrade to <a :href="plansUrl" class="link-primary text-decoration-underline">premium</a> for unlimited.
                    </h5>
                </div>

                <div v-if="stockTrackingType === 'automatic'">
                    <form @submit.prevent="searchTickers">
                        <div class="plate-padding-x mt-3 row g-0 justify-content-between align-items-stretch">
                            <div class="col-9 col-md-10 col-xl-11 pe-3">
                                <input
                                    class="form-control"
                                    type="text"
                                    v-model="search"
                                    placeholder="e.g. Apple or S&P 500"
                                >
                            </div>
                            <div class="col-3 col-md-2 col-xl-1 text-center">
                                <div v-if="!loading" @click="searchTickers" class="button-primary h-100 p-0 d-flex justify-content-center align-items-center">
                                    <div>Search</div>
                                </div>
                                <div v-else class="spinner"></div>
                            </div>
                        </div>
                    </form>
                    <div class="search_box my-3">
                        <input type="hidden" name="ticker" :value="selectedTicker">
                        <div
                            v-for="(ticker, index) in tickers"
                            :key="index"
                            class="security-row plate-padding-x"
                            @click="trackTicker(ticker.id)"
                        >
                            <div class="d-flex align-items-center">
                                <div class="fw-bold me-2">{{ ticker.symbol }}</div>
                                <div>{{ ticker.name }}</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-2">{{ ticker.security_exchange.short_name }}</div>
                                <div class="ticker_flag">
                                    <img
                                        :alt="ticker.security_exchange.sovereign_state.short_name"
                                        :src="flagSrc(ticker.security_exchange.sovereign_state)"
                                    >
                                </div>
                            </div>
                        </div>
                        <h3 v-if="tickers.length === 0" class="color-theme text-center p-3">No results found</h3>
                    </div>
                </div>
            </div>
        </form>

        <div
            v-if="selectedType.id !== 10 || stockTrackingType !== 'automatic'"
            class="plate-padding-x d-flex justify-content-end align-items-center pb-2"
        >
            <a class="value-button button-grey me-3" @click="$emit('close-create-item')">Cancel</a>
            <a class="value-button button-primary" @click="$refs.createForm.submit()">Create</a>
        </div>
    </div>
</template>

<script>
// mixins
import items from "../../../mixins/items";
import flag from "../../../mixins/flag";

export default {
    mixins: [items, flag],
    props: {
        url: {
            required: true,
            type: String,
        },
        csrfToken: {
            required: true,
            type: String,
        },
        types: {
            required: true,
            type: Array,
        },
        freeStocks: {
            required: true,
            type: Number,
        },
        stocksOwned: {
            required: true,
            type: Number,
        },
        hasPremium: {
            required: true,
            type: Boolean,
        },
        apiToken: {
            required: true,
            type: String,
        },
        plansUrl: {
            required: true,
            type: String,
        },
    },
    data() {
        return {
            typeId: this.types[0].id,
            stockTrackingType: 'manual',
            tickers: [],
            selectedTicker: '',
            search: '',
            storeUrl: this.url,
            loading: false,
        };
    },
    computed: {
        selectedType() {
            return this.types.find(type => type.id === this.typeId);
        },
        automaticAvailable() {
            return this.hasPremium || (this.stocksOwned < this.freeStocks);
        }
    },
    methods: {
        trackTicker(tickerId) {
            this.selectedTicker = tickerId;
            this.$nextTick(function () {
                this.$refs.createForm.submit();
            });
        },
        searchTickers(event) {
            if (this.search.toString().length >= 2) {
                const that = this;
                this.loading = true;
                axios.get('/api/member/tickers', {
                    headers: {
                        Authorization: 'Bearer '+that.apiToken,
                        Accept: 'application/json',
                    },
                    params: {
                        search: that.search,
                    },
                })
                .then(function (response) {
                    that.tickers = response.data;
                })
                .catch(function () {

                })
                .then(function () {
                    that.loading = false;
                });
            }
        }
    },
    mounted() {
        if (this.automaticAvailable) {
            this.stockTrackingType = 'automatic';
        }
    }
}
</script>
