@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">用户详情</span>
</h6>
<form ng-submit="saveuser()" class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="inputusername" class="col-form-label col-md-4">用户名称</label>
            <div class="col-md-8">
                <input id="inputusername" type="text" class="form-control" maxlength="20" ng-model="userData.name" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="inputuseremail" class="col-form-label col-md-4">电子邮件</label>
            <div class="col-md-8">
                <input id="inputuseremail" type="email" ng-model="userData.email" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-grour row">
            <label for="inputpassword" class="col-form-label col-md-4">登陆密码</label>
            <div class="col-md-8">
                <input type="password" id="inputpassword" ng-model="userData.password" class="form-control" required minlength="6">
            </div>
        </div>
    </div>

    <div class="col-12">
        <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
            <span class="head-border-left">用户权限</span>
        </h6>
    </div>
    
    <div class="col-md-4 col-sm-6 mb-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="checktopics" ng-model="userData.permissions.checktopics">
            <label class="custom-control-label" for="checktopics">维护题库</label>
        </div>
    </div>

    <div class="col-md-4 col-sm-6 mb-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="checkusers" ng-model="userData.permissions.checkusers">
            <label class="custom-control-label" for="checkusers">维护用户</label>
        </div>
    </div>

    <div class="col-12">
        <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
            <span class="head-border-left">可管理学员</span>
        </h6>
    </div>

    <div class="col-md-3 col-6 trainnee-item cursor-pointer" ng-repeat="trainee in traineesList" ng-click="selecttrainee(trainee);gettopicslist()">
        <div class="trainee-item border p-2 rounded" ng-class="istraineeselected(trainee)?'text-light bg-success':'text-dark bg-light'">
            <p>姓名</p>
            <p>{{trainee.name}}</p>
            <i class="fa fa-check" aria-hidden="true"></i>
        </div>
    </div>

    <div class="d-flex justify-content-end col-12 mt-3">
        <button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> 保存</button>
    </div>
</form>
@endverbatim