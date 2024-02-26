<template>
    <button
      @click="scrollToTop"
      class="fixed bottom-5 right-5 bg-primary hover:bg-accent text-white font-bold py-3 px-5 rounded-full"
      v-show="visible"
    >
      <i class="fa-solid fa-arrow-up"></i>
    </button>
  </template>
  
  <script>
  export default {
    data() {
      return {
        visible: false,
      };
    },
    methods: {
      scrollToTop() {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      },
      handleScroll() {
        this.visible = window.scrollY > 400;
      },
    },
    mounted() {
      window.addEventListener("scroll", this.handleScroll);
    },
    beforeDestroy() {
      window.removeEventListener("scroll", this.handleScroll);
    },
  };
  </script>