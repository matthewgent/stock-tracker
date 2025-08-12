<template>
    <div id="member" class="col container overflow-hidden">
        <div class="row justify-content-center g-4">
            <div class="col-12 col-lg-10 col-xl-9 col-xxl-8">
                <div class="plate plate-padding">
                    <div class="plate-title">Account Details</div>
                    <div class="plate-spacer"></div>
                    <form method="post" :action="updateMemberUrl">
                        <input type="hidden" name="_method" value="patch">
                        <input type="hidden" name="_token" :value="csrfToken">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email address</label>
                            <div class="col-md-7">
                                <input v-model="emailInput" id="email" type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-7">
                                <input v-model="passwordInput" ref="password" id="password" type="password" name="password" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">Repeat password</label>
                            <div class="col-md-7">
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-4">
                                <input
                                    type=submit
                                    :disabled="!emailChanged && !passwordChanged"
                                    class="d-inline-block button button-primary"
                                    :value="buttonText"
                                >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-lg-10 col-xl-9 col-xxl-8">
                <div class="plate plate-padding">
                    <div class="plate-title">My Plan</div>
                    <div class="plate-spacer"></div>
                    <div class="row align-items-center">
                        <div class="col-md-4">Current plan</div>
                        <div class="col-md-8">
                            <div v-if="hasPremium">
                                <div class="premium">Premium</div>
                            </div>
                            <div v-else class="d-flex justify-content-between align-items-center">
                                <div>Basic Free</div>
                                <a :href="plansUrl" class="button-primary">Get Premium</a>
                            </div>
                        </div>
                    </div>
                    <div v-if="hasPremium && !onGracePeriod">
                        <div class="plate-spacer"></div>
                        <div class="row">
                            <div class="col-md-4">Payments</div>
                            <div class="col-md-7">
                                {{ moneyFormat(premiumPrice.amount, premiumPrice.currency.id) }}
                                per
                                {{ premiumPrice.period }}
                            </div>
                        </div>
                        <div class="plate-spacer"></div>
                        <div class="row">
                            <div class="col-md-4">Next payment date</div>
                            <div class="col-md-7">
                                {{ isoDateString(premiumPeriodEnd) }}
                            </div>
                        </div>
                        <div class="plate-spacer"></div>
                        <div class="row">
                            <div class="col-md-7 offset-md-4">
                                <a
                                    :href="cancelPremiumUrl"
                                    class="d-inline-block button-danger"
                                >Cancel</a>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="hasPremium && onGracePeriod">
                        <div class="plate-spacer"></div>
                        <div class="row">
                            <div class="col-md-7 offset-md-4 color-danger">
                                Subscription ending on {{ isoDateString(premiumPeriodEnd) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-10 col-xl-9 col-xxl-8">
                <div class="plate plate-padding">
                    <div class="plate-title">Delete Account</div>
                    <div class="plate-spacer"></div>
                    <button v-if="deleteStatus === 0" @click="deleteStatus = 1" class="button button-danger">I want to delete my account</button>
                    <div v-if="deleteStatus === 1">
                        <div>This action will permanently delete all data on your account and cannot be undone. Are you sure you wish to to proceed?</div>
                        <div class="d-flex justify-content-end align-items-center">
                            <a class="d-inline-block button button-grey me-3" @click="deleteStatus = 0">No</a>
                            <form method="post" :action="destroyMemberUrl">
                                <input type="hidden" name="_method" value="delete">
                                <input type="hidden" name="_token" :value="csrfToken">
                                <input type="submit" value="Yes" class="button button-danger">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-10 col-xl-9 col-xxl-8">
                <div class="text-right">
                    <form method="post" :action="logoutUrl">
                        <input type="hidden" name="_method" value="post">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <input type="submit" value="Logout" class="button button-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import money from "../../mixins/money";

export default {
    mixins: [money],
    props: [
        'csrfToken',
        'emailAddress',
        'updateMemberUrl',
        'destroyMemberUrl',
        'logoutUrl',
        'hasPremium',
        'premiumPeriodEnd',
        'premiumPrice',
        'plansUrl',
        'cancelPremiumUrl',
        'onGracePeriod',
    ],
    data() {
        return {
            emailInput: this.emailAddress,
            passwordInput: '',
            deleteStatus: 0,
        }
    },
    computed: {
        emailChanged() {
            return this.emailInput !== this.emailAddress;
        },
        passwordChanged() {
            return this.passwordInput !== '';
        },
        buttonText() {
            let text = 'Update';
            if (this.emailChanged && this.passwordChanged) {
                text = 'Update Email & Password';
            } else if (this.emailChanged) {
                text = 'Update Email';
            } else if (this.passwordChanged) {
                text = 'Update Password';
            }
            return text;
        },
    },
    methods: {
        isoDateString(inputString) {
            const date = this.$date(inputString);
            return date.format('D MMMM YYYY');
        }
    }
}
</script>
