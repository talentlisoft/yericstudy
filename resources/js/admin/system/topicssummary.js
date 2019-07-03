import systemmodule from './systemmodule';

export default systemmodule.controller('topicssummaryctl', ['$scope', 'Persist', '$state', 'summaryData', function ($scope, Persist, $state, summaryData) {
    $scope.summaryData = summaryData;
    $scope.per = Persist;
    $scope.addtopic = (level, course = null, grade = null) => {

        Persist.edittopic.selectedlevel = Persist.shared.levelList.find(lv => lv.id == level);

        Persist.edittopic.selectedcourse = Persist.shared.coursesList.find(so => so.id == course);

        Persist.edittopic.selectedgrade = Persist.shared.gradeList.find(gr => gr.id == grade);


        $state.go('system.topics.add');
    }

    $scope.getlevelname = levelId => {
        let level = Persist.shared.levelList.find(lv => lv.id == levelId);
        return level ? level.desc : null;
    }

    $scope.getlevelsum = su => su.data.reduce((lastvalue, current) => current.topics_count + lastvalue, 0);;

    $scope.gotolist = (level, course = null, grade = null) => {
        $state.go('system.topics.list');
    };
}]);
