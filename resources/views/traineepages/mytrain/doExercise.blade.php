@verbatim
<h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
    <span class="head-border-left">{{trainingData.title}}</span>
</h6>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{trainingData.finished_count + 1 + currentPos}} / {{trainingData.finished_count + trainingData.pendding_topics.length}}
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-md-3">{{currenttopic().course_name + currenttopic().topic_type}}</label>
                    <div class="col-md-9">
                        {{currenttopic().question}}
                    </div>
                </div>
                <form ng-submit="answerquestion()">
                    <div class="form-group row">
                        <label for="inputanswer" class="col-form-label col-3">回答</label>
                        <div class="col-9">
                            <div class="input-group">
                                <input type="text" class="form-control" ng-model="answer" required>
                                <div class="input-group-append">
                                    <button ng-disabled="submitting" class="btn btn-outline-success" type="submit"><i class="fa fa-fw" ng-class="submitting?'fa-spinner fa-pulse':'fa-check'" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="progress">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{(trainingData.finished_count +  currentPos)/(trainingData.finished_count + trainingData.pendding_topics.length)*100}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        {{getroundprogess()}}%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endverbatim