@verbatim
<div class="modal-header">
    <h3 class="modal-title" id="modal-title">回答详情</h3>
</div>
<div class="modal-body">
    <h6 class="border-bottom pb-2 font-weight-bold mb-3">
        <span class="head-border-left">{{answerDetail.course_name + answerDetail.topic_type}}题</span>
    </h6>
    <div class="alert alert-info" role="alert">
        {{answerDetail.question}}
    </div>
    <h6 class="border-bottom pb-2 font-weight-bold mb-3">
        <span class="head-border-left">正确答案</span>
    </h6>
    <div class="alert alert-success" role="alert">
        <ul ng-show="answerDetail.answer && getanswerlist().length > 1">
            <li ng-repeat="an in getanswerlist()">{{an}}</li>
        </ul>
        <span ng-show="answerDetail.answer && getanswerlist().length == 1">{{answerDetail.answer}}</span>
    </div>
    <h6 class="border-bottom pb-2 font-weight-bold mb-3">
        <span class="head-border-left">{{answerDetail.trainee_name}}用时{{answerDetail.duration}}秒做出的回答</span>
    </h6>
    <div class="alert" ng-class="answerDetail.status=='CORRECT'?'alert_success':(answerDetail.status=='WRONG'?'alert-danger':'alert-secondary')" role="alert">
        {{answerDetail.trainee_answer}}
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="button" ng-click="close()">关闭</button>
</div>

@endverbatim