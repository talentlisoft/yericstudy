import systemmodule from './systemmodule';

export default systemmodule.controller('edittrainingctl', ['$scope', 'Persist', 'Admininterface', 'traineesList', 'trainingData', function($scope, Persist, Admininterface, traineesList, trainingData) {
    $scope.traineesList = traineesList;
    $scope.per = Persist;

    $scope.conditions = {
        selectedlevel: null,
        selectedgrade: null,
        selectedcourse: null,
        searchcontent: null,
        optionexpanded: true
    };
    if (!trainingData) {
        $scope.trainingData = {
            selectedtrainees: [],
            selectedtopics: []
        };
    }    
    $scope.selecttrainee = trainee => {
        if (!$scope.trainingData.selectedtrainees.find(tr => tr.id == trainee.id)) {
            $scope.trainingData.selectedtrainees.push(trainee);
        } else {
            let index = $scope.trainingData.selectedtrainees.indexOf(trainee);
            if (index > -1) {
                $scope.trainingData.selectedtrainees.splice(index, 1);
            }
        }
    };

    $scope.istraineeselected = trainee => {
        return $scope.trainingData.selectedtrainees.find(tr => tr.id == trainee.id) ? true : false;
    };
}]);