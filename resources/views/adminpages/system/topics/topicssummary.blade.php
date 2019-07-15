@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3">
    <span class="head-border-left">题目汇总</span>
</h6>

<div class="card mb-3" ng-repeat="su in summaryData" ng-show="su.data.length > 0">
    <div class="card-header text-white bg-secondary d-flex justify-content-between">
        <span>{{getlevelname(su.level)}}</span>
        <span class="cursor-pointer" ng-click="addtopic(su.level)"><i class="fa fa-plus fa-fw" aria-hidden="true"></i></span>
    </div>
    <div class="card-body">
        <h5 class="card-title">题目总数：{{getlevelsum(su)}}</h5>
    </div>
    <table class="table mb-0 table-hover">
        <thead>
            <tr>
                <th>科目</th>
                <th>年级</th>
                <th>题目数量</th>
            </tr>
        </thead>
        <tbody>
            <tr class="cursor-pointer" ng-repeat="da in su.data">
                <td ng-click="addtopic(su.level, da.course_id, da.grade)">{{da.course_name}}</td>
                <td ng-click="addtopic(su.level, da.course_id, da.grade)">{{da.grade}}</td>
                <td ng-click="gotolist(su.level, da.course_id, da.grade)">{{da.topics_count}}</td>
            </tr>
        </tbody>
    </table>
</div>
@endverbatim