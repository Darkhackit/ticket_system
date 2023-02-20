import './bootstrap';

import {createApp} from "vue";
import vSelect from 'vue-select'
import {createPinia} from "pinia";
import router from "./route";
import VueGravatar from 'vue3-gravatar'
import App from './App.vue'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import VueGoodTablePlugin from 'vue-good-table-next';
import 'vue-select/dist/vue-select.css';
import 'vue-good-table-next/dist/vue-good-table-next.css'

const pinia = createPinia()


let app = createApp(App)
    app.use(pinia)
    app.use(router)
    app.use(VueGravatar)
    app.use(VueGoodTablePlugin)
    app.component('QuillEditor', QuillEditor)
    app.component('v-select',vSelect)
    app.mount('#app')
