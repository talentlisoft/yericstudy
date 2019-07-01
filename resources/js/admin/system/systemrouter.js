import systemmodule from './systemmodule';

export default systemmodule.config(['$stateProvider', '$locationProvider', function ($stateProvider, $locationProvider) {
    let baseUrl = '../';

    $stateProvider.state('system', {
        url: '/system',
        templateUrl: `${baseUrl}adminpages/system`
    });

    $stateProvider.state('system.topics', {
        url: '/topics',
        template: `<ui-view class="w-100 d-block uiview"></ui-view>`
    });

    $stateProvider.state('system.topics.summary', {
        url: '/summary',
        templateUrl: `${baseUrl}adminpages/system.topicssummary`,
        controller: 'topicssummaryctl'
    });

    $stateProvider.state('system.topics.add', {
        url: '/add',
        templateUrl: `${baseUrl}adminpages/system.edittopic`,
        controller: 'edittopicctl'
    });
}]);