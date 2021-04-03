<template>
  <form method="POST" :action="url">
    <slot></slot>
    <input type="hidden" name="id" :value="id" />
    <button class="button button-secondary items-center icon w-full">
        <XIcon v-if="remove" class="mr-2" size="1.5x"></XIcon>
        <FolderAddIcon v-else class="mr-2" size="1.5x"></FolderAddIcon>
        {{ remove ? "Remove From" : "Add to" }} Reading List
    </button>
  </form>
</template>

<script>
import { FolderAddIcon } from "@vue-hero-icons/outline" 
import { XIcon } from "@vue-hero-icons/outline" 
export default {
  components: {
    FolderAddIcon, XIcon 
  },
  props: {
    url: String,
    id: String,
    remove: Boolean
  }
}
</script>