<template>
    <div :class="['input-group', isInvalid ? 'is-invalid' : '']">
        <input id="password" :type="passwordType" name="password" :class="['form-control', isInvalid ? 'is-invalid' : '']" required autocomplete="new-password">
        <div class="input-group-append">
            <button @click="switchPassword" class="btn btn-primary" type="button">{{ buttonText }}</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isInvalid: {
            required: false,
        }
    },
    data() {
        return {
            passwordType: 'password',
            buttonText: 'Show',
        }
    },
    methods: {
        switchPassword() {
            if (this.passwordType === 'password') {
                this.passwordType = 'text';
                this.buttonText = 'Hide';
            } else {
                this.passwordType = 'password';
                this.buttonText = 'Show';
            }
        }
    }
}
</script>
