<template>
    <a class="button" target="_blank" :href="link">
        <slot></slot>
    </a>
</template>

<script>
  export default {
    props: {
      href: String
    },
    data() {
        return {
            link: this.href
        }
    },
    mounted() {
      this.$eventBus.$on("update_preview_link", new_link => {
        this.link = new_link;
      });
    },
  }
</script>