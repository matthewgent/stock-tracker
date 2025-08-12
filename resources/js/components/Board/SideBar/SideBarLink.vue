<template>
    <li :class="currentUrl === url ? 'active' : ''">
        <a :href="url">
            <i :class="iconClass"></i>
            <span :class="{ 'd-none': minimized }">
                <slot></slot>
            </span>
        </a>
    </li>
</template>

<script>
export default {
    props: {
        minimized: {
            type: Boolean,
            required: true,
        },
        url: {
            type: String,
            required: true,
        },
        iconClass: {
            type: String,
            required: true,
        },
        currentUrl: {
            required: true,
            type: String,
        }
    }
}
</script>
