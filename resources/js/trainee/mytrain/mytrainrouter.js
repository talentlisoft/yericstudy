import mytrainModule from './mytrainmodule';

export default mytrainModule.config(['$stateProvider', function ($stateProvider) {
    let baseUrl = '../'
    $stateProvider.state('mytrain', {
        url: '/mytrain',
        templateUrl: `${baseUrl}traineepages/mytrain`
    });

    $stateProvider.state('mytrain.mytrains', {
        url: '/mytrains',
        template: `<ui-view class="w-100 d-block uiview"></ui-view>`
    });
}]);