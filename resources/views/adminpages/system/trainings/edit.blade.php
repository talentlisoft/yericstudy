@verbatim
<div>
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">选择受训人</span>
</h6>
<div class="row">
    <div class="col-md-3 col-6 trainnee-item cursor-pointer" ng-repeat="trainee in traineesList" ng-click="selecttrainee(trainee);gettopicslist()">
        <div class="trainee-item border p-2 rounded" ng-class="istraineeselected(trainee)?'text-light bg-success':'text-dark bg-light'">
            <p>姓名</p>
            <p>{{trainee.name}}</p>
            <i class="fa fa-check" aria-hidden="true"></i>
        </div>
    </div>
</div>

<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">训练详情</span>
</h6>

<form ng-submit="refreshtopiclist()">
    <div class="input-group mb-3">
        <input type="text" class="form-control" ng-model="conditions.searchcontent">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search fa-fw" aria-hidden="true"></i></button>
            <button class="btn btn-outline-secondary" type="button" ng-click="conditions.optionexpanded = !conditions.optionexpanded"><i class="fa fa-caret-down fa-fw" aria-hidden="true"></i></button>
        </div>
    </div>

    <div class="bg-light p-3" uib-collapse="conditions.optionexpanded">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectlevel" class="col-form-label col-md-4">等级</label>
                    <div class="col-md-8">
                        <select id="selectlevel" class="form-control" ng-model="conditions.selectedlevel" ng-options="level as level.desc for level in per.shared.levelList">
                            <option value="">全部等级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectgrade" class="col-form-label col-md-4">年级</label>
                    <div class="col-md-8">
                        <select id="selectgrade" class="form-control" ng-model="conditions.selectedgrade" ng-options="grade as grade.desc for grade in per.shared.gradeList">
                            <option value="">所有年级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectcourse" class="col-form-label col-md-4">科目</label>
                    <div class="col-md-8">
                        <select id="selectcourse" class="form-control" ng-model="conditions.selectedcourse" ng-options="course as course.name for course in per.shared.coursesList">
                            <option value="">全部科目</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selecttype" class="col-form-label col-md-4">题型</label>
                    <div class="col-md-8">
                        <select id="selecttype" class="form-control" ng-model="conditions.selectedtype" ng-options="type as type.name for type in per.shared.topictypesList">
                            <option value="">全部题型</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item" ng-click="conditions.mode='RECENT';refreshtopiclist()">
        <a class="nav-link" ng-class="conditions.mode=='RECENT'?'active':''" href>最近错过</a>
    </li>
    <li class="nav-item" ng-click="conditions.mode='EVER';refreshtopiclist()">
        <a class="nav-link" ng-class="conditions.mode=='EVER'?'active':''" href>曾经错过</a>
    </li>
    <li class="nav-item" ng-click="conditions.mode='RADOM';refreshtopiclist()">
        <a class="nav-link" ng-class="conditions.mode=='RADOM'?'active':''" href>随机</a>
    </li>
    <li class="nav-item" ng-click="conditions.mode='FREQUENCY';refreshtopiclist()">
        <a class="nav-link" ng-class="conditions.mode=='FREQUENCY'?'active':''" href>训练最少</a>
    </li>
    <li class="nav-item" ng-click="conditions.mode='NEWEST';refreshtopiclist()">
        <a class="nav-link" ng-class="conditions.mode=='NEWEST'?'active':''" href>最新的</a>
    </li>
</ul>
<table class="table table-striped table-hover picking-topics">
    <thead>
        <tr>
            <th>#</th>
            <th>类型</th>
            <th>题目</th>
            <th>做题统计</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="topic in topicsList" ng-class="istopicselected(topic)?'table-success':''">
            <td class="cursor-pointer" ng-click="selecttopic(topic)">
                <span class="topic-index">{{$index + 1}}</span>
                <span class="topic-selected"><i class="fa fa-check" aria-hidden="true"></i></span>
            </td>
            <td>{{gettypedesc(topic)}}</td>
            <td><span uib-popover="{{topic.question_full}}" popover-title="题目详情" popover-trigger="topic.question_full == topic.question ? 'none':'mouseenter'">{{topic.question}}</span></td>
            <td><span class="text-success">{{topic.total_correct}}</span> / <span class="text-danger">{{topic.total_fail}}</span></td>
        </tr>
    </tbody>
</table>
<div class="w-100 d-flex justify-content-end">
    <ul uib-pagination total-items="total" ng-model="conditions.currentPage" items-per-page="20" max-size="5" class="pagination-sm" boundary-links="true" force-ellipses="true" ng-change="gettopicslist()"></ul>
</div>


<form ng-submit="savetraining()">
    <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
        <span class="head-border-left">训练标题</span>
    </h6>
    <input type="text" class="form-control" placeholder="标题" ng-model="trainingData.title" maxlength="50" required>

    <div class="d-flex justify-content-end mt-3">
        <button type="submit" class="btn btn-primary" ng-disabled="saving"><i class="fa fa-fw" ng-class="saving?'fa-spinner fa-pulse':'fa-floppy-o'" aria-hidden="true"></i> 保存</button>
    </div>
</form>
<div id="selected-topics-pane" class="selected-topics bg-info text-white" ng-show="trainingData.selectedtopics.length > 0" ng-class="showselectedtopics?'show-content':''" click-outside="showselectedtopics = false">
    <div class="summary cursor-pointer bg-primary" ng-click="showselectedtopics = !showselectedtopics">
        {{trainingData.selectedtopics.length}} 题
    </div>
    <div class="content">
        <div class="selected-item pl-2 pr-2" ng-repeat="topic in trainingData.selectedtopics">
            <p class="d-flex justify-content-between">
                <span>{{topic.grade}}年级{{topic.course_name}}</span>
                <span class="cursor-pointer" ng-click="removetopic(topic)"><i class="fa fa-times" aria-hidden="true"></i></span>
            </p>
            <p>
                {{topic.question}}
            </p>
        </div>
    </div>
</div>
@endverbatim
