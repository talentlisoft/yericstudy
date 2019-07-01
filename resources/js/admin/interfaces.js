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
    });
}]);
