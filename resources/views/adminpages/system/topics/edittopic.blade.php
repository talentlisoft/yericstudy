@verbatim
<form name="topicform" class="mb-3" ng-submit="saveandnew()">
        <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
            <span class="head-border-left">题目详情</span>
        </h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectlevel" class="col-form-label col-md-4">等级</label>
                    <div class="col-md-8">
                        <select id="selectlevel" class="form-control" ng-model="per.edittopic.selectedlevel" ng-options="level as level.desc for level in per.shared.levelList" required>
                            <option value="">请选择等级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectgrade" class="col-form-label col-md-4">年级</label>
                    <div class="col-md-8">
                        <select id="selectgrade" class="form-control" ng-model="per.edittopic.selectedgrade" ng-options="grade as grade.desc for grade in per.shared.gradeList" required>
                            <option value="">请选择年级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectcourse" class="col-form-label col-md-4">课程</label>
                    <div class="col-md-8">
                        <select id="selectcourse" class="form-control" ng-model="per.edittopic.selectedcourse" ng-options="course as course.name for course in coursesList" required>
                            <option value="">请选择课程</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selecttype" class="col-form-label col-md-4">题型</label>
                    <div class="col-md-8">
                        <select id="selecttype" class="form-control" ng-model="per.edittopic.selectedtype" ng-options="type as type.name for type in per.shared.topictypesList" required>
                            <option value="">请选择题型</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="inputquestion" class="col-form-label col-md-2">题目</label>
                    <div class="col-md-10">
                        <textarea id="inputquestion" rows="5" class="form-control" ng-model="topicData.question" required></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="inputanswer" class="col-form-label col-md-2">答案</label>
                    <div class="col-md-10">
                        <div class="input-group mb-2">
                            <input type="text" id="inputanswer" class="form-control" ng-model="topicData.answer" maxlength="200">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" uib-tooltip="添加其它可选答案" tooltip-placement="left" ng-click="addoptionalanswer()">
                                    <i class="fa fa-plus-circle fa-fw" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="input-group-append" uib-dropdown>
                                <button uib-dropdown-toggle class="btn btn-outline-secondary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{topicData.selectedverifytype.name}}</button>
                                <div class="dropdown-menu" uib-dropdown-menu aria-labelledby="simple-dropdown">
                                    <a href ng-repeat="ver in topicverifytypeList" class="dropdown-item" ng-click="topicData.selectedverifytype = ver">{{ver.name}}</a>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-2" ng-repeat="an in topicData.optionalanswers track by $index">
                            <input type="text" class="form-control" ng-model="an.value" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" ng-click="removeoptionalanswer(an)" uib-tooltip="删除此项" tooltip-placement="left">
                                    <i class="fa fa-times fa-fw" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary mr-2" ng-disabled="saving || !topicform.$valid" ng-click="saveandclose()">保存并关闭</button>
            <button type="submit" class="btn btn-primary" ng-disabled="saving"><i class="fa fa-fw" ng-class="saving?'fa-spinner fa-pulse':'fa-floppy-o'" aria-hidden="true"></i> 保存并继续</button>
        </div>
    </form>
@endverbatim
