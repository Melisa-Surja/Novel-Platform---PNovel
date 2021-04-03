<template>
  <label :for="name" class="block text-sm font-medium leading-5 text-gray-700 capitalize">
      <slot></slot><span v-if="required" class="text-red-500">*</span>
  </label>
</template>

<script>
  export default {
    props: {
        name: String,
        required: Boolean
    }
  }
</script>