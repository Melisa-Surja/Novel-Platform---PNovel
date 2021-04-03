<template>
  <div>
    <template v-if="type == 'radio' || type == 'checkbox'">
      <div class="flex item-start">
        <div class="flex item-center pt-1">
          <input v-if="type == 'checkbox'" :id="name" :name="name" type="checkbox" class="form-checkbox h-4 w-4 text-primary" :checked="checked" :value="value">
          <input v-if="type == 'radio'" :id="name" :name="name" v-model="value" type="radio" class="form-radio h-4 w-4 text-primary">
        </div>

        <div class="ml-3 text-sm leading-5">
          <label :for="name" class="font-medium text-gray-700">{{label}}</label> 
          <p class="text-gray-500" v-if="desc">{{desc}}</p>
        </div>
      </div>
    </template>

    <div v-else class="mb-6">
      <backend-form-label :name="name" v-if="label" :required="required">{{label}}</backend-form-label>
      
      <!-- Checkbox -->
      <input v-if="type == 'single-checkbox'" :id="name" :name="name" type="checkbox" class="form-checkbox h-4 w-4 text-primary" :checked="checked" :value="value">

      <!-- Input -->
      <input v-if="type=='input'" :required="required" :id="name" :name="name" class="form-input block w-full sm:text-sm sm:leading-5" :placeholder="placeholder" :value="value">

      <!-- Password -->
      <input v-if="type=='password'" type="password" :required="required" :id="name" :name="name" class="form-input block w-full sm:text-sm sm:leading-5">

      <!-- Textarea -->
      <backend-form-textarea v-if="type=='textarea'" :id="name" :required="required" :rows="rows" :name="name" :placeholder="placeholder" :value="value" :maxlength="maxlength"></backend-form-textarea>

      <!-- Select -->
      <backend-form-select v-if="type=='select'" :id="name" :name="name" :placeholder="placeholder">
        <slot></slot>
      </backend-form-select>

      <backend-form-desc v-if="desc">
        {{desc}}
      </backend-form-desc>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      type: {
        type: String,
        default: "input"
      },
      //
      name: String,
      placeholder: String,
      value: String,
      //
      checked: Boolean,
      // textareas
      rows: Number,
      maxlength: Number,
      //
      label: String,
      desc: String,
      //
      required: Boolean
    }
  }
</script>