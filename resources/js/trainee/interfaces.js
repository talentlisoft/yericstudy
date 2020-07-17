import angular from 'angular';
import ngresource from 'angular-resource';

export default angular.module('TraineeInterfaces', ['ngResource']).service('Traineeinterface', ['$resource', function($resource) {
    let baseUrl = '../';
    return $resource(baseUrl, null, {
        getmytrainlist: {
            method: 'post',
            isArray: false,
            timeout: 10000,
            url: `${baseUrl}resttrainee/mytrain/list`
        },
        gettrainingdetail: {
            method: 'get',
            isArray: false,
            timeout: 10000,
            url: `${baseUrl}resttrainee/mytrain/detail/:traineetrainingId`,
            traineetrainingId: '@traineetrainingId'
        },
        submitanswer: {
            method: 'post',
            isArray: false,
            timeout: 10000,
            url: `${baseUrl}resttrainee/mytrain/submitanswer`,
        },
        gettrainingresult: {
            method: 'get',
            isArray: false,
            timeout: 10000,
            url: `${baseUrl}resttrainee/mytrain/result/:traineetrainingId`,
        },
        getanswerdetail: {
            method: 'get',
            isArray: false,
            timeout: 10000,
            url: `${baseUrl}resttrainee/mytrain/result/detail/:resultId`,
            resultId: '@resultId'
        }
    });
}]);
