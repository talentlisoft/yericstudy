import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios'
import VueAxios from 'vue-axios'

import systemComponent from './systemmain.vue';
import topicsSummary from './topicsSummary.vue'


const router = new VueRouter({
    mode: 'history',
    routes: [
        // 动态路径参数 以冒号开头
        {
            path: '/system', component: systemComponent,
            children: [
                {path: 'topicssummary', component: topicsSummary}

            ]
        }
    ]
})

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

const app = new Vue({
    router,
    el: '#studyapp'
})