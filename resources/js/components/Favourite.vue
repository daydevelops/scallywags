<template>
	<div v-bind:class="{favourited:favourited}">
		<i class="fas fa-heart" @click='toggle()'></i>
	</div>
</template>
<script>
export default {
	props: ['item','type'],
	data() {
		return {
			favourited:this.item.is_favourited,
		}
	},
	methods: {
		toggle() {
			if (this.favourited) {
				axios.delete('/favourite/'+this.type+'/'+this.item.id)
				.then(response => {
					this.favourited = 0;
				})
				.catch(errors => {
					console.log(errors);
				})
			} else {
				axios.post('/favourite/'+this.type+'/'+this.item.id)
				.then(response => {
					this.favourited = 1;
				})
				.catch(errors => {
					console.log(errors);
				})
			}
		},

	}
}
</script>

<style>
.favourite-wrapper {
	color:#999;
	padding:15px;
}
.favourite-wrapper.favourited {
	color:#A33;
}
.favourite-wrapper > i:hover {
	text-shadow:0px 0px 3px black;
}
</style>
