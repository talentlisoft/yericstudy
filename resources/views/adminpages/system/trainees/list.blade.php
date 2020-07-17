@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">学员列表</span>
    <a href uib-tooltip="添加学员" ng-click="addtrainee()"><i class="fa fa-plus fa-fw cursor-pointer" aria-hidden="true"></i></a>
</h6>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>学员名</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="user in traineesList" class="cursor-pointer" ng-click="gotouserdetail(user)">
            <td>{{$index + 1}}</td>
            <td>{{user.name}}</td>
        </tr>
    </tbody>
</table>
@endverbatim