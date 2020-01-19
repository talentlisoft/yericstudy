@verbatim
<div class="modal-header">
    <h3 class="modal-title" id="modal-title">回答详情</h3>
</div>
<div class="modal-body">
    <h6 class="border-bottom pb-2 font-weight-bold mb-3">
        <span class="head-border-left">{{answerDetail.course_name + answerDetail.topic_type}}题</span>
    </h6>
    <div class="alert alert-info" role="alert">
        {{answerDetail.question | yericfomular}}
    </div>
    <h6 class="border-bottom pb-2 font-weight-bold mb-3">
        <span class="head-border-left">正确答案</span>
    </h6>
    <div class="alert alert-success cursor-pointer" role="alert" ng-click="edittopic()" uib-tooltip="题目错了？">
        <ul ng-show="answerDetail.answer && getanswerlist().length > 1" class="font-24">
            <li ng-repeat="an in getanswerlist()">{{an}}</li>
        </ul>
        <span ng-show="answerDetail.answer && getanswerlist().length == 1" class="font-24">{{answerDetail.answer}}</span>
        <span><i class="fa fa-external-link-square" aria-hidden="true"></i></span>
    </div>
    <h6 class="border-bottom pb-2 font-weight-bold mb-3">
        <span class="head-border-left">
            {{answerDetail.trainee_name}}用时{{answerDetail.duration}}秒做出的回答
            <i class="fa fa-history cursor-pointer" ng-click="hidehistory = !hidehistory" tooltip-class="text-nowrap" uib-tooltip="显示/隐藏历史回答" aria-hidden="true"></i>
        </span>
    </h6>
    <div class="alert" ng-class="answerDetail.status=='CORRECT'?'alert-success':(answerDetail.status=='WRONG'?'alert-danger':'alert-secondary')" role="alert">
        {{answerDetail.trainee_answer}}
    </div>
    <div uib-collapse="hidehistory">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>回答时间</th>
                    <th>回答内容</th>
                    <th>结果</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="his in answerDetail.history">
                    <td>{{his.created_at}}</td>
                    <td>{{his.answer}}</td>
                    <td>
                        <i class="fa" aria-hidden="true" ng-class="per.getresulticon(his.status)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" type="button" ng-click="close()">关闭</button>
</div>

@endverbatim