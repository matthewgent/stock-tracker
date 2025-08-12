<template>
    <div id="category" class="col container overflow-hidden">
        <div class="row align-items-stretch g-4">
            <div class="col-12 col-xl-6">
                <div class="plate plate-padding">
                    <div class="plate-title">Categories</div>

                    <div class="row align-items-center">
                        <div class="col-12 col-md-6">
                            <canvas v-if="allItems.length > 0" id="category-pie-chart"></canvas>
                            <h3 v-else class="text-center my-2">
                                Create some {{ categoryName }}s below to get started.
                            </h3>
                        </div>
                        <div class="col-12 col-md-6">
                            <div
                                v-for="(type, index) in typesByCategorySortedByValue(categoryName)"
                                :key="index"
                                class="d-flex justify-content-between align-items-center"
                            >
                                <div class="d-flex align-items-center">
                                    <div class="plate-item-icon">
                                        <i :class="[itemTypeStyles[type.name].iconClass, 'color-' + type.name]"></i>
                                    </div>
                                    <div>{{ itemTypeStyles[type.name].pluralTitle }}</div>
                                </div>
                                <div>
                                    {{ Math.round(typeValueAsPercentageOfCategory(type.name, categoryName)) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <history
                    :items="itemsByCategory(categoryName, allItems)"
                    :home-currency-id="homeCurrencyId"
                    :currencies="currencies"
                    :has-premium="hasPremium"
                ></history>
            </div>
            <div class="col-12">
                <div class="plate">
                    <div class="plate-padding d-flex justify-content-between align-items-center">
                        <div class="plate-title">
                            {{ itemCategoryStyles[categoryName].pluralTitle }}
                        </div>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="button button-grey mx-3" @click="showDisposed = !showDisposed">
                                {{ showHide }} historic
                            </div>
                            <a class="button button-primary" @click="createItemStatus = 1">
                                <i class="fas fa-plus me-1"></i>
                                <span>Create {{ categoryName }}</span>
                            </a>
                        </div>
                    </div>
                    <create-item
                        v-if="createItemStatus === 1"
                        :url="storeItemUrl"
                        :plans-url="plansUrl"
                        :csrf-token="csrfToken"
                        :types="typesByCategory(this.categoryName)"
                        :free-stocks="freeStocks"
                        :stocks-owned="stocksOwned"
                        :has-premium="hasPremium"
                        :api-token="apiToken"
                        @close-create-item="createItemStatus = 0"
                        :flag-urls="flagUrls"
                        :currencies="currencies"
                    ></create-item>
                    <div
                        v-for="(type, index) in typesByCategorySortedByValue(categoryName)"
                        :key="index"
                    >
                        <div v-if="itemsByType(type.name, items).length > 0">
                            <div
                                :class="[
                                    'plate-padding-x',
                                    'py-0',
                                    'd-flex',
                                    'justify-content-between',
                                    'align-items-center',
                                    'background-color-' + type.name,
                                    'color-white'
                                ]"
                            >
                                <div class="d-flex align-items-center">
                                    <div class="plate-item-icon">
                                        <i :class="itemTypeStyles[type.name].iconClass"></i>
                                    </div>
                                    <div>{{ itemTypeStyles[type.name].pluralTitle }}</div>
                                </div>
                                <div>{{ moneyFormat(itemsValue(itemsByType(type.name, currentItems), homeCurrencyId), homeCurrencyId) }}</div>
                            </div>
                            <a
                                v-for="(item, index) in itemsSortedByValue(itemsByType(type.name, currentItems))"
                                :key="index"
                                :class="[
                                    'plate-padding-x',
                                    'py-2',
                                    'd-flex',
                                    'justify-content-between',
                                    'align-items-center',
                                    itemActive(item) ? '' : 'color-grey2',
                                    'background-hover-theme-faint',
                                ]"
                                :href="specificItemUrl(item.id)"
                            >
                                <div>
                                    <div>{{ itemName(item) }} {{ itemActive(item) ? '' : '(' + itemTypeStyles[item.item_type.name].inactiveTitle + ')' }}</div>
                                    <h4 v-if="itemClass(item) === 1" class="color-grey2">
                                        {{ item.stock.stock_quantities[0].length === 0 ? 0 : item.stock.stock_quantities[0].quantity }} share{{ (item.stock.stock_quantities[0].length === 0 ? 0 : item.stock.stock_quantities[0].quantity) === 1 ? '' : 's' }}
                                    </h4>
                                </div>
                                <div class="text-right" v-if="appropriateSnapshot(item)">
                                    <div>
                                    <span v-if="homeCurrencyId !== appropriateSnapshot(item).currencyId">
                                        {{
                                            moneyFormat(itemValue(item, appropriateSnapshot(item).currencyId), appropriateSnapshot(item).currencyId)
                                        }}
                                        <i class="fas fa-long-arrow-alt-right mx-2"></i>
                                    </span>
                                        <span>{{
                                                moneyFormat(itemValue(item, homeCurrencyId), homeCurrencyId)
                                            }}</span>
                                    </div>
                                    <h6 class="color-grey2">
                                        {{ daysAgoFormat(item) }}
                                    </h6>
                                </div>
                                <div class="text-right" v-else>
                                    <div>{{ moneyFormat(0, homeCurrencyId) }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <h3 v-if="allItems.length === 0 && createItemStatus !== 1" class="text-center my-2">
                        Create an {{ categoryName }} to get started!
                    </h3>
                    <div class="plate-spacer"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// mixins
import itemsMixin from '../../mixins/items';
import moneyMixin from '../../mixins/money';
import currencyMixin from "../../mixins/currency";

// libraries
import Chart from "chart.js/auto";

export default {
    mixins: [itemsMixin, moneyMixin, currencyMixin],
    props: [
        'categoryName',
        'homeCurrencyId',
        'itemTypes',
        'itemUrl',
        'plansUrl',
        'storeItemUrl',
        'csrfToken',
        'hasPremium',
        'items',
        'freeStocks',
        'stocksOwned',
        'apiToken',
        'flagUrls',
    ],
    data() {
        return {
            showDisposed: false,
            createItemStatus: 0,
        }
    },
    computed: {
        showHide() {
            let showHide = 'Show';
            if (this.showDisposed) {
                showHide = 'Hide';
            }
            return showHide;
        },
        allItems() {
            return this.itemsByCategory(this.categoryName, this.items);
        },
        currentItems() {
            return this.allItems.filter(item => this.itemActive(item) === true);
        },
        itemsShown() {
            let itemsShown = this.allItems;
            if (!this.showDisposed) {
                itemsShown = this.currentItems;
            }
            return itemsShown.sort( (item1, item2) =>
                this.itemValue(item2, this.homeCurrencyId) - this.itemValue(item1, this.homeCurrencyId)
            );
        },
    },
    methods: {
        test(item) {
            console.log(item);
            return '';
        },
        specificItemUrl(id) {
            return this.itemUrl.replace('#', id);
        },
        createPieChart(labels, percentages, colors) {
            new Chart(
                document.getElementById('category-pie-chart'),
                {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: percentages,
                            backgroundColor: colors,
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                boxPadding: 4,
                                titleFont: {
                                    size: 40,
                                    weight: undefined,
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.formattedValue + '%';
                                    }
                                }
                            }
                        }
                    }
                }
            );
        },
        typeValueAsPercentageOfCategory(type, category) {
            const typeValue = this.itemsValue(this.itemsByType(type, this.currentItems), this.homeCurrencyId);
            const categoryValue = this.itemsValue(this.itemsByCategory(category, this.currentItems), this.homeCurrencyId);
            return categoryValue === 0 ? 0 : 100 * typeValue / categoryValue;
        },
        typesByCategorySortedByValue(category) {
            const types = this.typesByCategory(category, this.currentItems);
            for (const type of types) {
                type.homeValue = this.itemsValue(this.itemsByType(type.name, this.currentItems), this.homeCurrencyId)
            }
            const sortedTypes = types.sort( (a, b) =>
                b.homeValue - a.homeValue
            );
            return sortedTypes;
        },
        itemsSortedByValue(items) {
            for (const item of items) {
                item.homeValue = this.itemValue(item, this.homeCurrencyId)
            }
            return items.sort( (a, b) =>
                b.homeValue - a.homeValue
            );
        },
        daysAgoFormat(item) {
            const itemValue = this.appropriateSnapshot(item);
            const daysAgo = this.$date().diff(itemValue.date, 'day');
            let daysAgoFormatted;
            if (daysAgo === 0) {
                daysAgoFormatted = 'today';
            } else if (daysAgo === 1) {
                daysAgoFormatted = 'yesterday';
            } else {
                daysAgoFormatted = daysAgo + ' days ago';
            }
            return daysAgoFormatted;
        }
    },
    mounted() {
        if (this.allItems.length > 0) {
            const labels = Array.from(
                this.typesByCategorySortedByValue(this.categoryName),
                (type) => this.itemTypeStyles[type.name].pluralTitle
            );
            const percentages = Array.from(
                this.typesByCategorySortedByValue(this.categoryName),
                (type) => Math.round(this.typeValueAsPercentageOfCategory(type.name, this.categoryName))
            );
            const colors = Array.from(
                this.typesByCategorySortedByValue(this.categoryName),
                (type) => this.itemTypeStyles[type.name].color
            );
            this.createPieChart(
                labels,
                percentages,
                colors
            );
        }
    }
}
</script>
