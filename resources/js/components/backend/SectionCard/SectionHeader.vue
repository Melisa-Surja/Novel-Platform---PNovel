<template>
  <div>
      <h3 class="text-lg font-medium leading-6 text-gray-900">{{title}}</h3>
      <p class="mt-1 text-sm leading-5 text-gray-600" v-if="$slots.default">
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
