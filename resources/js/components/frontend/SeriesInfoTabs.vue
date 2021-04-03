<template>
    <div>
        <h1 class="mb-4 border-b border-gray-500 text-2xl flex items-baseline justify-start cursor-pointer">
            <div @click="tabs = 'summary'" class="p-4 pl-0 flex items-center" :class="tabs!='summary'? 'text-gray-500' : 'border-b-2 border-primary'">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Summary
            </div>
            <div @click="tabs = 'chapters'" class="p-4 pl-2 flex items-center" :class="tabs!='chapters'? 'text-gray-500' : 'border-b-2 border-primary'">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg> 
                Chapters
            </div>
        </h1>

        <div v-show="tabs == 'summary'" class="summary text-sm">
            <slot name="summary"></slot>
        </div>
        <div v-show="tabs == 'chapters'" class="chapters">
            <slot name="chapters"></slot>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tabs: "summary"
        }
    }
}
</script>