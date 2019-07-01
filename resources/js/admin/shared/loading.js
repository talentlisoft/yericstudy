import angular from 'angular';

export default angular.module('loadinIndicator', []).
factory('ajaxloadCounter', [function() {
    return {
        counter: 0
    };
}]).
factory('loadingIndicator', ['$q', 'ajaxloadCounter', function($q, ajaxCounter) {
    return {
        'request': function(config) {
            ajaxCounter.counter++;
            return config;
        },
        'responseError': function(response) {
            ajaxCounter.counter--;
            return $q.reject(response);
        },
        'response': function(response) {
            ajaxCounter.counter--;
            return response;
        },
    }
}]).config(['$httpProvider', function ($httpProvider) {
    $httpProvider.interceptors.push('loadingIndicator');
}]).directive('showLoad', ['ajaxloadCounter', function(ajaxloadCounter) {
    return {
        restrict: 'E',
        replace: true,
        link: function(scope, element, attrs) {
            scope.ajaxCounter = ajaxloadCounter;
        },
        template: `<div class="loading-indicator" ng-show="ajaxCounter.counter>0"><i class="fa fa-spinner fa-pulse fa-fw"></i> 数据加载中...</div>`
    };
}]);