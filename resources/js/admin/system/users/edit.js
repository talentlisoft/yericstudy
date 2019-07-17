import systemModule from '../systemmodule';

export default systemModule.controller('editusersctl', ['$scope', 'Admininterface', 'userData', 'traineesList', 'toastr', '$state', function($scope, Admininterface, userData, traineesList, toastr, $state) {
    $scope.traineesList = traineesList;
    $scope.saving = false;
    if (!userData) {
        $scope.userData = {
            id: null,
            name: null,
            email: null,
            password: null,
            permissions: {
                checktopics: false,
                checkusers: false
            },
            selectedtrainees: []
        }
    } else {
        $scope.userData = userData;
    }

    $scope.istraineeselected = trainee => $scope.userData.selectedtrainees.find(tr => tr.id == trainee.id) ? true : false;

    $scope.selecttrainee = trainee => {
        let thistrainee = $scope.userData.selectedtrainees.find(tr => tr.id == trainee.id);
        if (!thistrainee) {
            $scope.userData.selectedtrainees.push(trainee);
        } else {
            let index = $scope.userData.selectedtrainees.indexOf(thistrainee);
            if (index > -1) {
                $scope.userData.selectedtrainees.splice(index, 1);
            }
        }
    };
    $scope.saveuser = () => {
        $scope.saving = true;
        Admininterface.saveuser($scope.userData, response => {
            if (response.result) {
                toastr.success('保存成功', '操作成功');
                $state.go('system.users.list');
            } else {
                $scope.saving = false;
            }
        }, () => {
            $scope.saving = false;
        });
    }
}]);