<template>
    <div id="premium" class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-9 col-xl-8 col-xxl-7">
                <div class="plate plate-padding">
                    <form>
                        <h2 class="mb-3">Purchase Details</h2>

                        <div class="form-group row">
                            <div class="col-md-3">Account</div>
                            <div class="col-md-8">{{ emailAddress }}</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Product</div>
                            <div class="col-md-8">Premium subscription</div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">Cost</div>
                            <div class="col-md-8">
                                {{ moneyFormat(premiumPrice.amount, premiumPrice.currency.id) }}
                                ({{ premiumPrice.currency.code }})
                                per {{ premiumPrice.period }}
                            </div>
                        </div>

                        <h2 class="mb-3">Card Details</h2>

                        <div v-if="alertMessage !== ''" class="mb-4">
                            <div :class="['alert', 'alert-'+alertType]">
                                {{ alertMessage }}
                            </div>
                        </div>
                        <div v-else-if="Object.keys(errors).length > 0" class="mb-4">
                            <div class="alert alert-danger">
                                <div v-for="(errorArray, arrayIndex) in errors" :key="arrayIndex">
                                    <div v-for="(error, index) in errorArray" :key="index">
                                        {{ error }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row align-items-center">
                            <div class="col-md-3">Cardholder Name</div>
                            <div class="col-md-8">
                                <input
                                    v-model="cardholderName"
                                    id="card-holder-name"
                                    type="text"
                                    class="form-control"
                                    placeholder="John Smith"
                                >
                            </div>
                        </div>
                        <div class="form-group row align-items-center">
                            <div class="col-md-3">Card</div>
                            <div class="col-md-8">
                                <div
                                    id="card-element"
                                    class="form-control"
                                ></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-3">
                                <div class="button-holder">
                                    <a
                                        v-if="loading === false"
                                        id="card-button"
                                        @click="setupIntent"
                                        class="button-primary d-inline-block px-5"
                                    >
                                        Purchase
                                    </a>
                                    <div v-else class="spinner"></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form ref="processForm" method="post" :action="paymentUrl">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <input type="hidden" id="payment_method_id" name="payment_method_id">
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import money from "../mixins/money";
import themeColors from "../mixins/themeColors";

export default {
    mixins: [money, themeColors],
    props: {
        intentSecret: {
            required: true,
            type: String,
        },
        publicKey: {
            required: true,
            type: String,
        },
        paymentUrl: {
            required: true,
            type: String,
        },
        csrfToken: {
            required: true,
            type: String,
        },
        initialAlertMessage: {
            required: false,
        },
        initialAlertType: {
            required: false,
        },
        errors: {
            required: false,
        },
        emailAddress: {
            required: true,
            type: String,
        },
        premiumPrice: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            stripe: null,
            cardElement: null,
            cardholderName: '',
            loading: false,
            alertType: this.initialAlertType,
            alertMessage: this.initialAlertMessage,
        }
    },
    methods: {
        async setupIntent(e) {
            e.preventDefault();

            this.loading = true;

            if (this.cardholderName === '') {
                this.alertType = 'danger';
                this.alertMessage = 'Cardholder name is required.';
            } else {
                const cardHolderName = document.getElementById('card-holder-name');
                const clientSecret = this.intentSecret;

                const { setupIntent, error } = await this.stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: this.cardElement,
                            billing_details: { name: cardHolderName.value }
                        }
                    }
                );

                if (error) {
                    this.alertType = 'danger';
                    this.alertMessage = error.message;
                    this.loading = false;
                } else {
                    document.getElementById('payment_method_id').value = setupIntent.payment_method;
                    this.$refs.processForm.submit();
                }
            }
        }
    },
    mounted() {
        this.stripe = window.Stripe(this.publicKey);
        const elements = this.stripe.elements();
        this.cardElement = elements.create('card', {
            style: {
                base: {
                    fontWeight: '400',
                    fontFamily: 'Roboto, sans-serif',
                    fontSize: '1.2rem',
                },
            },
        });

        this.cardElement.mount('#card-element');
    }
}
</script>
