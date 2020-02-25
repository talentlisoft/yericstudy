import Vue from 'vue';
import VueRouter from 'vue-router';
import systemComponent from './systemmain.vue';
import topicsSummary from './topicsSummary.vue';
import topicsList from './topicslist.vue';

// const systemComponent = () => import('./systemmain.vue'/* webpackChunkName: "js/systemmain" */);
// const topicsSummary = () => import('./topicsSummary.vue'/* webpackChunkName: "js/topicssummary" */);


export default new VueRouter({
    mode: 'history',
    routes: [
        // 动态路径参数 以冒号开头
        {
            path: '/system', component: systemComponent,
            children: [
                {path: 'topics/summary', component: topicsSummary},
                {path: 'topics/list', component: topicsList}

            ]
        }
    ]
})

Vue.use(VueRouter);