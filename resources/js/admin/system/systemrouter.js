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
        templateUrl: `${baseUrl}adminpages/system.topics.topicssummary`,
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
                        Persist.shared.coursesList = response.data.courses;
                        Persist.shared.topictypesList = response.data.topic_types;
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
        templateUrl: `${baseUrl}adminpages/system.topics.edittopic`,
        controller: 'edittopicctl',
        resolve: {
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data.courses;
                        Persist.shared.topictypesList = response.data.topic_types;
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

    $stateProvider.state('system.topics.detail', {
        url: '/modify/:topicId',
        controller: 'edittopicctl',
        templateUrl: `${baseUrl}adminpages/system.topics.edittopic`,
        resolve: {
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data.courses;
                        Persist.shared.topictypesList = response.data.topic_types;
                        return Persist.shared.coursesList;
                    } else {
                        return null;
                    }
                })
            }],
            topicData: ['$stateParams', 'Admininterface', function ($stateParams, Admininterface) {
                return Admininterface.gettopicdetail({
                    topicId: $stateParams.topicId
                }).$promise.then(response => {
                    return response.result ? response.data : null
                });
            }]
        }
    });

    $stateProvider.state('system.topics.list', {
        url: '/list',
        templateUrl: `${baseUrl}adminpages/system.topics.topicslist`,
        controller: 'topicslistctl',
        resolve: {
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data.courses;
                        Persist.shared.topictypesList = response.data.topic_types;
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
                    type: null,
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

    $stateProvider.state('system.trainings', {
        url: '/trainings',
        template: `<ui-view class="w-100 d-block uiview"></ui-view>`
    });

    $stateProvider.state('system.trainings.list', {
        url: '/list',
        templateUrl: `${baseUrl}adminpages/system.trainings.list`,
        controller: 'trainingslistctl',
        data: {
            pageTitle: '训练列表'
        },
        resolve: {
            trainingList: ['Admininterface', 'Persist', function (Admininterface, Persist) {
                return Admininterface.gettrainingslist({
                    searchcontent: Persist.trainingList.searchcontent,
                    page: Persist.trainingList.currentPage
                }).$promise.then(response => {
                    if (response.result) {
                        Persist.trainingList.total = response.data.total;
                        return response.data.list;
                    }
                });
            }]
        }
    });

    $stateProvider.state('system.trainings.detail', {
        url: '/detail/:trainingId',
        templateUrl: `${baseUrl}adminpages/system.trainings.detail`,
        controller: 'trainingdetailctl',
        data: {
            pageTitle: '训练详情'
        },
        resolve: {
            trainingDetail: ['Admininterface', '$stateParams', function (Admininterface, $stateParams) {
                return Admininterface.gettrainingDetail({
                    trainingId: $stateParams.trainingId
                }).$promise.then(response => response.result ? response.data : null);
            }]
        }
    });

    $stateProvider.state('system.trainings.result', {
        url: '/result/:traineetrainingId',
        controller: 'trainingresultctl',
        templateUrl: `${baseUrl}adminpages/system.trainings.result`,
        data: {
            pageTitle: '训练结果'
        },
        resolve: {
            resultData: ['Admininterface', '$stateParams', function(Admininterface, $stateParams) {
                return Admininterface.gettrainingResult({
                    traineetrainingId: $stateParams.traineetrainingId
                }).$promise.then(response => response.result ? response.data : null);
            }]
        }
    });

    $stateProvider.state('system.trainings.add', {
        url: '/add',
        templateUrl: `${baseUrl}adminpages/system.trainings.edit`,
        controller: 'edittrainingctl',
        data: {
            pageTitle: '添加训练'
        },
        resolve: {
            traineesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.traineesList ? Persist.shared.traineesList : Admininterface.gettraineelist().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.traineesList = response.data;
                        return Persist.shared.traineesList;
                    }
                })
            }],
            trainingData: [function () {
                return null;
            }],
            coursesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.coursesList ? Persist.shared.coursesList : Admininterface.getcoursesList().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.coursesList = response.data.courses;
                        Persist.shared.topictypesList = response.data.topic_types;
                        return Persist.shared.coursesList;
                    } else {
                        return null;
                    }
                })
            }],
        }
    });

    $stateProvider.state('system.manualaudit', {
        url: '/manualaudit',
        templateUrl: `${baseUrl}adminpages/system.manualaudit`,
        controller: 'manualauditctl',
        data: {
            pageTitle: '测试结果人工审核'
        },
        resolve: {
            auditList: ['Admininterface', function (Admininterface) {
                return Admininterface.getmanualauditlist().$promise.then(response => response.result ? response.data : null);
            }]
        }
    });

    $stateProvider.state('system.users', {
        url: '/users',
        template: `<ui-view class="w-100 d-block uiview"></ui-view>`
    });

    $stateProvider.state('system.users.list', {
        url: '/list',
        templateUrl: `${baseUrl}adminpages/system.users.list`,
        controller: 'userslistctl',
        resolve: {
            usersList: ['Admininterface', function(Admininterface) {
                return Admininterface.getuserlist().$promise.then(response => response.result ? response.data : null);
            }]
        }
    });

    $stateProvider.state('system.users.add', {
        url: '/add',
        templateUrl: `${baseUrl}adminpages/system.users.edit`,
        controller: 'editusersctl',
        resolve: {
            userData: [function() {
                return null;
            }],
            traineesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.traineesList ? Persist.shared.traineesList : Admininterface.gettraineelist().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.traineesList = response.data;
                        return Persist.shared.traineesList;
                    }
                })
            }],
        }
    });

    $stateProvider.state('system.users.modify', {
        url: '/modify/:userId',
        templateUrl: `${baseUrl}adminpages/system.users.edit`,
        controller: 'editusersctl',
        resolve: {
            userData: ['Admininterface', '$stateParams', function(Admininterface, $stateParams) {
                return Admininterface.getuserdetail({
                    userId: $stateParams.userId
                }).$promise.then(response => response.result ? response.data : null);
            }],
            traineesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Persist.shared.traineesList ? Persist.shared.traineesList : Admininterface.gettraineelist().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.traineesList = response.data;
                        return Persist.shared.traineesList;
                    }
                })
            }],
        }
    });

    $stateProvider.state('system.trainees', {
        url: '/trainees',
        template: `<ui-view class="w-100 d-block uiview"></ui-view>`
    });

    $stateProvider.state('system.trainees.list', {
        url: '/list',
        templateUrl: `${baseUrl}adminpages/system.trainees.list`,
        resolve: {
            traineesList: ['Persist', 'Admininterface', function (Persist, Admininterface) {
                return Admininterface.gettraineelist().$promise.then(response => {
                    if (response.result) {
                        Persist.shared.traineesList = response.data;
                        return Persist.shared.traineesList;
                    }
                })
            }],
        },
        controller: 'traineelistctl'
    })
}]);