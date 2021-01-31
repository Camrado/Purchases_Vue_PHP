<template>
  <!-- <Preloader /> -->

  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container">
      <router-link to="/" class="navbar-brand">Purchases</router-link>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse" style="outline: none">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse" id="navbarCollapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link to="/" active-class="active" class="nav-link">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/purchases" active-class="active" class="nav-link">Purchases</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/categories" active-class="active" class="nav-link">Categories</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <router-view />
</template>

<script>
// import Preloader from "@/components/Preloader.vue";

export default {
  // components: { Preloader },
};
</script>

<style lang="scss">
//Style
</style>
