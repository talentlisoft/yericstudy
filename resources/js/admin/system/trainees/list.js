import systemModel from '../systemmodule';

export default systemModel.controller('traineelistctl', ['$scope', 'traineesList', '$uibModal', function($scope, traineesList, $uibModal) {
    $scope.traineesList = traineesList;

    let opentraineedlg = (traineeItem, successcallback) => {
        let traineedlg = $uibModal.open({
            animation: true,
            size: 'lg',
            templateUrl: '../adminpages/system.trainees.traineedialog',
            controller: ['$scope', 'traineeDetail', '$uibModalInstance', 'Admininterface', 'toastr', function($scope, traineeDetail, $uibModalInstance, Admininterface, toastr) {
                $scope.saving = false;
                if (!traineeDetail) {
                    $scope.traineeDetail = {
                        id: null,
                        name: null,
                        avatar: null,
                        password: null
                    };
                } else {
                    $scope.traineeDetail = traineeDetail;
                    $scope.traineeDetail.password = 'nochange';
                }
                $scope.cancel = () => {
                    $uibModalInstance.dismiss(null);
                }

                $scope.save = () => {
                    $scope.saving = true;
                    Admininterface.savetrainee($scope.traineeDetail, response => {
                        if (response.result) {
                            toastr.success('保存成功', '操作成功');
                            $uibModalInstance.close({
                                id: response.data.id,
                                name: $scope.traineeDetail.name,
                                avatar: $scope.traineeDetail.avatar
                            });
                        }
                        $scope.saving = false;
                    }, () => {
                        $scope.saving = false;
                    });
                };
            }],
            resolve: {
                traineeDetail: [function() {
                    return angular.copy(traineeItem);
                }]
            }
        });
        traineedlg.result.then(result => {
            successcallback(result);
        }, () => {
            // dismissed
        });
    };

    $scope.gotouserdetail = traineeItem => {
        opentraineedlg(traineeItem, updatedtrainee => {
            let index = $scope.traineesList.indexOf(traineeItem);
            if (index > -1) {
                $scope.traineesList.splice(index, 1, updatedtrainee);
            }
        });
    };

    $scope.addtrainee = () => {
        opentraineedlg(null, newtraineeItem => {
            $scope.traineesList.splice(0, 0, newtraineeItem);
        });
    }
}]);