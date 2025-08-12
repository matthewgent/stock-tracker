<template>
    <div v-if="!acknowledged" id="cookie-notice">
        <p>By using the {{ appName }} website you agree to the storage of cookies on your device.</p>
        <div class="button button-primary" @click="acknowledgedAction">OK</div>
    </div>
</template>

<script>
export default {
    props: ['appName', 'lifespan'],
    data() {
        return {
            cookieKey: 'allow-cookies',
            acknowledged: false,
        }
    },
    methods: {
        acknowledgedAction() {
            this.$cookies.set(
                this.cookieKey,
                1,
                this.lifespan + 'm'
            );

            // manually override this temporarily until page is reloaded
            this.acknowledged = true;
        },
    },
    mounted() {
        const stored = this.$cookies.isKey(this.cookieKey);
        const valueIsOne = this.$cookies.get(this.cookieKey) === '1';
        if (stored && valueIsOne) {
            this.acknowledged = true;
        }
    }
}
</script>
