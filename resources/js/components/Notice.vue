<template>
    <div class="mb-4 py-3 px-3 rounded text-xs flex" :class="'bg-'+color+'-100'">
        <div class="mr-2">
            <component :is="icon" class="w-5 h-5" :class="'text-'+color+'-500'" />
        </div>

        <div class="flex-grow">
            <template v-if="type=='error'">
                <p class="font-semibold mb-1" :class="'text-'+color+'-900'">There were some errors in your submission</p>
                <ul class="list-disc ml-3 mb-0" :class="'text-'+color+'-700'">
                    <slot></slot>
                </ul>
            </template>

            <template v-if="type=='success'">
                <p class="font-semibold" :class="'text-'+color+'-900 mb-0'"><slot></slot></p>
            </template>
        </div>

        <!-- Necessary because dynamic classes aren't exported in production -->
        <div class="hidden 
        bg-red-100 bg-green-100 
        text-red-500 text-red-900 text-red-700
        text-green-500 text-green-900 text-green-700
        "></div>
    </div>
</template>

<script>
import { XCircleIcon } from "@vue-hero-icons/solid";
import { CheckCircleIcon } from "@vue-hero-icons/solid";
export default {
    components: {
        XCircleIcon, CheckCircleIcon
    },
    props: {
        type: String
    },
    data() {
        return {
            icon: "XCircleIcon",
            color: "red"
        }
    },
    mounted() {
        switch (this.type) {
            case 'error':
                this.icon = "XCircleIcon";
                this.color = "red";
                break;
            case 'success':
                this.icon = "CheckCircleIcon";
                this.color = "green";
                break;
        }
    }
}
</script>