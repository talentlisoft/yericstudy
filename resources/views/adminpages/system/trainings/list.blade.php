@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">训练列表</span>
    <i class="fa fa-plus fa-fw cursor-pointer" ng-click="addtraining()" uib-tooltip="添加测试" aria-hidden="true"></i>
</h6>

<form class="mb-3" ng-submit="searchtrainnings()">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="搜索训练标题">
        <div class="input-group-append">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fa fa-search fa-fw" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</form>

<table class="table table-striped mb-3">
    <thead>
        <tr>
            <th>标题</th>
            <th>布置时间</th>
            <th>受训人数</th>
            <th>完成人数</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="tr in trainingList">
            <td><a ui-sref="system.trainings.detail({trainingId: tr.id})">{{tr.title}}</a></td>
            <td>{{tr.created_at}}</td>
            <td>{{tr.trainee_count}}</td>
            <td>{{tr.finished_count}}</td>
        </tr>
    </tbody>
</table>

<div class="w-100 d-flex justify-content-end">
    <ul uib-pagination total-items="per.total" ng-model="per.currentPage" items-per-page="20" max-size="5" class="pagination-sm" boundary-links="true" force-ellipses="true" ng-change="gettraininglist()"></ul>
</div>
@endverbatim
