import systemmodule from './systemmodule';
import persist from '../persist';

export default systemmodule.controller('topicslistctl', ['$scope', 'Admininterface', 'topicsList', 'Persist', 'coursesList', function($scope, Admininterface, topicsList, Persist, coursesList) {
    $scope.topicsList = topicsList;
    $scope.coursesList = coursesList;
    $scope.searchcontent = null;
    $scope.per = Persist;
    $scope.currentPage = 1;

    $scope.researchlist = () => {
        $scope.currentPage = 1;
        $scope.search();
    };

    $scope.search = () => {
        Admininterface.gettopicslist({
            level: Persist.topicsList.selectedlevel ? Persist.topicsList.selectedlevel.id : null,
            grade: Persist.topicsList.selectedgrade ? Persist.topicsList.selectedgrade.id : null,
            course: Persist.topicsList.selectedcourse ? Persist.topicsList.selectedcourse.id : null,
            searchcontent: $scope.searchcontent,
            page: $scope.currentPage
        }, response=> {
            if (response.result) {
                Persist.topicsList.total = response.data.total;
                $scope.topicsList = response.data.list;
            }
        })
    };

}])