import systemmodule from './systemmodule';
import persist from '../persist';

export default systemmodule.controller('topicslistctl', ['$scope', 'Admininterface', 'topicsList', 'Persist', 'coursesList', '$state', function($scope, Admininterface, topicsList, Persist, coursesList, $state) {
    $scope.topicsList = topicsList;
    $scope.coursesList = coursesList;
    $scope.selectedtype = null;
    $scope.searchcontent = null;
    $scope.per = Persist;
    $scope.currentPage = 1;

    $scope.researchlist = () => {
        $scope.currentPage = 1;
        $scope.search();
    };


    $scope.gettypedesc = topic => {
        return `${Persist.shared.levelList.find(lv => lv.id == topic.level).desc}${topic.grade}年级（${topic.course_name}${topic.topic_type}）`;
    };

    $scope.search = () => {
        Admininterface.gettopicslist({
            level: Persist.topicsList.selectedlevel ? Persist.topicsList.selectedlevel.id : null,
            grade: Persist.topicsList.selectedgrade ? Persist.topicsList.selectedgrade.id : null,
            course: Persist.topicsList.selectedcourse ? Persist.topicsList.selectedcourse.id : null,
            type: $scope.selectedtype ? $scope.selectedtype.id : null,
            searchcontent: $scope.searchcontent,
            page: $scope.currentPage
        }, response=> {
            if (response.result) {
                Persist.topicsList.total = response.data.total;
                $scope.topicsList = response.data.list;
            }
        })
    };

    $scope.gotodetail = topicItem => {
        $state.go('system.topics.detail', {topicId: topicItem.id});
    };

    $scope.addtopic = () => {
        Persist.edittopic.selectedcourse = Persist.topicsList.selectedcourse;
        Persist.edittopic.selectedlevel = Persist.topicsList.selectedlevel;
        Persist.edittopic.selectedgrade = Persist.topicsList.selectedgrade;

        $state.go('system.topics.add');
    }

}]);