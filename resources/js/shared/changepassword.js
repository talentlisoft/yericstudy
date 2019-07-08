import angular from 'angular';
import toastr from 'angular-toastr';
import focusme from './focusme';
import uibootstrap from 'ui-bootstrap4';

export default angular.module('AdminchangePassword', ['ui.bootstrap', 'toastr', 'Admininterface', 'focusme']).directive('togglePassdialog', ['$uibModal', function($uibModal) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('click', () => {
                let changepassdlg = $uibModal.open({
                    animation: true,
                    templateUrl: '../adminpages/share.changePasswordDialog',
                    controller: 'changepassdlgctl'
                });

                changepassdlg.result.then(function(result) {

                }, function() {
                    //canceled
                });
            });
        }
    };
}]).controller('changepassdlgctl', ['$scope', '$uibModalInstance', 'toastr', 'Admininterface', function ($scope, $uibModalInstance, toastr, CRMInterface) {
    let passwordtestregx = [/[0-9]/, /[a-z]/, /[A-Z]/, /[^A-Z-0-9]/i];
    $scope.oldpassword = '';
    $scope.newpassword ='';
    $scope.confirmpassword = '';

    $scope.cancel = () => {
        $uibModalInstance.dismiss({
            $value: 'cancel'
        });
    };

    $scope.getpasswordScore = () => {
        let result = 0;
        if ($scope.newpassword) {
            if ($scope.newpassword.length >= 6) {
                for (let i in passwordtestregx) {
                    if (passwordtestregx[i].test($scope.newpassword)) {
                        result ++;
                    }
                }
            }
        }
        return result;
    };

    $scope.isconfirmok = () =>  ($scope.getpasswordScore() >=3 && ($scope.newpassword === $scope.confirmpassword));

    $scope.ok = () => {
        if ($scope.getpasswordScore() >= 3) {
            if ($scope.newpassword === $scope.confirmpassword) {
                CRMInterface.changepassword({
                    'oldpassword': $scope.oldpassword,
                    'newpassword': $scope.newpassword
                }, function(response) {
                    if (response.result === true) {
                        toastr.success('密码更改成功', '操作成功');
                        $uibModalInstance.close(true);
                    }
                });
            } else {
                toastr.warning('确认密码与新密码不相符！', '有点问题');
            }

        } else {
            toastr.warning('密码至少需要6位字符并且包含大写与小写和数字！', '有点问题');
        }
    };

}]);
