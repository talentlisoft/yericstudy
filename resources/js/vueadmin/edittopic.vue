<template>
  <form>
        <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
            <span class="head-border-left">题目详情</span>
        </h6>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectlevel" class="col-form-label col-md-4">等级</label>
                    <div class="col-md-8">
                        <select id="selectlevel" class="form-control" required>
                            <option value="">请选择等级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectgrade" class="col-form-label col-md-4">年级</label>
                    <div class="col-md-8">
                        <select id="selectgrade" class="form-control" required>
                            <option value="">请选择年级</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selectcourse" class="col-form-label col-md-4">课程</label>
                    <div class="col-md-8">
                        <select id="selectcourse" class="form-control" required>
                            <option value="">请选择课程</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="selecttype" class="col-form-label col-md-4">题型</label>
                    <div class="col-md-8">
                        <select id="selecttype" class="form-control" required>
                            <option value="">请选择题型</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="inputquestion" class="col-form-label col-md-2">题目</label>
                    <div class="col-md-10">
                        <textarea id="inputquestion" rows="5" class="form-control" required></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="inputanswer" class="col-form-label col-md-2">答案</label>
                    <div class="col-md-10">
                        <div class="input-group mb-2">
                            <input type="text" id="inputanswer" class="form-control" maxlength="200">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-plus-circle fa-fw" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="input-group-append" uib-dropdown>
                                <button class="btn btn-outline-secondary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu" aria-labelledby="simple-dropdown">
                                    <a href class="dropdown-item"></a>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" >
                                    <i class="fa fa-times fa-fw" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-secondary mr-2">保存并关闭</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-fw" aria-hidden="true"></i> 保存并继续</button>
        </div>
    </form>
</template>

<script>
export default {

}
</script>

<style>

</style>