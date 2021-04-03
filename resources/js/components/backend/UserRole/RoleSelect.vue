<template>
    <div class="flex item-center px-4 py-3 hover:bg-gray-100 border-gray-200" :class="{'border-b': !last}">
        <input @change="changeRole()" :id="'role-'+role" name="role" type="radio" class="form-radio h-4 w-4 text-primary mt-1 flex-shrink-0" :checked="checked">
        <label :for="'role-'+role" class="ml-3 text-sm font-medium text-gray-700 flex-grow">{{role}}</label> 
    </div>
</template>

<script>
  export default {
    props: {
      role: {
        type: String,
        required: true
      },
      checked: Boolean,
      last: Boolean,
    },
    methods: {
      changeRole() {
        this.$eventBus.$emit("change_role", this.role);
      }
    }
  }
</script>