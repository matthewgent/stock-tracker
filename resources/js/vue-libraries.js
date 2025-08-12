import Vue from 'vue';

import dayjs from 'dayjs';
Vue.use(require('vue-cookies'));
Object.defineProperty(Vue.prototype, '$date', { value: dayjs });

import VueCarousel from 'vue-carousel';
Vue.use(VueCarousel);

window.Vapor = require('laravel-vapor');
Vue.mixin({
    methods: {
        asset: window.Vapor.asset
    },
});
