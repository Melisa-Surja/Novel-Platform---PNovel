<template>
    <a @click.prevent="clickThis()" class="block rounded px-4 py-4 border border-transparent hover:border-gray-500 bg-gray-700 hover:bg-opacity-100 text-sm mb-2 cursor-pointer" :class="read ? 'bg-opacity-50 opacity-50 hover:opacity-100' : 'bg-opacity-75 hover:bg-gray-600'">
        <slot></slot>
    </a>
</template>

<script>
export default {
    props: {
        update: String,
        href: String,
        read: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        clickThis() {
            if (!this.read) {
                // if not read, make it read
                axios.patch(this.update, {read: true});
            }
            window.location.href = this.href;
        }
    }
}
</script>