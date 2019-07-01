import systemmodule from './systemmodule';

export default systemmodule.controller('topicssummaryctl', ['$scope', 'Persist', '$state', function($scope, Persist, $state) {
    $scope.addtopic = (level, course = null, grade = null) => {
        $state.go('system.topics.add');
    }
}]);