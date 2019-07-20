import angular from 'angular';
import ngResource from 'angular-resource';
export default angular.module('Admininterface', ['ngResource']).service('Admininterface', ['$resource', function($resource) {
    let baseUrl = '../';
    return $resource(baseUrl, null, {
        changepassword: { // 用户更改密码
            method: 'post',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/common/changepassword`
        },
        getuserpermission: {
            method: 'get',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/common/permission`
        },
        getcoursesList: {
            method: 'get',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/courses/list`
        },
        savetpic: {
            method: 'post',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/courses/save`
        },
        gettopicsummary: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/topics/summary`
        },
        gettopicslist: {
            method: 'post',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/topics/list`
        },
        gettopicdetail: {
            method: 'get',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/topics/detail/:topicId`,
            topicId: '@topicId'
        },
        gettraineelist: {
            method: 'get',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/trainees/list`
        },
        getmytraineelist: {
            method: 'get',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/trainees/mylist`
        },
        gettrainingtopicslist: {
            method: 'post',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}rest/trainees/topicslist`
        },
        addtraining: {
            method: 'post',
            isArray: false,
            timeout: 4000,
            url: `${baseUrl}rest/training/add`
        },
        getresultdetail: {
            method: 'get',
            isArray: false,
            timeout: 4000,
            url: `${baseUrl}rest/training/resultdetail/:resultId`,
            resultId: '@resultId'
        },
        gettrainingslist: {
            method: 'post',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/training/list`
        },
        gettrainingDetail: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url:  `${baseUrl}rest/training/detail/:trainingId`,
            trainingId: '@trainingId'
        },
        gettrainingResult: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/training/result/:traineetrainingId`,
            traineetrainingId: '@traineetrainingId'
        },
        getmanualauditlist: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/manualaudit/list`
        },
        getmanualauditdetail: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/manualaudit/detail/:trainingresultId`,
            trainingresultId: '@trainingresultId'
        },
        auditanswer: {
            method: 'post',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/manualaudit/auditanswer`,
        },
        getuserlist: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/users/list`,
        },
        saveuser: {
            method: 'post',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/users/save`,
        },
        getuserdetail: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/users/detail/:userId`,
            userId: '@userId'
        },
        savetrainee: {
            method: 'post',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}rest/trainees/save`
        }
    });
}]);
