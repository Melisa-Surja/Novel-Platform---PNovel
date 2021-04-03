<template>
    <div>
        <h5 class="mb-2">Reply to this comment:</h5>
        <div class="flex mb-8 bg-bg p-4 rounded-lg">
            <img class="lazyload md:w-12 md:h-12 w-8 h-8 rounded-full flex-shrink-0 mr-2 md:mr-3" :data-src="avatar" />
            <div>
                <div class="md:mt-1 flex items-baseline">
                    <h5 class="font-semibold">{{ username }}</h5>
                    <small class="ml-2 text-xs text-gray-400">{{ date }}</small>
                </div>
                <div class="mt-1 md:mt-2 text-gray-400 text-sm whitespace-pre-wrap"><slot name="comment"></slot></div>
            </div>
        </div>

        <div class="-mb-8">
            <comment-form 
                :header="false"
                :action="form_action" 
                :guest_commenting="guest_commenting"
                >
                <slot name="comment_form"></slot>
            </comment-form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        action: String,
        guest_commenting: { 
            type: Boolean,
            default: false
        }
    },
    computed: {
        form_action: function() {
            return this.action.replace("1453463636434636", this.id);
        }
    },
    data() {
        return {
            id: "",
            avatar: "",
            username: "",
            date: "",
            comment: ""
        }
    },
    mounted() {
        var t = this;
        this.$eventBus.$on('comment_replyTo', c => {
            t.id = c.id;
            t.avatar = c.avatar;
            t.username = c.username;
            t.date = c.date;
            t.$slots.comment = c.comment;
        });
    }
}
</script>