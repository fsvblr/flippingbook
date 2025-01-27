<!-- https://vuetifyjs.com/en/components/sliders/ -->

<template>
    <div id="flip-app-pbar" class="flippingbook-slider flex flex-col items-center">
        <v-slider
            v-model="slider"
            :max="max"
            :min="min"
            :step="step"
            :reverse="reverse"
            class="page-slideer"
            hide-details
            thumb-label
            show-ticks="always"
            tick-size="4"
            @update:modelValue="updateSlider"
        >
            <template v-slot:append>
                <v-text-field
                    class="page-window"
                    v-model="slider"
                    density="compact"
                    type="number"
                    hide-details
                    single-line
                    :max="max"
                    :min="min"
                    :step="step"
                    @change="changeInput"
                ></v-text-field>
            </template>

            <template v-slot:thumb-label="{ modelValue }">
                {{ modelValue }}
            </template>
        </v-slider>
    </div>
</template>

<script>
export default {
    data() {
        return {
            min: 1,
            step: 1,
        }
    },
    props: {
        thumbs: {
            type: Array,
            default: []
        },
        pageNumInSlider: {
            type: Number,
            default: 1
        },
        forwardDirection: {
            validator(value, props) {
                return ['right', 'left'].includes(value)
            }
        },
    },
    emits: ['bookUpdate'],
    computed: {
        currentThumbImage() {
            let curThumbUrl = this.thumbs[this.slider - 1]
            return `url(${curThumbUrl})`
        },
        slider: {
            get() {
                return this.pageNumInSlider
            },
            set(value) {
                this.$emit('bookUpdate', value)
            }
        },
        max() {
            return this.thumbs.length;
        },
        reverse() {
            let reversOptions = {'right': false, 'left': true}
            return reversOptions[this.forwardDirection]
        },
    },
    methods: {
        updateSlider(value) {
            this.fireBookUpdate(value)
        },
        changeInput(event) {
            let value = event.target.value
            this.fireBookUpdate(value)
        },
        fireBookUpdate(value) {
            this.$emit('bookUpdate', value)
        }
    },
}
</script>

<style>
.flippingbook-site .flippingbook-slider {
    margin-top: 20px;
    text-align: center;
    width: 100%;
}
.flippingbook-site .flippingbook-slider .page-slideer {
    display: flex;
    justify-content: center;
    width: 100%;
}
.flippingbook-site .flippingbook-slider .page-slideer .v-input__control {
    width: 100%;
    max-width: 800px;
}
.flippingbook-site .flippingbook-slider .page-window {
    margin-inline-start: 32px;
    width: 50px;
}
.flippingbook-site .flippingbook-slider .page-window .v-field__input {
    padding: 8px;
}
.flippingbook-site .flippingbook-slider .page-window .v-field__field {
    border: 1px solid #999;
}
.flippingbook-site .flippingbook-slider .v-slider-thumb__label:after {
    content: '';
    background-color: #fff;
    background-image: v-bind(currentThumbImage);
    background-repeat: no-repeat;
    background-size: contain;
    background-position-y: bottom;
    display: inline-block;
    height: 75px;
    width: 50px; /* default 35px*/
    position: absolute;
    left: -7px;
    right: 0;
    bottom: 0;
}
</style>
