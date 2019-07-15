import systemmodule from './systemmodule';

export default systemmodule.controller('trainingslistctl', ['$scope', '$state', 'Admininterface', 'trainingList', 'Persist', function($scope, $state, Admininterface, trainingList, Persist) {
    $scope.trainingList = trainingList;
    $scope.per = Persist.trainingList;
    $scope.addtraining = () => {
        $state.go('system.trainings.add');
    }

    $scope.gettraininglist = () => {
        Admininterface.gettrainingslist({
            searchcontent: $scope.per.searchcontent,
            page: $scope.per.currentPage
        }, response => {
            if (response.result) {
                $scope.per.total = response.data.total;
                $scope.trainingList = response.data.list;
            }
        });
    };
}]);