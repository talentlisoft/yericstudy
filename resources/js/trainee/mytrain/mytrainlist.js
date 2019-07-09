import mytrainModule from './mytrainmodule';

export default mytrainModule.controller('mytrainlistctl', ['$scope', 'Traineeinterface', '$state', 'trainList', 'Persist', function($scope, Traineeinterface, $state, trainList, Persist) {
    $scope.per = Persist.mytrain.list;
    $scope.trainList = trainList;

    $scope.refreshlist = () => {
        $scope.per.currentPage = 1;
        $scope.getmytrainlist();
    };

    $scope.getmytrainlist = () => {
        Traineeinterface.getmytrainlist({
            scope: $scope.per.scope,
            page: $scope.per.currentPage
        }, response => {
            if (response.result) {
                $scope.trainList = response.data.list;
                $scope.per.total = response.data.total;
            }
        });
    };

    $scope.gotodetail = train => {
        if (train.status == 0) {
            $state.go('mytrain.mytrains.doexercise', {traineetrainingId: train.id});
        }
    };
}]);