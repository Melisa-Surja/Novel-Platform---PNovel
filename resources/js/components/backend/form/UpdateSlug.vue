<template>
    <div class="mb-6">
        <backend-form-label name="slug" class="mb-1" required>Series Slug (Link)</backend-form-label>
        <notice v-show="errors.length > 0" type="error">
            <li v-for="(error, i) in errors" :key="i">{{error}}</li>
        </notice>
        <div class="flex sm:items-center flex-col sm:flex-row text-xs">
            <div class="flex items-center">
                <p class="flex-shrink-0">
                    <span v-if="edit">{{route}}</span>
                    <a v-else :href="full_route">{{full_route}}</a>
                </p>
                <input v-if="edit" name="slug" class="form-input block text-xs p-1 w-full" placeholder="Slug link" v-model="the_slug">
            </div>
            <button @click.prevent="clickButton()" class="sm:ml-2 sm:mt-0 mt-1 text-xs py-1 px-2">{{this.edit ? "Save" : "Edit"}}</button>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        route: {type: String, required: true},
        slug: {type: String, required: true},
        submit: {type: String, required: true}
    },
    data() {
        return {
            edit: false,
            the_slug: this.slug,
            prev_slug: this.slug,
            errors: []
        }
    },
    computed: {
        full_route: function() {
            return this.route + this.the_slug;
        }
    },
    methods: {
        clickButton() {
            var t = this;
            if (this.edit) {
                // submit the slug
                axios.patch(
                    this.submit, {slug: this.the_slug}
                )
                .then(response => {
                    this.errors = [];
                    t.prev_slug = t.the_slug;
                    this.edit = !this.edit;

                    this.$eventBus.$emit("update_preview_link", this.full_route);
                })
                .catch(error => {
                    if (error.response.data.errors) {
                        this.errors = Object.values(error.response.data.errors).flat(1);
                    }
                    // revert slug to previous slug
                    t.the_slug = t.prev_slug;
                });
            } else 
                this.edit = !this.edit;
        }
    }
}
</script>