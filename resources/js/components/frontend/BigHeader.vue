<template>
<h1 class="mt-4 mb-10 text-2xl text-white flex items-center justify-items-stretch">
    <div v-if="!noBox" class="w-3 h-5 mr-3 header-box">&nbsp;</div>
    <slot></slot>
</h1>
</template>


<script>
export default {
    props: {
        noBox: {type: Boolean, default: false}
    }
}
</script>