import Vue from 'vue';
import axios from 'axios'
import VueAxios from 'vue-axios'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import { ValidationProvider, extend, ValidationObserver } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

import store from './store';
import router from './router';
import loadingComponent from './loading.vue';


Vue.component('load-indicator', loadingComponent);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
extend('required', {
    ...required,
    message: 'This field is required'
  });

Vue.use(VueAxios, axios);
// Install BootstrapVue
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);

export default new Vue({
    router,
    store: store,
    el: '#studyapp'
});