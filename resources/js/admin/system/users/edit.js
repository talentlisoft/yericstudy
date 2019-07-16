import systemModule from '../systemmodule';

export default systemModule.controller('editusersctl', ['$scope', 'Admininterface', 'userData', 'traineesList', function($scope, Admininterface, userData, traineesList) {
    $scope.traineesList = traineesList;
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
    }

    $scope.istraineeselected = trainee => $scope.userData.selectedtrainees.find(tr => tr.id == trainee.id) ? true : false;

    $scope.selecttrainee = trainee => {
        if (!$scope.userData.selectedtrainees.find(tr => tr.id == trainee.id)) {
            $scope.userData.selectedtrainees.push(trainee);
        } else {
            let index = $scope.userData.selectedtrainees.indexOf(trainee);
            if (index > -1) {
                $scope.userData.selectedtrainees.splice(index, 1);
            }
        }
    };
}]);