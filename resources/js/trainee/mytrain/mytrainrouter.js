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

    $stateProvider.state('mytrain.mytrains.list', {
        url: '/list',
        templateUrl: `${baseUrl}traineepages/mytrain.list`,
        controller: 'mytrainlistctl',
        data: {
            pageTitle: '训练列表'
        },
        resolve: {
            trainList: ['Persist', 'Traineeinterface', function(Persist, Traineeinterface) {
                return Traineeinterface.getmytrainlist({
                    scope: Persist.mytrain.list.scope,
                    page: Persist.mytrain.list.currentPage
                }).$promise.then(response => {
                    if (response.result) {
                        Persist.mytrain.list.total = response.data.total;
                        return response.data.list;
                    } else {
                        return null;
                    }
                })
            }]
        }
    });

    $stateProvider.state('mytrain.mytrains.doexercise', {
        url: '/doexercise/:traineetrainingId',
        templateUrl: `${baseUrl}traineepages/mytrain.doExercise`,
        controller: 'doexercisectl',
        data: {
            pageTitle: '做训练'
        },
        resolve: {
            trainingData: ['$stateParams', 'Traineeinterface', function($stateParams, Traineeinterface) {
                return Traineeinterface.gettrainingdetail({
                    traineetrainingId: $stateParams.traineetrainingId
                }).$promise.then(response => response.result ? response.data : null);
            }]
        }
    });

    $stateProvider.state('mytrain.mytrains.result', {
        url: '/result/:traineetrainingId',
        templateUrl: `${baseUrl}traineepages/mytrain.result`,
        controller: 'trainresultctl',
        data: {
            pageTitle: '训练结果'
        },
        resolve: {
            resultData: ['$stateParams', 'Traineeinterface', function($stateParams, Traineeinterface) {
                return Traineeinterface.gettrainingresult({
                    traineetrainingId: $stateParams.traineetrainingId
                }).$promise.then(response => response.result ? response.data : null);
            }]
        }
    });
}]);