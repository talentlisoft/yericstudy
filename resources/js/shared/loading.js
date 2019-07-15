import angular from 'angular';
import angulartoastr from 'angular-toastr';
export default angular.module('interfaceInteractor', ['toastr']).factory('ajaxloadCounter', [function () {
    return {
        counter: 0
    };
}]).factory('loadingInteractor', ['ajaxloadCounter', '$injector', '$q', '$window', function (ajaxloadCounter, $injector, $q, $window) {
    let load_interactor = {
        'request': function (config) {
            ajaxloadCounter.counter++;
            return config;
        },
        'responseError': function (response) {
            ajaxloadCounter.counter--;
            let toastr = $injector.get('toastr');
            if (response.status == 422) {
                // 请求出错
                if (response.data.errors) {
                    let errorHtml = '';
                    for (let key in response.data.errors) {
                        if (response.data.errors.hasOwnProperty(key)) {
                            angular.forEach(response.data.errors[key], errorinfo => {
                                errorHtml += `<li>${errorinfo}</li>`;
                            })
                        }
                    }
                    toastr.error(errorHtml, '请检查以下字段数据', {
                        allowHtml: true
                    });
                }

            }
            if (response.status == 401) {
                // Session 过期
                $window.location.reload();
            }

            if (response.status == 500) {
                toastr.error('服务器打盹了', '500 错误');
            }

            if (response.status == 404) {
                toastr.error('页面被吹飞了~~', '404 错误');
            }

            if (response.status == 408) {
                toastr.error('服务器好像忙不过来了，请稍后再试', '超时错误');
            }
            return $q.reject(response);
        },
        'response': function (response) {
            if (response.data.result === false && response.data.errorinfo) {
                let toastr = $injector.get('toastr');
                toastr.warning(response.data.errorinfo, '出了一点小问题');
            }
            ajaxloadCounter.counter--;
            return response;
        }
    };
    return load_interactor;

}]).config(['$httpProvider', function ($httpProvider) {
    $httpProvider.interceptors.push('loadingInteractor');
}]).directive('loadIndicator', ['ajaxloadCounter', function (ajaxloadCounter) {
    return {
        restrict: 'E',
        replace: true,
        link: function (scope, element, attrs) {
            scope.ajaxCounter = ajaxloadCounter;
        },
        template: `<div class="loading-indicator" ng-show="ajaxCounter.counter>0"><i class="fa fa-spinner fa-pulse fa-fw"></i> 数据加载中...</div>`
    };
}]);
