import appmodule from './appmodule';

export default appmodule.factory('Persist', [function() {
    return {
        edittopic: {
            selectedcourse: null,
            selectedlevel: null,
            selectedgrade: null,
            selectedtype: null
        },
        topicsList: {
            optionexpanded: true,
            selectedcourse: null,
            selectedlevel: null,
            selectedgrade: null,
            selectedtype: null,
            total: 0
        },
        trainingList: {
            total: 0,
            currentPage: 1,
            searchcontent: null
        },
        shared: {
            coursesList: null,
            traineesList: null,
            topictypesList:null,
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
            ]
        }
    };
}]);