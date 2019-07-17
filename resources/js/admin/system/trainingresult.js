import systemModule from './systemmodule';

export default systemModule.controller('trainingresultctl', ['$scope', '$state', 'resultData', '$uibModal', function($scope, $state, resultData, $uibModal) {
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

    $scope.answerdetail = answer => {
        if (answer.result_id) {
            let detaildlg = $uibModal.open({
                animation: true,
                size: 'lg',
                templateUrl: '../adminpages/system.trainings.answerdetail',
                controller: ['$scope', 'answerDetail', '$uibModalInstance', '$state', function($scope, answerDetail, $uibModalInstance, $state) {
                    $scope.answerDetail = answerDetail;
                    $scope.getanswerlist = () => answerDetail.answer ? answerDetail.answer.split('|') : null;
                    $scope.close = () => {
                        $uibModalInstance.close(null);
                    };
                    $scope.edittopic = () => {
                        $uibModalInstance.close(null);
                        $state.go('system.topics.detail', {topicId: answerDetail.topic_id});
                    };
                }],
                resolve: {
                    answerDetail: ['Admininterface', function(Admininterface) {
                        return Admininterface.getresultdetail({
                            resultId: answer.result_id
                        }).$promise.then(response => response.result ? response.data : null);
                    }]
                }
            });
    
            detaildlg.result.then(result => {
    
            }, () => {
                // Canceled
            });
        }
    };

    $scope.getresultcolor = answer => answer.status == 'WRONG' ? 'table-warning': '';
}])