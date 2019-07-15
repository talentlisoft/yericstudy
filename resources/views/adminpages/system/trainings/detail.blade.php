@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">训练情况</span>
</h6>

<div class="row">
    <div class="col-md-3 col-sm-4" ng-repeat="tr in trainingDetail">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between cursor-pointer" ng-class="tr.status?'bg-success text-white':'bg-info'" ng-click="gotoresult(tr)">
                <span>{{tr.trainee_name}}</span>
                <span>
                    <i class="fa fa-fw" ng-class="tr.status?'fa-check':'fa-clock-o'" aria-hidden="true"></i>
                </span>
            </div>
            <div class="card-body">
                <h5 class="card-title" ng-show="tr.status == 1">
                    已完成
                </h5>
                <h5 class="card-title" ng-show="tr.status == 0">
                    未完成 {{tr.finished_topics}} of {{tr.total_topics}}
                </h5>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped" ng-class="tr.status?'bg-success':'bg-info'" role="progressbar" style="width: {{(tr.finished_topics/tr.total_topics)*100}}%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endverbatim