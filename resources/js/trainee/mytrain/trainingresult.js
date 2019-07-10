import mytrainModule from './mytrainmodule';

export default mytrainModule.controller('trainresultctl', ['$scope', 'resultData', function($scope, resultData) {
    $scope.resultData = resultData;
}]);