<template>
<div>
    <v-select 
        :options="options"
        v-model="data" 
        :autoscroll="false"
        :selectable="tag => !data.includes(tag)"
        taggable multiple push-tags></v-select>
        <!-- :selectable="tag => !(data.includes(tag.label)||isDuplicateName(tag.label))" -->
    <input type="hidden" name="tags" v-model="data" />
    <div class="hidden v-select vs--searchable"> 
        <div class="vs__dropdown-toggle">
            <div class="vs__selected-options">
                <span class="vs__selected">
                <button class="vs__deselect"></button>
                </span>
                <input type="search" class="vs__search">
            </div> 
            <div class="vs__actions">
                <button type="button" class="vs__clear"></button> 
                <div class="vs__spinner"></div>
            </div>
        </div> 
        <ul></ul> 
    </div>
</div>
</template>

<script>
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
var existingNames = [];
export default {
    components: {
        vSelect
    },
    props: {
        options: Array,
        selected: {type: Array, default: []}
    },
    methods: {
        // isDuplicateName: function(newLabel) {
        //     return existingNames.includes(newLabel);
        // }
    },
    data() {
        return {
            data: this.selected,
        }
    },
    mounted() {
        console.log(this.options, this.selected);
        // existingNames= this.options;
    }
}
</script>