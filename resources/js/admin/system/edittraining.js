import systemmodule from './systemmodule';

export default systemmodule.controller('edittrainingctl', ['$scope', 'Persist', 'Admininterface', 'traineesList', 'trainingData', '$timeout', 'toastr', '$state', function($scope, Persist, Admininterface, traineesList, trainingData, $timeout, toastr, $state) {
    $scope.traineesList = traineesList;
    $scope.topicsList = null;
    $scope.per = Persist;
    $scope.total = 0;
    $scope.showselectedtopics = false;
    $scope.saving = false;

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
            selectedtopics: [],
            title: null
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
    $scope.gettypedesc = topic => {
        return `${Persist.shared.levelList.find(lv => lv.id == topic.level).desc}${topic.grade}年级（${topic.course_name}）`;
    };

    $scope.selecttopic = topic => {
        let selectedone = $scope.trainingData.selectedtopics.find(tp => tp.id == topic.id);
        if (selectedone) {
            let index = $scope.trainingData.selectedtopics.indexOf(selectedone);
            if (index > -1) {
                $scope.trainingData.selectedtopics.splice(index, 1);
            }
        } else {
            $scope.trainingData.selectedtopics.push(topic);
        }
        if ($scope.trainingData.selectedtopics.length === 0) {
            $scope.showselectedtopics = false;
        }
    };
    
    $scope.istopicselected = topic => $scope.trainingData.selectedtopics.find(tp => tp.id == topic.id) ? true : false;

    $scope.removetopic = topic => {
        let index = $scope.trainingData.selectedtopics.indexOf(topic);
        if (index > -1) {
            if ($scope.trainingData.selectedtopics.length === 1) {
                $scope.showselectedtopics = false;
            }
            $timeout(() =>{$scope.trainingData.selectedtopics.splice(index, 1)}, 0);
        }
    };

    $scope.savetraining = () => {
        if ($scope.trainingData.selectedtrainees.length > 0) {
            if ($scope.trainingData.selectedtopics.length > 0) {
                $scope.saving = true;
                Admininterface.addtraining({
                    title: $scope.trainingData.title,
                    trainees: $scope.trainingData.selectedtrainees,
                    topics: $scope.trainingData.selectedtopics
                }, response => {
                    if (response.result) {
                        toastr.success('已白布置新测试', '操作成功');
                        $state.go('system.trainings.list');
                    } else {
                        $scope.saving = false;
                    }
                }, () => {
                    $scope.saving = false;
                });
            } else {
                toastr.warning('一题也不出吗？', '请检查');
            }
        } else {
            toastr.warning('请至少一名受训人!', '请检查');
        }
    };
}]);