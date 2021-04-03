<template>
    <div @click.prevent="$eventBus.$emit('open_chapters_list')" class="flex-grow cursor-pointer">
        <slot></slot>
    </div>
</template>