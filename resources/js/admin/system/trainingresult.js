import systemModule from './systemmodule';
import Echo from 'laravel-echo';

window.io = require('socket.io-client');
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});

export default systemModule.controller('trainingresultctl', ['$scope', '$state', 'resultData', '$uibModal', 'Persist', function($scope, $state, resultData, $uibModal, Persist) {
    $scope.resultData = resultData;
    $scope.per = Persist;

    $scope.answerdetail = answer => {
        if (answer.result_id) {
            let detaildlg = $uibModal.open({
                animation: true,
                size: 'lg',
                templateUrl: '../adminpages/system.trainings.answerdetail',
                controller: ['$scope', 'answerDetail', '$uibModalInstance', '$state', 'Persist', function($scope, answerDetail, $uibModalInstance, $state, Persist) {
                    $scope.per = Persist;
                    $scope.hidehistory = true;
                    $scope.answerDetail = answerDetail;
                    $scope.getanswerlist = () => answerDetail.answer ? answerDetail.answer.split('|') : null;
                    $scope.close = () => {
                        $uibModalInstance.close(null);
                    };
                    $scope.edittopic = () => {
                        $uibModalInstance.close(null);
                        $state.go('system.topics.detail', {topicId: answerDetail.topic_id});
                    };
                }],
                resolve: {
                    answerDetail: ['Admininterface', function(Admininterface) {
                        return Admininterface.getresultdetail({
                            resultId: answer.result_id
                        }).$promise.then(response => response.result ? response.data : null);
                    }]
                }
            });
    
            detaildlg.result.then(result => {
    
            }, () => {
                // Canceled
            });
        }
    };

    $scope.getresultcolor = answer => answer.status == 'WRONG' ? 'table-warning': '';
    if ($scope.resultData.status != 1) {
        window.Echo.private(`training.${resultData.id}`).listen('.trainee.answering', e => {
            angular.forEach($scope.resultData.results, resultItem => {
                if (resultItem.topic_id == e.topic_id) {
                    resultItem.answer = e.answer;
                    resultItem.duration = e.duration;
                    resultItem.result_id = e.result_id;
                    resultItem.status = e.status;
                    $scope.$apply();
                }
            });
        });
    }


    $scope.$on('$destroy', () => {
        window.Echo.leaveChannel(`training.${resultData.id}`);
    });

}])