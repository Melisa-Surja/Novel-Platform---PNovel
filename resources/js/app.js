/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('container', require('./components/Container.vue').default);
Vue.component('logo', require('./components/Logo.vue').default);
Vue.component('icon', require('./components/Icon.vue').default);
Vue.component('notice', require('./components/Notice.vue').default);
Vue.component('modals', require('./components/Modals.vue').default);
Vue.component('header-profile', require('./components/frontend/HeaderProfile.vue').default);
Vue.component('button-start-reading', require('./components/frontend/StartReading.vue').default);
Vue.component('button-add-reading-list', require('./components/frontend/AddToReadingList.vue').default);
Vue.component('series-info-tabs', require('./components/frontend/SeriesInfoTabs.vue').default);
Vue.component('chapter-content', require('./components/frontend/ChapterContent.vue').default);
Vue.component('chapters-list', require('./components/frontend/ChaptersList.vue').default);
Vue.component('chapters-list-trigger', require('./components/frontend/ChaptersListTrigger.vue').default);
Vue.component('notification-item', require('./components/frontend/NotificationItem.vue').default);
Vue.component('image-notfound', require('./components/ImageNotFound.vue').default);
Vue.component('big-header', require('./components/frontend/BigHeader.vue').default);

Vue.component('comment-form', require('./components/frontend/Comment/Form.vue').default);
Vue.component('comment-body', require('./components/frontend/Comment/Body.vue').default);
Vue.component('comment-reply-to', require('./components/frontend/Comment/ReplyTo.vue').default);


/* 
 * Backend
 */
Vue.component('backend-sidebar', require('./components/backend/Sidebar.vue').default);
Vue.component('backend-sidebar-content', require('./components/backend/SidebarContent.vue').default);
Vue.component('backend-sidebar-link', require('./components/backend/SidebarLink.vue').default);
Vue.component('backend-header', require('./components/backend/Header.vue').default);

// Card
Vue.component('backend-section-header', require('./components/backend/SectionCard/SectionHeader.vue').default);
Vue.component('backend-section-fixed-save', require('./components/backend/SectionCard/SectionFixedSave.vue').default);
Vue.component('backend-section-card', require('./components/backend/SectionCard/SectionCard.vue').default);
Vue.component('backend-section-card-header', require('./components/backend/SectionCard/SectionCardHeader.vue').default);
Vue.component('backend-section-card-footer', require('./components/backend/SectionCard/SectionCardFooter.vue').default);

// Form
Vue.component('backend-form-label', require('./components/backend/form/Label.vue').default);
Vue.component('backend-form-desc', require('./components/backend/form/Description.vue').default);
Vue.component('backend-form-input', require('./components/backend/form/Input.vue').default);
Vue.component('backend-form-textarea', require('./components/backend/form/Textarea.vue').default);
Vue.component('backend-form-select', require('./components/backend/form/Select.vue').default);
Vue.component('backend-form-tags', require('./components/backend/form/Tags.vue').default);
Vue.component('backend-form-submit', require('./components/backend/form/Submit.vue').default);
Vue.component('backend-form-update-slug', require('./components/backend/form/UpdateSlug.vue').default);
Vue.component('backend-form-preview-button', require('./components/backend/form/PreviewButton.vue').default);
Vue.component('backend-create-new', require('./components/backend/form/CreateNew.vue').default);

// User Role
Vue.component('backend-role', require('./components/backend/UserRole/RoleSelect.vue').default);
Vue.component('backend-permissions', require('./components/backend/UserRole/Permissions.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.prototype.$eventBus = new Vue(); // Global event bus
const app = new Vue({
    el: '#app',
});

import 'lazysizes';
// import a plugin
import 'lazysizes/plugins/parent-fit/ls.parent-fit';


import ImageCompress from 'quill-image-compress';
window.ImageCompress = ImageCompress;