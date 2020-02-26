<template>
  <transition name="slide">
    <div class="loading-indicator" v-if="($store.state.loading > 0)"><i class="fa fa-spinner fa-pulse fa-fw"></i> 数据加载中...</div>
  </transition>
</template>

<script>
export default {

}
</script>

<style>

</style>