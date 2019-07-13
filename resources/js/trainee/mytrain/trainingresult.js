import mytrainModule from './mytrainmodule';

export default mytrainModule.controller('trainresultctl', ['$scope', 'resultData', function($scope, resultData) {
    $scope.resultData = resultData;

    $scope.getresulticon = answer => {
        switch (answer.status) {
            case null:
                return 'text-dark fa-clock-o';
                break;
            case 'CORRECT':
                return 'fa-check text-success';
                break;
            case 'WRONG':
                return 'fa-times text-danger';
            case 'PENDDING':
                return 'fa-question-circle text-dark';
                break;
            default:
                return null;
        }
    }

    $scope.getresultcolor = answer => answer.status == 'WRONG' ? 'table-warning': '';
}]);