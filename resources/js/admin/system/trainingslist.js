import systemmodule from './systemmodule';

export default systemmodule.controller('trainingslistctl', ['$scope', '$state', 'Admininterface', 'trainintsList', function($scope, $state, Admininterface, trainintsList) {
    $scope.addtraining = () => {
        $state.go('system.trainings.add');
    }
}]);