<template>
  <div>
      <h6 class="border-bottom pb-2 font-weight-bold mt-3">
            <span class="head-border-left">题目汇总</span>
        </h6>

        <div class="card mb-3">
            <div class="card-header text-white bg-secondary d-flex justify-content-between">
                <!-- <span>{{getlevelname(su.level)}}</span> -->
                <span class="cursor-pointer"><i class="fa fa-plus fa-fw" aria-hidden="true"></i></span>
            </div>
            <div class="card-body">
                <h5 class="card-title">题目总数：</h5>
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
                    <tr class="cursor-pointer">
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
  </div>
</template>

<script>
export default {
    data() {
        return {

        }
    },
    beforeRouteEnter(to, from, next) {
        console.log('enter');
        next(vm => {})
    }
}
</script>

<style>

</style>