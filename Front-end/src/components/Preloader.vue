<template>
  <div class="loader">
    <div class="loader__image"></div>
  </div>
</template>

<script>
export default {
  name: 'Preloader',

  setup() {
    window.addEventListener('load', function() {
      const loader = document.querySelector('.loader');
      loader.className += ' hidden'; //? "loader hidden"
    });
  }
};
</script>

<style lang="scss" scoped>
.loader {
  position: fixed;
  z-index: 1000;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #ffffff;
  display: flex;
  justify-content: center;
  align-items: center;

  &__image {
    width: 800px;
    height: 600px;
    background-image: url('../assets/img/loader.gif');
    background-size: cover;
    background-repeat: no-repeat;
  }
}

.loader.hidden {
  animation: fadeOut 1s forwards;
}

@keyframes fadeOut {
  to {
    opacity: 0;
    visibility: hidden;
  }
}
</style>
