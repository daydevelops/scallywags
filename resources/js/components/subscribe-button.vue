<template>
	<button
	class="btn"
	:class="subscribed?'btn-warning':'btn-primary'"
	@click="toggleSubscribed"
	v-text="subscribed?'unsubscribe':'subscribe'">
	</button>
</template>

<script>
export default {
	props: ['init_subscribed'],
	data() {
		return {
			subscribed: this.init_subscribed,
		}
	},
	methods: {
		toggleSubscribed() {
			this.subscribed ? this.unsubscribe() : this.subscribe();
		},
		subscribe() {
			axios.post(location.pathname+'/subscribe')
			.then(response => {
				this.subscribed = 1;
			})
		},
		unsubscribe() {
			axios.delete(location.pathname+'/unsubscribe')
			.then(response => {
				this.subscribed = 0;
			})
		}
	}
}
</script>
