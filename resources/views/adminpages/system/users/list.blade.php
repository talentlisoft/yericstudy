@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">用户列表</span>
    <a ui-sref="system.users.add" uib-tooltip="添加用户"><i class="fa fa-plus fa-fw cursor-pointer" aria-hidden="true"></i></a>
</h6>

<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>用户名称</th>
            <th>邮箱地址</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="user in usersList" class="cursor-pointer" ng-click="gotouserdetail(user)">
            <td>{{$index + 1}}</td>
            <td>{{user.name}}</td>
            <td>{{user.email}}</td>
        </tr>
    </tbody>
</table>
@endverbatim