import systemmodule from './systemmodule';

export default systemmodule.controller('edittopicctl', ['$scope', 'Admininterface', 'toastr', 'Persist', 'coursesList', 'topicData', '$window', function($scope, Admininterface, toastr, Persist, coursesList, topicData, $window) {
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
    }
    

    $scope.addoptionalanswer = () => {
        $scope.topicData.optionalanswers.push({value: null});
    };

    $scope.removeoptionalanswer = answer => {
        let index = $scope.topicData.optionalanswers.indexOf(answer);
        if (index > -1) {
            $scope.topicData.optionalanswers.splice(index, 1);
        }
    }

    $scope.saveandnew = () => {
        $scope.savetopic(()=> {
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
            answers.push(an.value);
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