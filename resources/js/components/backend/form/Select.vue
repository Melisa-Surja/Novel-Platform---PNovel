<template>
  <select :id="name" :name="name" class="block form-select w-full py-2 pl-3 pr-0 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
    <option v-if="placeholder">{{placeholder}}</option>
    <slot></slot>
  </select>
</template>

<script>
  export default {
    props: {
      name: String,
      placeholder: String
    }
  }
</script>