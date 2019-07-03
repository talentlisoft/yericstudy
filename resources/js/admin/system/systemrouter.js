import systemmodule from './systemmodule';

export default systemmodule.config(['$stateProvider', '$locationProvider', function ($stateProvider, $locationProvider) {
    let baseUrl = '../';

    $stateProvider.state('system', {
        url: '/system',
        templateUrl: `${baseUrl}adminpages/system`
    });

    $stateProvider.state('system.topics', {
        url: '/topics',
        template: `<ui-view class="w-100 d-block uiview"></ui-view>`
    });

    $stateProvider.state('system.topics.summary', {
        url: '/summary',
        templateUrl: `${baseUrl}adminpages/system.topicssummary`,
        controller: 'topicssummaryctl',
        resolve: {
            summaryData: ['Admininterface', function (Admininterface) {
                return Admininterface.gettopicsummary().$promise.then(response => {
                    return response.result ? response.data : null;
                })
            }],
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data;
                        return Persist.shared.coursesList;
                    } else {
                        return null;
                    }
                })
            }]
        }
    });

    $stateProvider.state('system.topics.add', {
        url: '/add',
        templateUrl: `${baseUrl}adminpages/system.edittopic`,
        controller: 'edittopicctl',
        resolve: {
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data;
                        return Persist.shared.coursesList;
                    } else {
                        return null;
                    }
                })
            }],
            topicData: [function () {
                return null;
            }]
        }
    });

    $stateProvider.state('system.topics.list', {
        url: '/list',
        templateUrl: `${baseUrl}adminpages/system.topicslist`,
        controller: 'topicslistctl',
        resolve: {
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data;
                        return Persist.shared.coursesList;
                    } else {
                        return null;
                    }
                })
            }],
            topicsList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Admininterface.gettopicslist({
                    level: Persist.topicsList.selectedlevel ? Persist.topicsList.selectedlevel.id : null,
                    grade: Persist.topicsList.selectedgrade ? Persist.topicsList.selectedgrade.id : null,
                    course: Persist.topicsList.selectedcourse ? Persist.topicsList.selectedcourse.id : null,
                    page: 1
                }).$promise.then(response => {
                    if (response.result) {
                        Persist.topicsList.total = response.data.total;
                        return response.data.list;
                    }
                });
            }]
        }
    });
}]);