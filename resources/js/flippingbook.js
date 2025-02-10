import { createApp } from 'vue'
import Flippingbook from './components/Flippingbook.vue'

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
const vuetify = createVuetify({
    defaults: {
        VSlider: {
            //
        },
    }
});

const flippingbookApp = createApp(Flippingbook)
flippingbookApp.use(vuetify)
flippingbookApp.mount('#flippingbook')
