@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">审核列表</span>
</h6>

<table class="table table-striped">
    <thread>
        <tr>
            <th>学员</th>
            <th>题目</th>
            <th>回答</th>
        </tr>
    </thread>
    <tbody>
        <tr ng-repeat="au in auditList" class="cursor-pointer" ng-click="auditanswer(au)">
            <td>{{au.trainee_name}}</td>
            <td>{{au.question}}</td>
            <td>{{au.answer}}</td>
        </tr>
    </tbody>
</table>

@endverbatim