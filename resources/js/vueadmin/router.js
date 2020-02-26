import Vue from 'vue';
import VueRouter from 'vue-router';
import systemComponent from './systemmain.vue';
import topicsSummary from './topicsSummary.vue';
import topicsListComponent from './topicslist.vue';
import edittopicComponent from './edittopic.vue';

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
                {path: 'topics/list', component: topicsListComponent},
                {path: 'topics/addnew', component: edittopicComponent}

            ]
        }
    ]
})

Vue.use(VueRouter);