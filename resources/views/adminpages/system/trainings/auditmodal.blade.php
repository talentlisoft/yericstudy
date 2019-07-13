@verbatim
<div class="modal-header">
    <h3 class="modal-title" id="modal-title">审核回答</h3>
</div>
<div class="modal-body" id="modal-body">
    <div class="form-group row">
        <label for="inputquestion" class="col-form-label col-md-3">{{auditdetailData.course_name+auditdetailData.type_name}}</label>
        <div class="col-md-9">
            <textarea id="inputquestion" class="form-control" ng-model="auditdetailData.question" cols="5" readonly></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputanswer" class="col-form-label col-md-3">{{auditdetailData.trainee_name}}的回答</label>
        <div class="col-md-9">
            <textarea id="inputanswer" cols="3" ng-model="auditdetailData.answer" readonly class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-success" type="button" ng-click="makecorrect()"><i class="fa fa-check" aria-hidden="true"></i> 回答正确</button>
    <button class="btn btn-danger" type="button" ng-click="makewrong()"><i class="fa fa-times" aria-hidden="true"></i> 回答错误</button>
</div>
@endverbatim