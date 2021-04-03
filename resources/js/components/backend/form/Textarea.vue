<template>
  <div class="rounded-md shadow-sm mt-1 border border-gray-200 overflow-hidden textarea-parent">
      <textarea :id="name" :name="name" :rows="rows" :required="required" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 border-0" :placeholder="placeholder" v-model.trim="val" :maxlength="maxlength"></textarea>
      <div v-if="maxlength" class="bg-white pr-1 text-gray-600 text-xs bg-opacity-50 text-right">{{textarea_length}}</div>
  </div>
</template>

<script>
  export default {
    props: {
      name: String,
      placeholder: String,
      value: String,
      rows: {
        type: Number,
        default: 3
      },
      maxlength: Number,
      //
      required: Boolean
    },
    data() {
      return {
        val: this.value
      }
    },
    computed: {
      textarea_length: function() {
        if (this.maxlength) {
          // new line counts as double
          return this.val.length + "/" + this.maxlength;
        }
        else "";
      }
    }
  }
</script>