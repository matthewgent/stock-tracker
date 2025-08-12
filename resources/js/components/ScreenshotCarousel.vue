<template>
    <carousel
        :autoplay="true"
        :autoplay-timeout="3000"
        :per-page="1"
        :pagination-active-color="palette.theme1"
    >
        <slide v-for="(image, index) in images" :key="index" class="text-center">
            <img class="screenshot-image" :src="image" alt="carousel-image">
        </slide>
    </carousel>
</template>

<script>
import themeColors from "../mixins/themeColors";
import { Carousel, Slide } from 'vue-carousel';

export default {
    mixins: [themeColors],
    components: {Carousel, Slide},
    props: {
        images: { required: true, type: Array },
    }
};
</script>
