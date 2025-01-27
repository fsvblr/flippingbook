<template>
    <div
        id="flip-app"
        :class="{ 'has-mouse': hasMouse }"
        @touchstart="hasMouse = false"
    >
        <flipbook
            class="flipbook"
            :pages
            :forwardDirection
            :startPage="pageNum"
            v-slot="flipbook"
            ref="flipbook"
            @flip-left-start="onFlipLeftStart"
            @flip-left-end="onFlipLeftEnd"
            @flip-right-start="onFlipRightStart"
            @flip-right-end="onFlipRightEnd"
            @zoom-start="onZoomStart"
            @zoom-end="onZoomEnd"
        >
            <div class="action-bar">
                <double-left-icon
                    class="btn double-left"
                    :class="{ disabled: !flipbook.canFlipLeft }"
                    @click="this.clickDoubleLeftIcon()"
                />
                <left-icon
                    class="btn left"
                    :class="{ disabled: !flipbook.canFlipLeft }"
                    @click="flipbook.flipLeft"
                />
                <plus-icon
                    class="btn plus"
                    :class="{ disabled: !flipbook.canZoomIn }"
                    @click="flipbook.zoomIn"
                />
                <div v-if="this.forwardDirection === 'right'" class="page-num">
                    <span>{{ this.lang['Page'] }} {{ flipbook.page }}&nbsp;</span>
                    <span>{{ this.lang['of'] }} {{ flipbook.numPages }}</span>
                </div>
                <div v-if="this.forwardDirection === 'left'" class="page-num rtl">
                    <span>{{ flipbook.numPages }} {{ this.lang['of'] }}</span>
                    <span>&nbsp;{{ flipbook.page }} {{ this.lang['Page'] }}</span>
                </div>
                <minus-icon
                    class="btn minus"
                    :class="{ disabled: !flipbook.canZoomOut }"
                    @click="flipbook.zoomOut"
                />
                <right-icon
                    class="btn right"
                    :class="{ disabled: !flipbook.canFlipRight }"
                    @click="flipbook.flipRight"
                />
                <double-right-icon
                    class="btn double-right"
                    :class="{ disabled: !flipbook.canFlipRight }"
                    @click="this.clickDoubleRightIcon()"
                />
            </div>
        </flipbook>
        <FlippingbookSlider v-if="this.showPages===2"  class="hidden md:block"
            :thumbs :pageNumInSlider :forwardDirection @bookUpdate="setPageNum" />
    </div>
</template>

<script>
import 'vue-material-design-icons/styles.css'
import LeftIcon from 'vue-material-design-icons/ChevronLeftCircle.vue'
import DoubleLeftIcon from 'vue-material-design-icons/ChevronDoubleLeft.vue'
import RightIcon from 'vue-material-design-icons/ChevronRightCircle.vue'
import DoubleRightIcon from 'vue-material-design-icons/ChevronDoubleRight.vue'
import PlusIcon from 'vue-material-design-icons/PlusCircle.vue'
import MinusIcon from 'vue-material-design-icons/MinusCircle.vue'
import Flipbook from 'flipbook-vue'
import FlippingbookSlider from './FlippingbookSlider.vue'

export default {
    components: {
        Flipbook,
        LeftIcon, RightIcon, DoubleLeftIcon, DoubleRightIcon,
        PlusIcon, MinusIcon,
        FlippingbookSlider
    },
    data() {
        return {
            pages: [null],
            thumbs: [],
            publicationId: 0,
            forwardDirection: 'right',
            pageNum: 1,
            pageNumInSlider: 1,
            hasMouse: true,
            lang: [],
            showPages: 2,
        }
    },
    methods: {
        onFlipLeftStart(page) {
            //console.log('flip-left-start', page)
        },
        onFlipLeftEnd(page) {
            //console.log('flip-left-end', page)
            this.setHash(page)
            this.setPageFromHash()
        },
        onFlipRightStart(page) {
            //console.log('flip-right-start', page)
        },
        onFlipRightEnd(page) {
            //console.log('flip-right-end', page)
            this.setHash(page)
            this.setPageFromHash()
        },
        onZoomStart(zoom) {
            //console.log('zoom-start', zoom)
        },
        onZoomEnd(zoom) {
            //console.log('zoom-end', zoom)
        },
        setHash(n) {
            window.location.hash = '#' + n
        },
        setPageFromHash() {
            const n = parseInt(window.location.hash.slice(1), 10)
            if(isFinite(n)) {
                this.setPageNum(n, true)
            }
        },
        setPageNum(n, fromHash=false) {
            n = parseInt(n)
            if(this.$refs.flipbook.displayedPages === 2 && n % 2 !== 0 && n !== 1) {
                this.pageNum = n - 1
            } else {
                this.pageNum = n
            }
            let pageNumForSlider = fromHash ? this.pageNum : n
            this.setHash(pageNumForSlider)
            this.pageNumInSlider = pageNumForSlider
        },
        clickDoubleLeftIcon() {
            if(this.forwardDirection === 'right') {
                this.setPageNum(1)
            }
            if(this.forwardDirection === 'left') {
                this.setPageNum(this.$refs.flipbook.numPages)
            }
        },
        clickDoubleRightIcon() {
            if(this.forwardDirection === 'right') {
                this.setPageNum(this.$refs.flipbook.numPages)
            }
            if(this.forwardDirection === 'left') {
                this.setPageNum(1)
            }
        },
    },
    mounted() {
        let wrap = document.getElementById('flippingbook'),
            book = JSON.parse(wrap.getAttribute('data-book')),
            images = book.images
        this.publicationId = book.publication
        this.forwardDirection = book.direction
        this.lang = JSON.parse(wrap.getAttribute('data-lang'))
        this.showPages = this.$refs.flipbook.displayedPages
        images.forEach((img) => {
            this.pages.push('/storage/flippingbook/publications/' + this.publicationId + '/' + img)
            this.thumbs.push('/storage/flippingbook/publications/' + this.publicationId + '/thumbs/' + img)
        })

        window.addEventListener('keydown', (ev) => {
            const flipbook = this.$refs.flipbook
            if (!flipbook) return
            if (ev.key === 'ArrowLeft' && flipbook.canFlipLeft) flipbook.flipLeft()
            if (ev.key === 'ArrowRight' && flipbook.canFlipRight) flipbook.flipRight()
        })

        window.addEventListener('resize', (ev) => {
            const flipbook = this.$refs.flipbook
            if (!flipbook) return
            this.showPages = this.$refs.flipbook.displayedPages
        })

        this.setPageFromHash()
    },
}
</script>

<style>
.flippingbook-site .flippingbook-wrap {
    position: relative;
    width: 100%;
    max-width: 800px;
}
.flippingbook-site .flipbook .action-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 30px;
    margin-bottom: 20px;
    padding: 10px 0;
    width: 100%;
}
.flippingbook-site .flipbook .action-bar .btn {
    font-size: 30px;
    color: #666;
    cursor: pointer;
}
.flippingbook-site .flipbook .action-bar .btn svg {
    bottom: 0;
}
.flippingbook-site .flipbook .action-bar .btn:not(:first-child) {
    margin-left: 10px;
}
.flippingbook-site .flipbook .has-mouse .action-bar .btn:hover {
    color: #ccc;
    filter: drop-shadow(1px 1px 5px #000);
    cursor: pointer;
}
.flippingbook-site .flipbook .action-bar .btn:active {
    filter: none !important;
}
.flippingbook-site .flipbook .action-bar .btn.disabled {
    opacity: 0.4;
    pointer-events: none;
}
.flippingbook-site .flipbook .action-bar .page-num {
    font-size: 12px;
    margin-left: 10px;
}
.flippingbook-site .flipbook .viewport {
    height: 620px;
}
.flippingbook-site .flipbook .bounding-box {
    box-shadow: 0 0 20px #000;
}
@media screen and (max-width: 480px) {
    .flippingbook-site .flipbook .viewport {
        height: 530px;
    }
}
@media screen and (max-width: 400px) {
    .flippingbook-site .flipbook .action-bar .page-num {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 11px;
    }
    .flippingbook-site .flipbook .action-bar .page-num.rtl {
        flex-direction: column-reverse;
    }
    .flippingbook-site .flipbook .viewport {
        height: 500px;
    }
}
@media screen and (max-width: 360px) {
    .flippingbook-site .flipbook .viewport {
        height: 420px;
    }
}
</style>
