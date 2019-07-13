import mytrainModule from './mytrainmodule';

export default mytrainModule.controller('trainresultctl', ['$scope', 'resultData', '$uibModal', function($scope, resultData, $uibModal) {
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
    },

    $scope.answerdetail = answer => {
        let detaildlg = $uibModal.open({
            animation: true,
            size: 'lg',
            templateUrl: '../traineepages/mytrain.answerdetail',
            controller: ['$scope', 'answerDetail', '$uibModalInstance', function($scope, answerDetail, $uibModalInstance) {
                $scope.answerDetail = answerDetail;
                $scope.getanswerlist = () => answerDetail.answer ? answerDetail.answer.split('|') : null;
                $scope.close = () => {
                    $uibModalInstance.close(null);
                };
            }],
            resolve: {
                answerDetail: ['Traineeinterface', function(Traineeinterface) {
                    return Traineeinterface.getanswerdetail({
                        resultId: answer.result_id
                    }).$promise.then(response => response.result ? response.data : null);
                }]
            }
        });

        detaildlg.result.then(result => {

        }, () => {
            // Canceled
        });
    };

    $scope.getresultcolor = answer => answer.status == 'WRONG' ? 'table-warning': '';
}]);