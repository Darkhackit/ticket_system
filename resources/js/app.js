import './bootstrap';

import {createApp} from "vue";
import vSelect from 'vue-select'
import router from "./route";
import App from './App.vue'
import moveEditor from 'mavon-editor'
import VueGoodTablePlugin from 'vue-good-table-next';
import 'mavon-editor/dist/css/index.css'
import 'vue-select/dist/vue-select.css';
import 'vue-good-table-next/dist/vue-good-table-next.css'



let app = createApp(App)
    app.use(router)
    app.use(VueGoodTablePlugin)
    app.use(moveEditor)
    app.component('v-select',vSelect)
    app.mount('#app')
