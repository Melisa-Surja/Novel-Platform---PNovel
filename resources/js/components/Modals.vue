<template>
<div>
    <span v-if="$slots.trigger" @click.prevent.stop="isOpen = true" class="flex cursor-pointer">
        <slot name="trigger"></slot>
    </span>

    <div v-show="isOpen" class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <transition
        enter-active-class="ease-out duration-300"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-class="opacity-100"
        leave-to-class="opacity-0">
            <div v-show="isOpen" @click="isOpen = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
        </transition>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <transition
        enter-active-class="ease-out duration-300"
        enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div v-show="isOpen" class="modal-bg inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <slot></slot>
                </div>
            </div>
        </transition>
    </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        id: {
            type: String, 
            default: String(Date.now())
        }
    },
    data() {
        return {
            isOpen: false
        }
    }, 
    methods: {
    },
    mounted() {
        var t = this;
        this.$eventBus.$on('modal_open_' + this.id, ()=> {
            t.isOpen = true;
        })
    }
}
</script>