<template>
    <component :is="type" :size="size"></component>
</template>

<script>
import { PencilAltIcon } from "@vue-hero-icons/outline";
import { EyeIcon } from "@vue-hero-icons/outline";
import { XCircleIcon } from "@vue-hero-icons/solid" 
export default {
  components: {
    PencilAltIcon, EyeIcon, XCircleIcon
  },
  props: {
    type: {type: String, required: true},
    size: {type: String, default: "1x"}
  }
}
</script>