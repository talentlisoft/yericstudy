import systemModule from './systemmodule';

export default systemModule.controller('trainingdetailctl', ['$scope', '$state', 'trainingDetail', function($scope, $state, trainingDetail) {
    $scope.trainingDetail = trainingDetail;
    $scope.gotoresult = trainee => {
        $state.go('system.trainings.result', {
            traineetrainingId: trainee.traineetraining_id
        });
    }
}]);