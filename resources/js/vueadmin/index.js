import Vue from 'vue';
import axios from 'axios'
import VueAxios from 'vue-axios'
import store from './store';
import router from './router';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

Vue.use(VueAxios, axios);
// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

const app = new Vue({
    router,
    store: store,
    el: '#studyapp'
})