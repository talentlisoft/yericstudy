import systemModule from '../systemmodule';

export default systemModule.controller('userslistctl', ['$scope', 'usersList', 'Admininterface', '$state', function($scope, usersList, Admininterface, $state) {
    $scope.usersList = usersList;

    $scope.gotouserdetail = user => {
        
    };
}]);