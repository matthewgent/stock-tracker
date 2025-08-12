<template>
    <div id="top-bar" :class="['nav', includeBorderLine === '1' ? 'lined' : '']">
        <div class="container h-100">
            <div class="row h-100 justify-content-between">
                <div class="col-auto d-flex align-items-center h-100">
                    <a class="burger" v-if="includeBurgerButton === '1'" v-on:click="minimizeSwitch">
                        <i class="fas fa-bars"></i>
                    </a>
                    <a :href="homeUrl" class="brand">
                        <img :src="brandSrc" alt="brand">
                        <span class="d-none d-md-block">{{ appName }}</span>
                    </a>
                </div>
                <div class="col-auto d-flex justify-content-end align-items-center h-100">
                    <div>
                        <a :href="plansUrl" class="premium button">
                            <i class="fas fa-star me-2"></i><span>Premium</span>
                        </a>
                    </div>
                    <div>
                        <a :href="accountUrl" class="account">
                            <i class="fas fa-user-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        homeUrl: {
            type: String,
            required: true,
        },
        plansUrl: {
            type: String,
            required: true,
        },
        accountUrl: {
            type: String,
            required: true,
        },
        appName: {
            type: String,
            required: true,
        },
        initiallyMinimized: {
            type: String,
            required: true,
        },
        includeBurgerButton: {
            type: String,
            required: true,
        },
        includeBorderLine: {
            type: String,
            required: true,
        },
        brandSrc: {
            required: true,
            type: String,
        }
    },
    data() {
        return {
            isMinimized: this.initiallyMinimized === '1',
        }
    },
    methods: {
        minimizeSwitch() {
            this.isMinimized = !this.isMinimized;
            this.$emit('minimize-switched', this.isMinimized);
        }
    },
}
</script>
