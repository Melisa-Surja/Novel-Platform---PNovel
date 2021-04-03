<template>
    <div :class="'theme-'+theme + (sideMenuOpen ? 'overflow-hidden' : '')">
        <slot></slot>
    </div>
</template>


<script>
    export default {
        mounted() {
            this.$eventBus.$on("theme_change", newTheme => {
                this.theme = newTheme;
            });
            this.$eventBus.$on("sideMenu_toggle", newVal => {
                this.sideMenuOpen = newVal;
            });
        },
        data() {
            return {
              theme: 'day',
              sideMenuOpen: false
            }
        },
    }
</script>