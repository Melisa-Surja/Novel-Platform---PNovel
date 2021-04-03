<template>
  <li class="relative px-6 py-3 flex items-center">
    <span v-if="current" class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg" aria-hidden="true"></span>
    <a
      class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150"
      :href="href" :class="current ? 'text-gray-200 hover:text-gray-200' : 'text-gray-400 hover:text-gray-200'"
    >
      <component :is="icon" size="1.5x" />
      <span class="ml-4">{{title}}</span>
    </a>
  </li>
</template>


<script>
import { ViewGridIcon } from "@vue-hero-icons/outline";
import { BookOpenIcon } from "@vue-hero-icons/outline";
import { DocumentTextIcon } from "@vue-hero-icons/outline";
import { CogIcon } from "@vue-hero-icons/outline";
import { UserGroupIcon } from "@vue-hero-icons/outline";
import { IdentificationIcon } from "@vue-hero-icons/outline";
import { ChatAlt2Icon } from "@vue-hero-icons/outline";
import { TagIcon } from "@vue-hero-icons/outline" 

export default {
  components: {
    ViewGridIcon, TagIcon, ChatAlt2Icon, BookOpenIcon, DocumentTextIcon, UserGroupIcon,IdentificationIcon, CogIcon
  },
  props: {
    href: String,
    title: String,
    icon: String,
    current: Boolean
  }
}
</script>