<template>
  <div>
      <h6 class="border-bottom pb-2 font-weight-bold mt-3">
            <span class="head-border-left">题目汇总</span>
        </h6>

        <div class="card mb-3" v-for="su in topicsSummary" :key="su.level" v-show="su.data.length > 0">
            <div class="card-header text-white bg-secondary d-flex justify-content-between">
                <span>{{getlevelname(su.level)}}</span>
                <span class="cursor-pointer" v-b-tooltip.hover title="添加新题" @click="showtoast()"><i class="fa fa-plus fa-fw" aria-hidden="true"></i></span>
            </div>
            <div class="card-body">
                <h5 class="card-title">题目总数：{{getlevelsum(su)}}</h5>
            </div>
            <table class="table mb-0 table-hover">
                <thead>
                    <tr>
                        <th>科目</th>
                        <th>年级</th>
                        <th>题目数量</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="cursor-pointer" v-for="(da, key, index) in su.data" :key="index">
                        <td>{{da.course_name}}</td>
                        <td>{{da.grade}}</td>
                        <td @click="gotolist(su.level, da.course_id, da.grade)">{{da.topics_count}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
  </div>
</template>

<script>
import Vue from 'vue';

export default {
    data() {
        return {
            topicsSummary: []
        }
    },
    beforeRouteEnter(to, from, next) {
        Vue.axios.get('../rest/topics/summary').then(response => {
            next(vm => {
                vm.setSummarydata(response.data.data);
            });
        })
    },
    methods: {
        setSummarydata(data) {
            this.topicsSummary = data;
        },
        getlevelname(levelId) {
            let level = this.$store.state.levelList.find(lv => lv.id == levelId);
            return level ? level.desc : null;
        },
        getlevelsum(su) {
            return su.data.reduce((lastvalue, current) => current.topics_count + lastvalue, 0);
        },
        gotolist(level, course, grade) {
            this.$store.commit('setselectedtopicsCondition', {
                level, course, grade
            });
            this.$router.push('/system/topics/list');
        },
        showtoast() {
            this.$bvToast.toast('test Toast', {
                title: 'Lisoft',
                autoHideDelay: 2000,
                appendToast: true,
                variant: 'success',
                solid: true
            });
        }
    },
    computed: {
        
    }

}
</script>

<style>

</style>