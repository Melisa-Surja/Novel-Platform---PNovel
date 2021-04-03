<template>
<div>
    <a v-if="guest" :href="login" class="px-3 py-2 rounded-md text-sm font-bold  focus:outline-none bg-gray-100 bg-opacity-5 text-white text-opacity-90 hover:text-opacity-100 hover:bg-opacity-10" @click.prevent="doLogin()">Login</a>

    <div v-else class="ml-3 flex items-center">

      <!-- Bell Notification -->
      <!-- <a :href="notification_url" class="relative button p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white mr-2 hover:bg-gray-800">
        <span class="sr-only">View notifications</span>
        <BellIcon size="1.5x"></BellIcon> -->
        
        <!-- If unread notification exist, remove this tick -->
        <!-- <span v-if="new_notifications" class="absolute top-0.5 right-0.5 inline-block w-3 h-3 bg-green-600 border-2 border-gray-900 rounded-full"></span>
      </a> -->

      <div class="relative">
        <button class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid p-0" id="user-menu" aria-label="User menu" aria-haspopup="true" @click.stop="dropdown = !dropdown">
          <img class="h-8 w-8 rounded-full lazyload" :data-src="avatar" />
        </button>
        <transition
        enter-active-class="transition ease-out duration-200"
        enter-class="transform opacity-0 -translate-y-1"
        enter-to-class="transform opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-class="transform opacity-100 translate-y-0"
        leave-to-class="transform opacity-0 -translate-y-1">
            <div v-show="dropdown" class="z-10 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg" v-click-outside="closeDropdown">
                <div class="py-1 rounded-md bg-bg shadow-xl" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                    
                    <header-profile-button v-if="dashboard" :href="dashboard">
                      <ViewGridIcon size="1x" class="w-6"></ViewGridIcon>
                      Dashboard
                    </header-profile-button>
                    
                    <header-profile-button v-if="profile" :href="profile">
                      <UserIcon size="1x" class="w-6"></UserIcon>
                      Edit Profile
                    </header-profile-button>
                    
                    <header-profile-button v-if="reading_list" :href="reading_list">
                      <ClipboardListIcon size="1x" class="w-6"></ClipboardListIcon>
                      Reading List
                    </header-profile-button>

                    <template v-if="logout">
                      <header-profile-button :href="logout" @click.native.prevent="submitLogout()">
                        <LogoutIcon size="1x" class="w-6"></LogoutIcon>
                        Sign Out
                      </header-profile-button>

                      <form id="logout-form" :action="logout" method="POST" class="hidden">
                          <slot name="csrf"></slot>
                      </form>
                    </template>
                </div>
            </div>
        </transition>
      </div>
    </div>
</div>
</template>

<script>
import vClickOutside from 'v-click-outside'
import Cookies from 'js-cookie'
import { ViewGridIcon } from "@vue-hero-icons/outline" 
import { ClipboardListIcon } from "@vue-hero-icons/outline" 
import { LogoutIcon } from "@vue-hero-icons/outline" 
import { BellIcon } from "@vue-hero-icons/outline" 
import { UserIcon } from "@vue-hero-icons/outline" 
var HeaderProfileButton = {
  props: ['href'],
  template: '<a class="px-4 py-2 text-sm text-gray-300 hover:bg-bg-light flex items-center justify-start" role="menuitem" :href="href"><slot></slot></a>'
};
export default {
  directives: {
    clickOutside: vClickOutside.directive
  },
  components: {
    'header-profile-button': HeaderProfileButton,
    ViewGridIcon, ClipboardListIcon, LogoutIcon, BellIcon, UserIcon
  },
  props: {
    guest: Boolean,
    login: String,
    logout: String,
    avatar: String,
    dashboard: String,
    profile: String,
    reading_list: String,
    notification_url: String,
    new_notifications: Number
  },
  data() {
    return {
      dropdown: false
    }
  },
  methods: {
      submitLogout() {
        Cookies.set(
            'redirect_login', 
            window.location.href, 
            { expires: 1, path: '/' }
            );
        document.getElementById('logout-form').submit();
      },
      closeDropdown() {
        if (this.dropdown) this.dropdown = false;
      },
      doLogin() {
        // set cookie
        Cookies.set(
            'redirect_login', 
            window.location.href, 
            { expires: 1, path: '/' }
            );
        window.location.href = this.login;
      }
  },
  mounted() {
    // get cookie
    let redirect = Cookies.get('redirect_login', { path: '/' });
    if (redirect) {
      Cookies.remove('redirect_login', { path: '/' });
        if (window.location.href != redirect)
            window.location.href = redirect;
    }
  }
}
</script>