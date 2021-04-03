<template>
  <div class="mb-6">
    <div class="text-base leading-6 font-medium text-gray-900">{{title}}</div>
    <p class="text-sm leading-5 text-gray-500" v-if="$slots.default">
      <slot></slot>
    </p>
  </div>
</template>


<script>
  export default {
    props: {
      title: {
        type: String,
        required: true
      },
    }
  }
</script>
