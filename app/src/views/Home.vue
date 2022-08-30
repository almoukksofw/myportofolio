<template>
  <div id="app">
    <home></home>

    <router-view />
  </div>
</template>

<script>
import home from "@/components/home";

export default {
  components: {
    home,
  },
};
</script>

