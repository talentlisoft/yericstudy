import systemmodule from './systemmodule';

export default systemmodule.controller('trainingslistctl', ['$scope', '$state', 'Admininterface', 'trainingList', function($scope, $state, Admininterface, trainingList) {
    $scope.trainingList = trainingList;
    $scope.addtraining = () => {
        $state.go('system.trainings.add');
    }
}]);