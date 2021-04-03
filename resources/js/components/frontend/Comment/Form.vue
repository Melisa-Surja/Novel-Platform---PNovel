<template>
<div class="mt-4 mb-12 comment-form">
    <div class="-mb-8">
        <big-header :noBox="true">Leave a Comment</big-header>
    </div>

    <form method="POST" :action="action">

        <slot></slot>

        <backend-form-input type="textarea" required :rows="3" name="message" placeholder="Your comment" @click.native.once="guest_expand = true"></backend-form-input>

        <div class="-mt-4 flex">

            <div v-if="guest_commenting" v-show="guest_expand" class="flex-grow mr-2 -mb-6">
                <div class="-mb-5">
                <backend-form-input required name="guest_name" placeholder="Your Name"></backend-form-input>
                </div>
                <backend-form-input required name="guest_email" placeholder="Your Email"></backend-form-input>
            </div>

            <button type="submit" class="md:w-36 flex items-center">Submit</button>
        </div>

    </form>
    
</div>
</template>


<script>
export default {
  props: {
    action: String,
    guest_commenting: Boolean,
    header: {
        type:Boolean,
        default: true
    }
  },
  data() {
      return {
          guest_expand: false
      }
  }
}
</script>