@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">训练结果</span>
</h6>

<div class="card bg-light mb-3">
    <div class="card-header">{{resultData.title}}</div>
    <div class="card-body">
        <h5 class="card-title" ng-show="resultData.status==1">得分：<span class="text-danger font-weight-bold">{{resultData.score}}</span></h5>
        <h5 class="card-title" ng-show="resultData.status==0">待完成</h5>
    </div>
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>题目</th>
                <th>回答</th>
                <th>用时（秒）</th>
                <th>结果</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="topic in resultData.results" ng-class="getresultcolor(topic)">
                <td>{{$index + 1}}</td>
                <td>{{topic.question}}</td>
                <td ng-class="topic.status?'':'text-danger'">{{topic.answer}}</td>
                <td>{{topic.duration}}</td>
                <td>
                    <i class="fa" aria-hidden="true" ng-class="getresulticon(topic)"></i>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<a href onclick="window.history.back()" class="btn btn-primary">返回训练情况</a>
@endverbatim