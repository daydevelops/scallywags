<template>
	<div>
		<newReply @created="add"></newReply>
		<div v-for="(reply, index) in items" :key='reply.id'>
			<reply :data="reply" @deleted="remove(index)"></reply>
		</div>
		<paginator :data="data" @newPage="fetch"></paginator>
	</div>
</template>

<script>
import reply from './Reply.vue';
import newReply from './newReply.vue';
export default {
	props:['page'],
	components: {reply, newReply},
	data() {
		return {
			items: [],
			data: []
		}
	},
	created() {
		this.fetch(this.page);
	},
	methods: {
		fetch(page=1) {
			axios.get(location.pathname+'/replies/?page='+page)
			.then(response => {
				this.data = response.data;
				this.items = response.data.data;
			})
			.then(()=> {
				window.scrollTo(0,0);
			})
		},
		remove (index) {
			// alert('removed');
			this.items.splice(index,1);
			this.$emit('removed');
		},
		add(reply) {
			console.log(reply);
			this.items.push(reply);
			this.$emit("add");
		}
	}
}
</script>
