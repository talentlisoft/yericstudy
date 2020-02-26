<template>
  <div>
    <h6 class="border-bottom pb-2 font-weight-bold mt-3 mb-3">
      <span class="head-border-left">
        题目列表
        <i
          class="fa fa-plus fa-fw cursor-pointer"
          v-b-tooltip.hover
          title="添加新题"
          aria-hidden="true"
        ></i>
      </span>
    </h6>

    <form @submit.prevent="research">
      <div class="input-group mb-3">
        <input type="text" v-model="searchcontent" class="form-control" />
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="submit">
            <i class="fa fa-search fa-fw" aria-hidden="true"></i>
          </button>
          <button v-b-toggle.searchoptions class="btn btn-outline-secondary" type="button">
            <i class="fa fa-caret-down fa-fw" aria-hidden="true"></i>
          </button>
        </div>
      </div>
        <b-collapse id="searchoptions">
            <div class="bg-light p-3">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                    <label for="selectlevel" class="col-form-label col-md-4">等级</label>
                    <div class="col-md-8">
                        <select id="selectlevel" v-model="selectedlevel" class="form-control">
                        <option value="">所有等级</option>
                        <option
                            v-for="level in $store.state.levelList"
                            :value="level.id"
                            :key="level.id"
                        >{{level.desc}}</option>
                        </select>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                    <label for="selectgrade" class="col-form-label col-md-4">年级</label>
                    <div class="col-md-8">
                        <select id="selectgrade" class="form-control" v-model="selectedgrade">
                        <option value="">所有年级</option>
                        <option v-for="grade in $store.state.gradeList" :value="grade.id" :key="grade.id">{{grade.desc}}</option>
                        </select>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                    <label for="selectcourse" class="col-form-label col-md-4">科目</label>
                    <div class="col-md-8">
                        <select id="selectcourse" class="form-control" v-model="selectedcourse">
                        <option value="">全部科目</option>
                        <option v-for="course in $store.state.coursesList" :value="course.id" :key="course.id">{{course.name}}</option>
                        </select>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                    <label for="selecttype" class="col-form-label col-md-4">题型</label>
                    <div class="col-md-8">
                        <select id="slecttype" class="form-control" v-model="selectedcourseType">
                        <option value="">全部题型</option>
                        <option v-for="type in $store.state.topicTypes" :value="type.id" :key="type.id">{{type.name}}</option>
                        </select>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </b-collapse>

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
          <tr v-for="(topic, index) in topicsList" :key="index">
            <td>{{index + 1}}</td>
            <td>
              <span>{{topic.question}}</span>
            </td>
            <th>{{topic.topic_type}}</th>
            <td>{{topic.updated_at}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-end">
      <b-pagination v-model="currentPage" :total-rows="total" per-page="20" :disabled="loading" @change="search()"></b-pagination>
    </div>
  </div>
</template>

<script>
import store from "./store";
import Vue from 'vue';

export default {
  data() {
    return {
      selectedgrade: '',
      selectedcourse: '',
      selectedlevel: '',
      selectedcourseType: '',
      currentPage: 1,
      total: 0,
      topicsList: [],
      loading: false,
      searchcontent: null
    };
  },
  created() {
    this.selectedlevel = this.$store.state.selectedtopicsCondition.level;
    this.selectedcourse = this.$store.state.selectedtopicsCondition.course;
    this.selectedgrade = this.$store.state.selectedtopicsCondition.grade;
    this.search();
  },

  beforeRouteEnter(to, from, next) {
    if (store.state.coursesList) {
      next();
    } else {
      Vue.axios.get("../rest/courses/list").then(response => {
        if (response.data.result) {
            store.commit('setcoursesList', response.data.data);
            next();
        }
      });
    }
  },
  methods: {
      search() {
        this.loading = true;
          Vue.axios.post("../rest/topics/list", {
            level: this.selectedlevel,
            grade: this.selectedgrade,
            course: this.selectedcourse,
            type: this.selectedcourseType,
            searchcontent: this.searchcontent,
            page: this.currentPage
          }).then(response => {
              this.topicsList = response.data.data.list;
              this.total = response.data.data.total;
          }).finally(() => {
            this.loading = false;
          })
      },
      research() {
        this.currentPage = 1;
        this.search();
      }
  }
};
</script>

<style>
</style>