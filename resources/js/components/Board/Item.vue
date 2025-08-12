<template>
    <div id="item" class="col container overflow-hidden">
        <div class="row align-items-stretch g-4">
            <div class="col-12">
                <a :href="categoryUrl" class="d-inline-block button-grey">
                    <i class="fas fa-arrow-left"></i>
                    <span class="ms-2">{{ categoryStyles.pluralTitle }}</span>
                </a>
            </div>
            <div class="col-12 col-lg-5 col-xl-4">
                <div class="plate plate-padding">
                    <form v-if="itemStatus !== 2" ref="updateForm" method="post" :action="updateUrl">
                        <input type="hidden" name="_method" value="patch">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div v-if="itemStatus === 0" class="d-flex justify-content-between align-items-center">
                            <div class="plate-title">{{ itemName(item) }} {{ itemActive(item) ? '' : '('+typeStyles.inactiveTitle+')' }}</div>
                            <div class="title-buttons">
                                <a v-if="itemClass(item) !== 1" class="button-primary me-1" @click="itemStatus = 1">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a class="button-danger" @click="itemStatus = 2">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                        <div v-if="itemStatus === 1 && itemClass(item) === 0">
                            <h5 class="mb-1">Name:</h5>
                            <input class="form-control" type="text" name="name" :value="itemName(item)">
                        </div>
                        <div v-if="itemStatus === 0" >
                            <div class="plate-spacer"></div>
                            <h5 class="mb-1">Type:</h5>
                            <div class="d-flex align-items-center color-grey4">
                                <div class="plate-item-icon">
                                    <i :class="[categoryStyles.iconClass]"></i>
                                </div>
                                <div>{{ categoryStyles.singularTitle }}</div>
                            </div>
                        </div>
                        <div v-if="itemStatus === 0">
                            <div class="plate-spacer"></div>
                            <h5 class="mb-1">Category:</h5>
                            <div :class="['d-flex', 'align-items-center', 'color-'+typeName]">
                                <div class="plate-item-icon">
                                    <i :class="[typeStyles.iconClass]"></i>
                                </div>
                                <div>{{ typeStyles.singularTitle }}</div>
                            </div>
                        </div>
                        <div class="plate-spacer" v-if="itemClass(item) === 0 || itemStatus === 0"></div>
                        <div v-if="itemStatus === 0">
                            <h5 class="mb-1">Status:</h5>
                            <div :class="['d-flex', 'align-items-center', itemActive(item) ? 'color-active' : 'color-inactive']">
                                <div class="plate-item-icon">
                                    <i v-if="itemActive(item)" class="fas fa-check-circle"></i>
                                    <i v-else class="fas fa-times-circle"></i>
                                </div>
                                <div v-if="itemActive(item)">Owned</div>
                                <div v-else>
                                    {{ typeStyles.inactiveTitle }}
                                </div>
                            </div>
                        </div>
                    </form>

                    <div v-if="itemStatus === 1">
                        <div class="plate-spacer"></div>
                        <div class="d-flex justify-content-end align-items-center">
                            <a class="value-button button-grey me-3" @click="itemStatus = 0">Cancel</a>
                            <a class="value-button button-primary" @click="$refs.updateForm.submit()">Save</a>
                        </div>
                    </div>

                    <div v-if="itemStatus === 2">
                        <h2 class="text-center color-danger">Warning</h2>
                        <div class="plate-spacer"></div>
                        <h4>Deleting this {{ categoryStyles.singularTitle.toLowerCase() }} will erase all records of it.</h4>
                        <div class="plate-spacer"></div>
                        <h4>Only do this if you created this {{ categoryStyles.singularTitle.toLowerCase() }} by mistake.</h4>
                        <div class="plate-spacer"></div>
                        <h4>If you no longer own this {{ categoryStyles.singularTitle.toLowerCase() }}, then just mark it as "{{ typeStyles.inactiveTitle }}" instead.</h4>
                        <div class="plate-spacer"></div>
                        <div class="plate-spacer"></div>
                        <div class="d-flex justify-content-center align-items-center">
                            <a class="value-button button-grey me-3" @click="itemStatus = 0">Cancel</a>
                            <a class="value-button button-danger" @click="$refs.destroyForm.submit()">Delete</a>
                        </div>
                        <form ref="destroyForm" method="post" :action="destroyUrl">
                            <input type="hidden" name="_method" value="delete">
                            <input type="hidden" name="_token" :value="csrfToken">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 col-xl-8">
                <history
                    :items="[item]"
                    :home-currency-id="homeCurrencyId"
                    :currencies="currencies"
                    :has-premium="hasPremium"
                    @demo=""
                ></history>
            </div>
            <div v-if="itemClass(item) === 1" class="col-12 col-lg-6">
                <div class="plate plate-padding">
                    <div class="plate-title">{{ itemTypeStyles.stock.singularTitle }}</div>
                    <div class="plate-spacer"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Name:</div>
                        <div>{{ itemName(item) }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Symbol:</div>
                        <div>{{ item.stock.ticker.symbol }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Latest Price:</div>
                        <div>
                            <span v-if="homeCurrencyId !== item.stock.ticker.security_exchange.currency_id">
                                {{ stockFormat(latestStockPrice, item.stock.ticker.security_exchange.currency_id) }}
                                <i class="fas fa-long-arrow-alt-right mx-2"></i>
                            </span>
                            <span>
                                {{ stockFormat(exchangeAmount(
                                    latestStockPrice,
                                    item.stock.ticker.security_exchange.currency_id,
                                    homeCurrencyId
                                ), homeCurrencyId) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="itemClass(item) === 1" class="col-12 col-lg-6">
                <div class="plate plate-padding">
                    <div class="plate-title">Security Exchange</div>
                    <div class="plate-spacer"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Name:</div>
                        <div>{{ item.stock.ticker.security_exchange.name }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Abbreviation:</div>
                        <div>{{ item.stock.ticker.security_exchange.short_name }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Country:</div>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="me-2">{{ item.stock.ticker.security_exchange.sovereign_state.short_name }}</div>
                            <div class="ticker_flag">
                                <img
                                    :alt="item.stock.ticker.security_exchange.sovereign_state.short_name"
                                    :src="flagSrc(item.stock.ticker.security_exchange.sovereign_state)"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="plate">
                    <div class="plate-padding d-flex justify-content-between align-items-center">
                        <div v-if="itemClass(item) === 0" class="plate-title">Value Records</div>
                        <div v-if="itemClass(item) === 1" class="plate-title">Shares Records</div>
                        <div class="d-flex justify-content-end align-items-center">
                            <div class="button-primary" @click="addRecord()">Add record</div>
                            <div v-if="itemActive(item) && appropriateSnapshot(item) !== null" class="button-danger ms-3" @click="addZeroRecord()">Mark as {{ typeStyles.inactiveTitle }}</div>
                        </div>
                    </div>
                    <div>
                        <div v-show="createValueStatus > 0" class="plate-padding-x">
                            <form ref="createForm" method="post" :action="recordUrls.store">
                                <input type="hidden" name="_method" value="post">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <input v-if="itemClass(item) === 0" type="hidden" name="item_id" :value="item.id">
                                <input v-if="itemClass(item) === 1" type="hidden" name="stock_id" :value="item.stock.id">
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <input
                                            class="form-control"
                                            type="date"
                                            name="date"
                                            min="1970-01-01"
                                            :max="$date().format('YYYY-MM-DD')"
                                            :value="$date().format('YYYY-MM-DD')"
                                        >
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center">
                                        <h4 v-if="createValueStatus === 2 && itemClass(item) === 0" class="color-grey4">
                                            Value of {{ typeStyles.singularTitle }} will be set to zero.
                                        </h4>
                                        <h4 v-if="createValueStatus === 2 && itemClass(item) === 1" class="color-grey4">
                                            Shares owned of {{ typeStyles.singularTitle }} will be set to zero.
                                        </h4>
                                        <select
                                            v-if="itemClass(item) === 0"
                                            v-show="createValueStatus !== 2"
                                            class="form-select"
                                            name="currency"
                                        >
                                            <option
                                                v-for="(currency, index) in currencies"
                                                :key="index"
                                                :value="currency.id"
                                                :selected="isCurrencySelected(currency.id)"
                                            >
                                                {{ currency.code }} ({{ currency.symbol }})
                                            </option>
                                        </select>
                                        <input v-if="itemClass(item) === 0" v-show="createValueStatus !== 2" ref="createItemValueInput" class="form-control" type="number" name="value" placeholder="e.g. 789.50" autofocus>
                                        <div v-if="itemClass(item) === 1" v-show="createValueStatus !== 2" class="input-group">
                                            <input ref="createQuantityInput" class="form-control share_quantity_input" type="number" name="quantity" placeholder="e.g. 5" autofocus>
                                            <div class="input-group-append">
                                                <div class="input-group-text">shares</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="d-flex justify-content-end align-items-center pb-2">
                                <a class="value-button button-grey me-3" @click="createValueStatus = 0">Cancel</a>
                                <a class="value-button button-primary" @click="$refs.createForm.submit()">
                                    {{ createValueStatus === 2 ? 'Confirm' : 'Add' }}
                                </a>
                            </div>
                        </div>

                        <record
                            v-for="(record, index) in records"
                            :key="index"
                            :record="record"
                            :first="index === 0"
                            :active="itemActive(item)"
                            :inactive-title="typeStyles.inactiveTitle"
                            :class-code="itemClass(item)"
                            :home-currency-id="homeCurrencyId"
                            :currencies="currencies"
                            :urls="recordUrls"
                            :csrf-token="csrfToken"
                        ></record>

                        <h3 v-if="itemClass(item) === 0 && item.item_values.length === 0 && createValueStatus !== 1" class="text-center my-2">
                            Create a value record to get started!
                        </h3>
                        <!--<h3 v-if="itemClass(item) === 1 && item.stock.stock_quantities.length === 0 && createValueStatus !== 1" class="text-center my-2">
                            Add a shares record to get started!
                        </h3>-->
                    </div>
                    <div class="plate-spacer"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import items from "../../mixins/items";
import flag from "../../mixins/flag";
import money from "../../mixins/money";
import currency from "../../mixins/currency";

export default {
    mixins: [items, flag, money, currency],
    props: [
        'item',
        'homeCurrencyId',
        'currencies',
        'itemUrls',
        'recordUrls',
        'csrfToken',
        'itemTypes',
        'hasPremium',
        'categoryUrl',
    ],
    data() {
        return {
            createValueStatus: 0,
            itemStatus: 0,
            zeroRecordSwitch: false,
        }
    },
    computed: {
        homeCurrency() {
            this.findCurrency(this.homeCurrencyId);
        },
        updateUrl() {
            return this.itemUrls.update;
        },
        destroyUrl() {
            return this.itemUrls.destroy;
        },
        typeName() {
            return this.item.item_type.name;
        },
        categoryStyles() {
            return this.itemCategoryStyles[this.item.item_type.item_category.name];
        },
        typeStyles() {
            return this.itemTypeStyles[this.typeName];
        },
        records() {
            let records = this.item.item_values;
            if (this.itemClass(this.item) === 1) {
                records = this.item.stock.stock_quantities;
            }
            return records;
        },
        latestStockPrice() {
            let price = 0;
            if (this.itemClass(this.item) === 1) {
                price = this.item.stock.ticker.chart_prices[0].price;
                price *= this.item.stock.ticker.security_exchange.price_coefficient;
            }
            return price;
        },
    },
    methods: {
        addRecord() {
            this.createValueStatus = 1;
            this.$nextTick(function () {
                if (this.itemClass(this.item) === 0) {
                    this.$refs.createItemValueInput.value = '';
                    this.$refs.createItemValueInput.focus();
                } else {
                    this.$refs.createQuantityInput.value = '';
                    this.$refs.createQuantityInput.focus();
                }
            });
        },
        addZeroRecord() {
            this.createValueStatus = 2;
            if (this.itemClass(this.item) === 0) {
                this.$refs.createItemValueInput.value = '0';
            } else {
                this.$refs.createQuantityInput.value = '0';
            }
        },
        isCurrencySelected(currencyId) {
            const snapshot = this.appropriateSnapshot(this.item);
            const chosenCurrencyId = snapshot !== null ? snapshot.currencyId : this.homeCurrencyId;

            return currencyId === chosenCurrencyId;
        }
    },
}
</script>
