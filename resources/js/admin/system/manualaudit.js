import systemModule from './systemmodule';

export default systemModule.controller('manualauditctl', ['$scope', 'toastr', 'auditList', '$uibModal', function ($scope, toastr, auditList, $uibModal) {
    $scope.auditList = auditList;

    $scope.auditanswer = answer => {
        let auditmodal = $uibModal.open({
            animation: true,
            size: 'lg',
            templateUrl: '../adminpages/system.trainings.auditmodal',
            controller: ['$scope', 'Admininterface', '$uibModalInstance', 'auditdetailData', 'toastr', function ($scope, Admininterface, $uibModalInstance, auditdetailData, toastr) {
                $scope.auditdetailData = auditdetailData;

                $scope.makecorrect = () => {
                    $scope.saveauditresult(true);
                };

                $scope.makewrong = () => {
                    $scope.saveauditresult(false);
                }

                $scope.saveauditresult = result => {
                    Admininterface.auditanswer({
                        trainingresultId: auditdetailData.trainingresultId,
                        result: result
                    }, response => {
                        if (response.result) {
                            toastr.success('审核成功', '操作成功');
                            $uibModalInstance.close(result);
                        }
                    });
                }

            }],
            resolve: {
                auditdetailData: ['Admininterface', function (Admininterface) {
                    return Admininterface.getmanualauditdetail({
                        trainingresultId: answer.result_id
                    }).$promise.then(response => response.result ? response.data : null);
                }]
            }
        });

        auditmodal.result.then(result => {
            let index = $scope.auditList.indexOf(answer);
            if (index > -1) {
                $scope.auditList.splice(index, 1);
            }
        }, () => {
            // dismissed
        });
    };
}])