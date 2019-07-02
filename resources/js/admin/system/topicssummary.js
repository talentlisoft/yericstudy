import systemmodule from './systemmodule';

export default systemmodule.controller('topicssummaryctl', ['$scope', 'Persist', '$state', 'summaryData', function ($scope, Persist, $state, summaryData) {
    $scope.summaryData = summaryData;
    $scope.per = Persist;
    $scope.addtopic = (level, course = null, grade = null) => {
        $state.go('system.topics.add');
    }

    $scope.getlevelname = levelId => {
        let level = Persist.shared.levelList.find(lv => lv.id == levelId);
        return level ? level.desc : null;
    }

    $scope.getlevelsum = su => su.data.reduce((lastvalue, current) => current.topics_count + lastvalue, 0);
    ;
}]);