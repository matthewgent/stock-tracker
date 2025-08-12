<template>
    <div :class="['plate-padding-x', status === 0 ? 'value-row-initial' : '']">
        <!-- Show -->
        <div v-if="status !== 2" @click="status = 1" class="d-flex justify-content-between align-items-center py-2">
            <div>
                <span>{{ $date(record.time).format('YYYY-MM-DD') }}</span>
                <span v-if="first && !active" class="mx-2 color-danger">({{ inactiveTitle }})</span>
            </div>
            <div v-if="classCode === 0" class="text-right">
                <span v-if="homeCurrencyId !== record.currency_id">
                    {{ moneyFormat(record.value, record.currency_id) }}
                    <i class="fas fa-long-arrow-alt-right mx-2"></i>
                </span>
                <span>{{ moneyFormat(exchangeAmount(record.value, record.currency_id, homeCurrencyId), homeCurrencyId) }}</span>
            </div>
            <div v-if="classCode === 1" class="text-right">
                <span>{{ record.quantity }} share{{ record.quantity !== 1 ? 's' : ''}}</span>
            </div>
        </div>
        <!-- Update -->
        <form ref="updateForm" method="post" :action="updateUrl">
            <input type="hidden" name="_method" value="patch">
            <input type="hidden" name="_token" :value="csrfToken">
            <div v-if="status === 2" class="d-flex justify-content-between align-items-center py-2">
                <div>
                    <input
                        class="form-control"
                        type="date"
                        :value="$date(record.time).format('YYYY-MM-DD')"
                        name="date"
                        min="1970-01-01"
                        :max="$date().format('YYYY-MM-DD')"
                    >
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <select v-if="classCode === 0" class="form-select" name="currency">
                        <option
                            v-for="(currency, index) in currencies"
                            :key="index"
                            :value="currency.id"
                            :selected="currency.id === record.currency_id"
                        >
                            {{ currency.code }} ({{ currency.symbol }})
                        </option>
                    </select>
                    <input v-if="classCode === 0" class="form-control" type="number" name="value" :value="record.value">
                    <div v-if="classCode === 1" class="input-group">
                        <input class="form-control share_quantity_input" type="number" name="quantity" :value="record.quantity">
                        <div class="input-group-append">
                            <div class="input-group-text">shares</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div v-if="status === 1" class="d-flex justify-content-end align-items-center pb-2">
            <a class="value-button button-grey me-3" @click="status = 0">Cancel</a>
            <a class="value-button button-primary me-3" @click="status = 2">Edit</a>
            <a class="value-button button-danger" @click="status = 3">Delete</a>
        </div>
        <div v-if="status === 2" class="d-flex justify-content-end align-items-center pb-2">
            <a class="value-button button-grey me-3" @click="status = 0">Cancel</a>
            <a class="value-button button-primary" @click="$refs.updateForm.submit()">Save</a>
        </div>
        <div v-else-if="status === 3" class="d-flex justify-content-end align-items-center pb-2">
            <div class="me-3">
                Are you sure you want to delete this {{ classCode === 0 ? 'value record' : 'quantity record' }}?
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <a class="value-button button-grey me-3" @click="status = 0">No</a>
                <a class="value-button button-danger" @click="$refs.deleteForm.submit()">Yes</a>
            </div>
        </div>

        <form ref="deleteForm" method="post" :action="destroyUrl">
            <input type="hidden" name="_method" value="delete">
            <input type="hidden" name="_token" :value="csrfToken">
        </form>
    </div>
</template>

<script>
import itemsMixin from '../../../mixins/items';
import moneyMixin from '../../../mixins/money';
import currencyMixin from "../../../mixins/currency";

export default {
    mixins: [itemsMixin, moneyMixin, currencyMixin],
    props: {
        record: {
            required: true,
            type: Object,
        },
        classCode: {
            required: true,
            type: Number,
        },
        homeCurrencyId: {
            required: true,
            type: Number,
        },
        currencies: {
            required: true,
            type: Array,
        },
        urls: {
            required: true,
            type: Object,
        },
        csrfToken: {
            required: true,
            type: String,
        },
        first: {
            required: true,
            type: Boolean,
        },
        active: {
            required: true,
            type: Boolean,
        },
        inactiveTitle: {
            required: true,
            type: String,
        }
    },
    data() {
        return {
            /*
             * Statuses:
             * 0 - Display
             * 1 - Edit or delete selection
             * 2 - Edit form
             * 3 - Delete form
             */
            status: 0,
        }
    },
    computed: {
        destroyUrl() {
            return this.urls.destroy.replace('#', this.record.id);
        },
        updateUrl() {
            return this.urls.update.replace('#', this.record.id);
        },
        homeCurrency() {
            return this.findCurrency(this.homeCurrencyId);
        }
    },
}
</script>
