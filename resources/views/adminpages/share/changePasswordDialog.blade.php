<div class="modal-header">
    <h3 class="modal-title">更改密码 <i class="fa fa-key" aria-hidden="true"></i></h3>
</div>
<form ng-submit="ok()">
    <div class="modal-body changepassworddlg">
        <div class="form-group row">
            <label for="inputoldpassword" class="col-form-label col-md-3">旧密码</label>
            <div class="col-md-9">
                <input type="password" class="form-control" ng-model="oldpassword" id="inputoldpassword" focus-me="true" required />
            </div>
        </div>
        <div class="form-group row">
            <label for="inputnewpassword" class="col-form-label col-md-3">新密码</label>
            <div class="col-md-9 check">
                <input type="password" class="form-control" ng-model="newpassword" id="inputnewpassword" uib-tooltip="至少6位字符并且包含大写与小写和数字" required />
                <i class="fa fa-check" aria-hidden="true" ng-show="getpasswordScore()>=3"></i>
            </div>
        </div>
        <div class="form-group row">
            <label for="repeatpassword" class="col-form-label col-md-3">确认密码</label>
            <div class="col-md-9 check">
                <input type="password" class="form-control" ng-model="confirmpassword" id="repeatpassword" required />
                <i class="fa fa-check" aria-hidden="true" ng-show="isconfirmok()"></i>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">确定</button>
        <button class="btn btn-warning" type="button" ng-click="cancel()">取消</button>
    </div>
</form>