<template>
    
<div :id="'comment-'+id" class="comment" :class="indentation > 1 ? 'md:ml-12 ml-6' : ''">
    <div class="pb-4 border-b border-gray-500 mb-4">

        <!-- Parent preview if it's a reply -->
        <slot name="parent"></slot>

        <!-- Begin Comment Content -->
        <div class="flex">
            <img class="lazyload md:w-12 md:h-12 w-8 h-8 rounded-full flex-shrink-0 mr-2 md:mr-3" :data-src="avatar" />
            <div>
                <!-- Comment Info -->
                <div class="md:mt-1 flex items-baseline">
                    <h5 class="font-semibold">{{ username }}</h5>
                    <small class="ml-2 text-xs text-gray-400">{{ date }}</small>
                </div>
                <!-- Comment Content -->
                <div class="mt-1 md:mt-2 text-gray-400 text-sm whitespace-pre-wrap"><slot name="comment"></slot></div>
            </div>
        </div>

        <!-- Comment Actions: Report, Reply, etc -->
        <div class="action flex items-center justify-end px-2 -mb-2 mt-2">
            <!-- <a class="text-gray-400 text-xs mr-2 cursor-pointer select-none">Report</a> -->
            <a class="text-gray-400 text-xs cursor-pointer select-none" @click="replyTo()">Reply</a>
        </div>
    </div>

    <!-- Recursion for children -->
    <slot name="children"></slot>
</div>

</template>


<script>
export default {
    props: {
        id: String,
        avatar: String,
        username: String,
        indentation: Number,
        date: String
    },
    methods: {
        replyTo() {
            var t = this;
            this.$eventBus.$emit('comment_replyTo', {
                id: t.id,
                avatar: t.avatar,
                username: t.username,
                date: t.date,
                comment: t.$slots.comment
            });
            this.$eventBus.$emit('modal_open_comment_modals');
        }
    }
}
</script>