import systemmodule from './systemmodule';

export default systemmodule.controller('edittrainingctl', ['$scope', 'Persist', 'Admininterface', 'traineesList', 'trainingData', function($scope, Persist, Admininterface, traineesList, trainingData) {
    $scope.traineesList = traineesList;
    $scope.per = Persist;
    $scope.total = 0;

    $scope.conditions = {
        selectedlevel: null,
        selectedgrade: null,
        selectedcourse: null,
        searchcontent: null,
        optionexpanded: true,
        mode: 'RADOM',
        currentPage: 1,
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

    $scope.gettopicslist = () => {
        Admininterface.gettrainingtopicslist({
            level: $scope.conditions.selectedlevel ? $scope.conditions.selectedlevel.id : null,
            trade: $scope.conditions.selectedgrade ? $scope.conditions.selectedgrade.id : null,
            course: $scope.conditions.selectedcourse ? $scope.conditions.selectedcourse.id : null,
            searchcontent: $scope.conditions.searchcontent,
            mode: $scope.conditions.mode,
            page: $scope.conditions.currentPage
        }, response => {
            if (response.result) {
                $scope.topicsList = response.data.list;
                $scope.total = response.data.total;
            }
        });
    };
    
    $scope.gettopicslist();

    $scope.refreshtopiclist = () => {
        $scope.conditions.currentPage = 1;
        $scope.gettopicslist();
    }
}]);