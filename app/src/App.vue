<template>
  <div id="nav">
    <navbar></navbar>

    <router-view />
  </div>
</template>

<style>
@import './assets/cssfile.css';
</style>

<script>
import navbar from "@/components/navbar";
export default {
  components: {
    navbar,
  },
};
</script> 

