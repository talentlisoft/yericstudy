import systemModule from './systemmodule';

export default systemModule.controller('trainingresultctl', ['$scope', '$state', 'resultData', function($scope, $state, resultData) {
    $scope.resultData = resultData;
}])