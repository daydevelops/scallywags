
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vue.prototype.authorize = function (handler) {
	if (! window.App.user) {
		return false;
	}
	return handler(window.app.user);
}
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const files = require.context('./', true, /\.vue$/i)

// files.keys().map(key => {
//     return Vue.component(_.last(key.split('/')).split('.')[0], files(key))
// })

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('notification',require('./components/Notification.vue'));
Vue.component('thread',require('./pages/Thread.vue'));
Vue.component('favourite',require('./components/Favourite.vue'));
Vue.component('paginator',require('./components/Paginator.vue'));
Vue.component('subscribe-button',require('./components/subscribe-button.vue'));
Vue.component('avatar-form',require('./components/AvatarForm.vue'));
const app = new Vue({
    el: '#app',
});
