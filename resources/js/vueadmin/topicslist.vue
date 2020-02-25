<template>
  <div>
      <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
        <span class="head-border-left">题目列表 <i class="fa fa-plus fa-fw cursor-pointer" v-b-tooltip.hover title="添加新题" aria-hidden="true"></i></span>
    </h6>
        
    <form>
        <div class="input-group mb-3">
            <input type="text" class="form-control">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search fa-fw" aria-hidden="true"></i></button>
                <button class="btn btn-outline-secondary" type="button"><i class="fa fa-caret-down fa-fw" aria-hidden="true"></i></button>
            </div>
        </div>

        <div class="bg-light p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="selectlevel" class="col-form-label col-md-4">等级</label>
                        <div class="col-md-8">
                            <select id="selectlevel" v-model="selectedlevel"  class="form-control">
                                <option value="">所有等级</option>
                                <option v-for="level in $store.state.levelList" :value="level.id" :key="level.id">{{level.desc}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="selectgrade" class="col-form-label col-md-4">年级</label>
                        <div class="col-md-8">
                            <select id="selectgrade" class="form-control">
                                <option value="">所有年级</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="selectcourse" class="col-form-label col-md-4">科目</label>
                        <div class="col-md-8">
                            <select id="selectcourse" class="form-control">
                                <option value="">全部科目</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="selecttype" class="col-form-label col-md-4">题型</label>
                        <div class="col-md-8">
                            <select id="slecttype" class="form-control">
                                <option value="">全部题型</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
        
    <div class="mt-3 mb-3">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>题目</th>
                    <th>类型</th>
                    <th>更新日期</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td><span></span></td>
                    <th></th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end">
        <!-- <ul uib-pagination boundary-links="true" max-size="3" boundary-link-numbers="true" rotate="true" items-per-page="20" total-items="per.topicsList.total" ng-model="currentPage" class="pagination-sm" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" ng-change="search()"></ul> -->
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            selectedgrade: null,
            selectedcourse: null,
            selectedlevel: null
        }
    },
    created() {
        this. selectedlevel = this.$store.state.selectedtopicsCondition.level;
    }
}
</script>

<style>

</style>