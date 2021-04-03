<template>
<div>
    <div class="ql-viewer select-none" v-html="html" oncontextmenu="return false"></div>
</div>
</template>


<script>
export default {
    props: {
        type: String,
        content: String
    },
    data() {
        return {
            html: ""
        }
    },
    mounted() {
        if (this.type != 'text') {
            var cfg = {};
            var content = JSON.parse(this.content).ops;

            // replace and make attributes data-popup to become custom blots
            content = content.map(obj => {
                if (obj.hasOwnProperty("attributes") && obj.attributes.hasOwnProperty("popup")) {
                    return {
                        insert: {
                            popup: {
                                text: obj.insert,
                                note: obj.attributes.popup
                            },
                            attributes: obj.attributes
                        }
                    }
                }
                return obj;
            });

            var converter = new QuillDeltaToHtmlConverter(content, cfg);
            
            converter.renderCustomWith(function(customOp, contextOp){
                if (customOp.insert.type === 'popup') {
                    let val = customOp.insert.value;
                    return `<span class="tooltip underline cursor-pointer" data-tippy-content="${val.note}">${val.text}</span>`;
                }
            });
            
            this.html = converter.convert().replace(/<br\/>/g, "</p><p>"); 
            console.log(content);

            // tooltip
            this.$nextTick(() => {
                tippy('.tooltip', {
                    trigger: 'click',
                });
            });
        } else {
            this.html = this.content.split("\n").map(p=> "<p>" + p + "</p>").join("");
        }
    }
}
</script>