<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">训练列表</span>
    <i class="fa fa-plus fa-fw cursor-pointer" ng-click="addtraining()" uib-tooltip="添加测试" aria-hidden="true"></i>
</h6>

<form ng-submit="searchtrainnings()">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="搜索训练标题">
        <div class="input-group-append">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fa fa-search fa-fw" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</form>