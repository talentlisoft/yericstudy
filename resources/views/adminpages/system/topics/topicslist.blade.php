@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">题目列表 <i class="fa fa-plus fa-fw cursor-pointer" ng-click="addtopic()" uib-tooltip="添加新题" aria-hidden="true"></i></span>
</h6>
    
<form ng-submit="researchlist()">
    <div class="input-group mb-3">
        <input type="text" class="form-control" ng-model="searchcontent">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search fa-fw" aria-hidden="true"></i></button>
            <button class="btn btn-outline-secondary" type="button" ng-click="per.topicsList.optionexpanded = !per.topicsList.optionexpanded"><i class="fa fa-caret-down fa-fw" aria-hidden="true"></i></button>
        </div>
    </div>

    <div class="bg-light p-3" uib-collapse="per.topicsList.optionexpanded">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectlevel" class="col-form-label col-md-4">等级</label>
                    <div class="col-md-8">
                        <select id="selectlevel" class="form-control" ng-model="per.topicsList.selectedlevel" ng-options="level as level.desc for level in per.shared.levelList">
                            <option value="">筛选等级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectgrade" class="col-form-label col-md-4">年级</label>
                    <div class="col-md-8">
                        <select id="selectgrade" class="form-control" ng-model="per.topicsList.selectedgrade" ng-options="grade as grade.desc for grade in per.shared.gradeList">
                            <option value="">筛选年级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectcourse" class="col-form-label col-md-4">科目</label>
                    <div class="col-md-8">
                        <select id="selectcourse" class="form-control" ng-model="per.topicsList.selectedcourse" ng-options="course as course.name for course in per.shared.coursesList">
                            <option value="">筛选科目</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    
<div class="mt-3 mb-3">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>题目</th>
                <th>类型</th>
                <th>更新日期</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="item in topicsList" class="cursor-pointer" ng-click="gotodetail(item)">
                <td>{{$index + 1}}</td>
                <td>{{item.question}}</td>
                <th>{{gettypedesc(item)}}</th>
                <td>{{item.updated_at}}</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-end">
    <ul uib-pagination boundary-links="true" total-items="per.topicsList.total" ng-model="currentPage" class="pagination-sm" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" ng-change="search()"></ul>
</div>
@endverbatim
