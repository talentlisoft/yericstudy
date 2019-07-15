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
                <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
                    <span class="head-border-left">{{currenttopic().course_name + currenttopic().topic_type}}</span>
                </h6>
                <div class="alert alert-primary" role="alert">
                    {{currenttopic().question}}
                </div>
                <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
                    <span class="head-border-left">回答</span>
                </h6>
                <form ng-submit="answerquestion()">
                    <textarea id="answer" class="form-control mb-3" rows="3" ng-model="answer" required>
                    </textarea>
                    <button ng-disabled="submitting" class="pull-right btn btn-outline-success" type="submit"><i class="fa fa-fw" ng-class="submitting?'fa-spinner fa-pulse':'fa-check'" aria-hidden="true"></i> 回答</button>                    
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