import systemModel from '../systemmodule';

export default systemModel.controller('traineelistctl', ['$scope', 'traineesList', function($scope, traineesList) {
    $scope.traineesList = traineesList;
}]);