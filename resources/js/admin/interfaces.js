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
        }
    });
}]);
