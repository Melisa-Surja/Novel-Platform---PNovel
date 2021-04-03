<template>
    <a :href="siteURL" class="flex items-center text-gray-300 hover:text-white">
        <!-- <img class="h-8 w-8 flex-shrink-0 mr-2" src="https://tailwindui.com/img/logos/v1/workflow-mark-on-dark.svg" alt="Workflow logo"> -->
        <span class="hidden md:block">{{siteName}}</span>
    </a>
</template>

<script>
export default {
    data() {
        return {
            siteURL: site_url,
            siteName: site_name
        }
    }
}
</script>