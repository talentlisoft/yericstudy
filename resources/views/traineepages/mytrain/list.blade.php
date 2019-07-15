@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">训练列表</span>
</h6>
    
<ul class="nav nav-tabs mb-3">
    <li class="nav-item" ng-click="per.scope='PENDDING';refreshlist()">
        <a class="nav-link" ng-class="per.scope=='PENDDING'?'active':''" href>待完成</a>
    </li>
    <li class="nav-item" ng-click="per.scope='FINISHED';refreshlist()">
        <a class="nav-link" ng-class="per.scope=='FINISHED'?'active':''" href>已完成</a>
    </li>
</ul>

<div class="row">
    <div class="col-12 col-md-4" ng-repeat="train in trainList">
        <div class="card bg-light mb-3">
            <div class="card-header cursor-pointer" ng-click="gotodetail(train)">{{train.title}}</div>
            <div class="card-body">
                <h5 class="card-title">{{train.finished_topics}} 题已完成，共 {{train.total_topics}} 题</h5>
                <p class="card-text">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{(train.finished_topics / train.total_topics)*100}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </p>
            </div>
        </div>
    </div>
</div>
@endverbatim
