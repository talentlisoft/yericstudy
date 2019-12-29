import systemmodule from './systemmodule';

export default systemmodule.controller('edittopicctl', ['$scope', 'Admininterface', 'toastr', 'Persist', 'coursesList', 'topicData', '$window', function ($scope, Admininterface, toastr, Persist, coursesList, topicData, $window) {
    $scope.per = Persist;
    $scope.coursesList = coursesList;
    $scope.saving = false;

    $scope.topicverifytypeList = [
        { id: false, name: '自动审题' },
        { id: true, name: '人工审题' }
    ];

    $scope.initnewtopicData = () => {
        $scope.topicData = {
            id: null,
            question: null,
            answer: null,
            optionalanswers: [],
            selectedverifytype: $scope.topicverifytypeList[0]
        };
    };

    if (!topicData) {
        $scope.initnewtopicData();
    } else {
        $scope.topicData = {
            id: topicData.id,
            question: topicData.question,
            answer: topicData.answer ? topicData.answer.split('|')[0] : null,
            optionalanswers: []
        };
        if (topicData.answer) {
            for (let i = 1; i < topicData.answer.split('|').length; i++) {
                $scope.topicData.optionalanswers.push({ value: topicData.answer.split('|')[i] });
            }
        }

        $scope.topicData.selectedverifytype = $scope.topicverifytypeList.find(ver => ver.id == topicData.manualverify);
        $scope.per.edittopic.selectedlevel = Persist.shared.levelList.find(lv => lv.id == topicData.level);
        $scope.per.edittopic.selectedgrade = Persist.shared.gradeList.find(grade => grade.id == topicData.grade);
        $scope.per.edittopic.selectedcourse = Persist.shared.coursesList.find(course => course.id == topicData.course);
        $scope.per.edittopic.selectedtype = Persist.shared.topictypesList.find(ty => ty.id == topicData.topictype_id);
    }


    $scope.addoptionalanswer = () => {
        $scope.topicData.optionalanswers.push({ value: null });
    };

    $scope.removeoptionalanswer = answer => {
        let index = $scope.topicData.optionalanswers.indexOf(answer);
        if (index > -1) {
            $scope.topicData.optionalanswers.splice(index, 1);
        }
    }

    $scope.saveandnew = () => {
        $scope.savetopic(() => {
            $scope.initnewtopicData();
            document.querySelector('#inputquestion').focus();
        })
    };

    $scope.saveandclose = () => {
        $scope.savetopic(() => {
            $window.history.back();
        });
    }

    $scope.checkquestion = () => {
        if ($scope.per.edittopic.selectedtype.name == '计算') {
            $scope.topicData.answer = eval($scope.topicData.question);
        }
    }

    $scope.savetopic = (successcallback = null) => {
        if ($scope.topicData.selectedverifytype.id == false && !$scope.topicData.answer) {
            toastr.warning('计算机自动审题的话总得给个答案做参考吧~~', '请检查');
        } else {
            $scope.saving = true;
            let answers = [$scope.topicData.answer];
            angular.forEach($scope.topicData.optionalanswers, opan => {
                answers.push(opan.value);
            });
            Admininterface.savetpic({
                id: $scope.topicData.id,
                level: $scope.per.edittopic.selectedlevel.id,
                grade: $scope.per.edittopic.selectedgrade.id,
                course: $scope.per.edittopic.selectedcourse.id,
                type: $scope.per.edittopic.selectedtype.id,
                question: $scope.topicData.question,
                manualverify: $scope.topicData.selectedverifytype.id,
                answer: answers.join('|')
            }).$promise.then(response => {
                if (response.result) {
                    toastr.success('保存成功', '操作成功');
                    if (successcallback) {
                        successcallback();
                    }
                }
                $scope.saving = false;
            }, () => {
                $scope.saving = false;
            })
        }
    };
}]);