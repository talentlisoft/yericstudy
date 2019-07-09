import angular from 'angular';
import ngresource from 'angular-resource';

export default angular.module('TraineeInterfaces', ['ngResource']).service('Traineeinterface', ['$resource', function($resource) {
    let baseUrl = '../';
    return $resource(baseUrl, null, {
        getmytrainlist: {
            method: 'post',
            isArray: false,
            timeout: 2000,
            url: `${baseUrl}resttrainee/mytrain/list`
        },
        gettrainingdetail: {
            method: 'get',
            isArray: false,
            timeout: 3000,
            url: `${baseUrl}resttrainee/mytrain/detail/:traineetrainingId`,
            traineetrainingId: '@traineetrainingId'
        },
        submitanswer: {
            method: 'post',
            isArray: false,
            timeout: 4000,
            url: `${baseUrl}resttrainee/mytrain/submitanswer`,
        }
    });
}]);
