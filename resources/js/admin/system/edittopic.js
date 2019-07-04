import systemmodule from './systemmodule';

export default systemmodule.controller('edittopicctl', ['$scope', 'Admininterface', 'toastr', 'Persist', 'coursesList', 'topicData', '$window', function ($scope, Admininterface, toastr, Persist, coursesList, topicData, $window) {
    $scope.per = Persist;
    $scope.coursesList = coursesList;
    $scope.saving = false;

    $scope.initnewtopicData = () => {
        $scope.topicData = {
            id: null,
            question: null,
            answer: null,
            optionalanswers: []
        };
    };

    if (!topicData) {
        $scope.initnewtopicData();
    } else {
        $scope.topicData = {
            id: topicData.id,
            question: topicData.question,
            answer: topicData.answer.split('|')[0],
            optionalanswers: []
        };
        for (let i = 1; i < topicData.answer.split('|').length; i++) {
            $scope.topicData.optionalanswers.push({ value: topicData.answer.split('|')[i] });
        }
        $scope.per.edittopic.selectedlevel = Persist.shared.levelList.find(lv => lv.id == topicData.level);
        $scope.per.edittopic.selectedgrade = Persist.shared.gradeList.find(grade => grade.id == topicData.grade);
        $scope.per.edittopic.selectedcourse = Persist.shared.coursesList.find(course => course.id == topicData.course);
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

    $scope.savetopic = (successcallback = null) => {
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
            question: $scope.topicData.question,
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
    };
}]);