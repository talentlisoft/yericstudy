import mytrainModule from './mytrainmodule';

export default mytrainModule.controller('doexercisectl', ['$scope', 'trainingData', '$state', 'toastr', 'Traineeinterface', '$stateParams', function($scope, trainingData, $state, toastr, Traineeinterface, $stateParams) {
    $scope.trainingData = trainingData;
    $scope.currentPos = 0;
    $scope.submitting = false;
    $scope.answer = null;
    $scope.lasttime = new Date();
    
    $scope.currenttopic = () => {
        return $scope.currentPos < $scope.trainingData.pendding_topics.length ? $scope.trainingData.pendding_topics[$scope.currentPos] : {course_name: 'Done', question: '--'}
    };

    $scope.answerquestion = () => {
        if (!$scope.submitting) {
            if ($scope.currentPos < $scope.trainingData.pendding_topics.length) {
                $scope.submitting = true;
                Traineeinterface.submitanswer({
                    'topic_id': $scope.trainingData.pendding_topics[$scope.currentPos].topic_id,
                    'answer': $scope.answer,
                    'traineetrainingId': $stateParams.traineetrainingId,
                    'duration': parseInt((new Date - $scope.lasttime) / 1000)
                }, response => {
                    $scope.submitting = false;
                    if (response.result) {
                        $scope.currentPos++;
                        $scope.answer = null;
                        if (response.data.isFinished) {
                            // Goto result page
                            toastr.success('都完成啦', '恭喜');
                            $state.go('mytrain.mytrains.result', {traineetrainingId: $stateParams.traineetrainingId});
                        } else {
                            $scope.lasttime = new Date();
                        }
                    }
                }, () => {
                    $scope.submitting = false;
                });
            } else {
                $scope.currentPos++;
                // $scope.answer = null;
            }
        }
    };

    $scope.getroundprogess = () => Math.round(($scope.trainingData.finished_count + $scope.currentPos)/($scope.trainingData.finished_count + $scope.trainingData.pendding_topics.length)*100);
}]);