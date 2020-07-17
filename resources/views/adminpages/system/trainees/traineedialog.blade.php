@verbatim
<form ng-submit="save()">
    <div class="modal-header">
        <h3 class="modal-title" id="modal-title">学员详情</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-4">
                <img ng-src="{{traineeDetail.avatar}}" onerror="this.src='https://via.placeholder.com/250'" class="w-100 img-thumbnail">
            </div>
            <div class="col-md-8">
                <div class="form-group row">
                    <label for="inputname" class="col-form-label col-md-3">学员名</label>
                    <div class="col-md-9">
                        <input type="text" id="inputname" ng-model="traineeDetail.name" class="form-control" maxlength="10" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputavatarurl" class="col-form-label col-md-3">头像URL</label>
                    <div class="col-md-9">
                        <input type="url" class="form-control" ng-model="traineeDetail.avatar" maxlength="200" ng-model-options="{updateOn: 'blur'}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputpassword" class="col-form-label col-md-3">登陆密码</label>
                    <div class="col-md-9">
                        <input type="password" id="inputpassword" class="form-control" ng-model="traineeDetail.password" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success" ng-disabled="saving" type="submit"><i ng-class="saving?'fa-spinner fa-pulse':'fa-floppy-o'" class="fa" aria-hidden="true"></i> 保存</button>
        <button class="btn btn-warning" type="button" ng-click="cancel()"><i class="fa fa-times" aria-hidden="true"></i> 关闭</button>
    </div>
</form>
@endverbatim