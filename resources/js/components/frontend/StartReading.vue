<template>
    <a :href="url" class="start-reading button items-center icon">
        <BookOpenIcon class="mr-2" size="1.5x"></BookOpenIcon>
        Start Reading
    </a>
</template>

<script>
import { BookOpenIcon } from "@vue-hero-icons/outline" 
export default {
  components: {
    BookOpenIcon
  },
  props: {
    url: String
  }
}
</script>