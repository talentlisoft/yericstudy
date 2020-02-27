<template>
<div>
  <ValidationObserver v-slot="{ invalid }">
    <form>
      <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
        <span class="head-border-left">题目详情</span>
      </h6>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group row">
            <label for="selectlevel" class="col-form-label col-md-4">等级</label>
            <div class="col-md-8">
              <ValidationProvider name="level" rules="required">
                <select id="selectlevel" v-model="selectedlevel" class="form-control" required>
                    <option value="">请选择等级</option>
                    <option
                        v-for="level in $store.state.levelList"
                        :value="level.id"
                        :key="level.id"
                    >{{level.desc}}</option>
                </select>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row">
            <label for="selectgrade" class="col-form-label col-md-4">年级</label>
            <div class="col-md-8">
              <ValidationProvider name="grade" rules="required">
                <select id="selectgrade" v-model="selectedgrade" class="form-control" required>
                    <option value="">请选择年级</option>
                    <option v-for="grade in $store.state.gradeList" :value="grade.id" :key="grade.id">{{grade.desc}}</option>
                </select>
              </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row">
            <label for="selectcourse" class="col-form-label col-md-4">课程</label>
            <div class="col-md-8">
                <ValidationProvider name="course" rules="required">
                    <select id="selectcourse" v-model="selectedcourse" class="form-control" required>
                        <option value>请选择课程</option>
                    </select>
                </ValidationProvider>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group row">
            <label for="selecttype" class="col-form-label col-md-4">题型</label>
            <div class="col-md-8">
                <ValidationProvider name="type" rules="required">
                    <select id="selecttype" v-model="selectedtype" class="form-control" required>
                        <option value="">请选择题型</option>
                        <option v-for="type in $store.state.topicTypes" :value="type.id" :key="type.id">{{type.name}}</option>
                    </select>
                </ValidationProvider>
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
                <input type="text" id="inputanswer" class="form-control" maxlength="200" />
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button">
                    <i class="fa fa-plus-circle fa-fw" aria-hidden="true"></i>
                  </button>
                </div>
                <div class="input-group-append" uib-dropdown>
                  <button
                    class="btn btn-outline-secondary"
                    type="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  ></button>
                  <div class="dropdown-menu" aria-labelledby="simple-dropdown">
                    <a href class="dropdown-item"></a>
                  </div>
                </div>
              </div>
              <div class="input-group mb-2">
                <input type="text" class="form-control" required />
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button">
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
        <button type="submit" :disabled="invalid" class="btn btn-primary">
          <i class="fa fa-fw" aria-hidden="true"></i> 保存并继续
        </button>
      </div>
    </form>
  </ValidationObserver>
</div>

</template>

<script>
import store from "./store";
import Vue from "vue";

export default {
  data() {
    return {
      selectedlevel: "",
      selectedcourse: "",
      selectedgrade: '',
      selectedtype: ''

    };
  },
  beforeRouteEnter(to, from, next) {
    if (store.state.coursesList) {
      next();
    } else {
      Vue.axios.get("../rest/courses/list").then(response => {
        if (response.data.result) {
          store.commit("setcoursesList", response.data.data);
          next();
        }
      });
    }
  },
    created() {
    this.selectedlevel = this.$store.state.selectedtopicsCondition.level;
    this.selectedcourse = this.$store.state.selectedtopicsCondition.course;
    this.selectedgrade = this.$store.state.selectedtopicsCondition.grade;
  },
};
</script>

<style>
</style>