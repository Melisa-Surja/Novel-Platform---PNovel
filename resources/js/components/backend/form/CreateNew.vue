<template>
    <a class="button text-sm icon" style="margin-bottom: 0 !important;" :href="href">
        <PlusIcon class="mr-1" size="1.5x"></PlusIcon>
        Create New
    </a>
</template>

<script>
 import { PlusIcon } from "@vue-hero-icons/solid" 
export default {
  components: {
    PlusIcon
  },
  props: {
    href: String,
  }
}
</script>