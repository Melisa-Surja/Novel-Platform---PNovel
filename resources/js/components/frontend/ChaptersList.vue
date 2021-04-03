<template>
<div v-show="isOpen" class="fixed z-10 inset-0 overflow-hidden">
    
    <transition
    enter-active-class="ease-in-out duration-500"
    enter-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="ease-in-out duration-500"
    leave-class="opacity-100"
    leave-to-class="opacity-0">
        <div v-show="isOpen" @click="isOpen = false" class="absolute inset-0 bg-black bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    </transition>

    <section class="absolute inset-y-0 left-0 md:pr-10 md:right-auto right-16 pr-0 max-w-full flex" aria-labelledby="slide-over-heading">
      
        <transition
        enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
        enter-class="-translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
        leave-class="translate-x-0"
        leave-to-class="-translate-x-full">
        <div v-show="isOpen" class="relative w-screen max-w-md">
            <div class="h-full py-6 px-4 bg-bg shadow-xl text-gray-300 overflow-y-auto">
                <slot></slot>
            </div>
        </div>
        </transition>

    </section>
</div>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false
        }
    },
    // watch: {
    //     isOpen: function(val) {
    //         this.$eventBus.$emit("sideMenu_toggle", val);
    //     }
    // },
    mounted() {
        this.$eventBus.$on("open_chapters_list", ()=>{
            this.isOpen = true;
        });
    }
}
</script>