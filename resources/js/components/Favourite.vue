<template>
	<div v-bind:class="{favourited:favourited}">
		<i class="fas fa-heart" @click='toggle(event)'></i>
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
			event.stopPropagation(); 
			var identifier = this.type=='thread' ? this.item.slug : this.item.id;
			if (this.favourited) {
				axios.delete('/favourite/'+this.type+'/'+identifier)
				.then(response => {
					this.favourited = 0;
				})
				.catch(errors => {
					console.log(errors);
				})
			} else {
				axios.post('/favourite/'+this.type+'/'+identifier)
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
}
.favourite-wrapper.favourited {
	color:#A33;
}
.favourite-wrapper > i:hover {
	text-shadow:0px 0px 3px black;
}
</style>
