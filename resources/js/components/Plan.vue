<template>
    <div id="plan" class="container">
        <div class="title">Choose your plan.</div>
        <div class="options row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="option-basic">
                    <h1>Basic</h1>
                    <div class="description d-flex justify-content-center align-items-center">
                        <div class="text-end me-3">
                            Assets and Debts:
                            <br>
                            {{ itemTypeStyles.stock.pluralTitle }}:
                        </div>
                        <div>
                            {{ freeItems }}
                            <br>
                            {{ freeStocks }}
                        </div>
                    </div>
                    <div class="price">Free</div>
                    <div class="per-year"></div>
                    <div class="action">
                        <a v-if="isMember && hasPremium" :href="accountUrl" class="button-primary">Select</a>
                        <div v-else-if="isMember && !hasPremium" class="button-disabled">Owned</div>
                        <a v-else :href="registerUrl" class="button-primary">Select</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="option-premium">
                    <h1>Premium</h1>
                    <div class="description d-flex justify-content-center align-items-center">
                        <div class="text-end me-3">
                            Assets and Debts:
                            <br>
                            {{ itemTypeStyles.stock.pluralTitle }}:
                        </div>
                        <div>
                            Unlimited
                            <br>
                            Unlimited
                        </div>
                    </div>
                    <div class="price">
                        {{ moneyFormat(premiumPrice.amount, premiumPrice.currency.id) }}
                    </div>
                    <div class="per-year">per {{ premiumPrice.period }}</div>
                    <div class="action">
                        <div v-if="isMember && hasPremium" class="button-disabled">Owned</div>
                        <a v-else-if="isMember && !hasPremium" :href="premiumUrl" class="button-background">Select</a>
                        <a v-else :href="registerUrl" class="button-background">Select</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// mixins
import money from "../mixins/money";
import items from "../mixins/items";

export default {
    mixins: [money, items],
    props: {
        premiumPrice: {
            required: true,
            type: Object,
        },
        hasPremium: {
            required: true,
            type: Boolean,
        },
        isMember: {
            required: true,
            type: Boolean,
        },
        loginUrl: {
            required: true,
            type: String,
        },
        premiumUrl: {
            required: true,
            type: String,
        },
        freeItems: {
            required: true,
            type: Number,
        },
        freeStocks: {
            required: true,
            type: Number,
        },
        accountUrl: {
            required: true,
            type: String,
        },
        registerUrl: {
            required: true,
            type: String,
        }
    },
}
</script>
