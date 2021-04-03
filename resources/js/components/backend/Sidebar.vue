<template>
    <div class="bg-cool-gray-900 shadow-2xl z-10">
      <!-- Desktop sidebar -->
      <aside
        class="z-20 hidden w-48 lg:w-64 overflow-y-auto md:block flex-shrink-0"
      >
        <div class="h-16 flex items-center px-4 text-gray-300 font-semibold"><logo></logo></div>
        <backend-sidebar-content>
            <slot></slot>
        </backend-sidebar-content>
      </aside>

      <!-- Mobile sidebar -->
      <transition
        enter-active-class="transition ease-in-out duration-150"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in-out duration-150"
        leave-class="opacity-100"
        leave-to-class="opacity-0">
        <div
            v-show="sideMenuOpen"
            @click="sideMenuToggle()"
            class="fixed inset-0 z-10 mt-16 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
        ></div>
      </transition>
        <transition
            enter-active-class="transition ease-in-out duration-150"
            enter-class="opacity-0 transform -translate-x-20"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in-out duration-150"
            leave-class="opacity-100"
            leave-to-class="opacity-0 transform -translate-x-20"
        >
            <aside
                class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-cool-gray-900 md:hidden"
                v-show="sideMenuOpen"
                @keydown.escape="sideMenuToggle()"
            >
                <backend-sidebar-content>
                    <slot></slot>
                </backend-sidebar-content>
            </aside>
        </transition>
    </div>
</template>


<script>
export default {
    mounted() {
        this.$eventBus.$on("sidemenu_toggle", () => {
            this.sideMenuOpen = !this.sideMenuOpen;
        });
    },
    data() {
        return {
            sideMenuOpen: false,
            sideMenuCanOpen: false
        }
    },
    watch: {
    },
    methods: {
        sideMenuToggle() {
            this.sideMenuOpen = !this.sideMenuOpen;
            this.$eventBus.$emit("sidemenu_toggle_button");
        },
    }
}
</script>