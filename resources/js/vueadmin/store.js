import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        levelList: [
            {'id': 'PSCHOOL', 'desc': '小学'},
            {'id': 'JHSCHOOL', 'desc': '初中'},
            {'id': 'SHCHOOL', 'desc': '高中'},
            {'id': 'COLLEDGE', 'desc': '大学'}
        ],
        gradeList: [
            {'id': '1', 'desc': '一年级'},
            {'id': '2', 'desc': '二年级'},
            {'id': '3', 'desc': '三年级'},
            {'id': '4', 'desc': '四年级'},
            {'id': '5', 'desc': '五年级'},
            {'id': '6', 'desc': '六年级'}
        ],
        selectedtopicsCondition: {
            grade: null,
            level: null,
            course: null
        }
    },
    mutations: {
        setselectedtopicsCondition(state, payload) {
            state.selectedtopicsCondition.grade = payload.grade;
            state.selectedtopicsCondition.level = payload.level;
            state.selectedtopicsCondition.course = payload.course;
        }
    }
});