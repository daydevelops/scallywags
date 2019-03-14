<template>
<div class="">
		<input id="new-user-img" type="file" name="avatar" accept="image/*">
		<button type='submit' @click="uploadImage">upload</button>
	<img id='user-image' :src="path" v-on:mouseover="showButton" v-on:mouseleave="hideButton">
</div>
</template>

<script>
export default {
	props: ['user'],
	data() {
		return {
			path:'/storage/'+this.user.image
		}
	},
	methods: {
		uploadImage() {
			let input_elem = document.querySelector('#new-user-img')
			if (!input_elem.files.length) return;

			let avatar = input_elem.files[0];

			let data = new FormData();
			data.append('avatar',avatar);
			axios.post('/profile/avatar',data)
			.then(
				(response) => {
					this.path = '/storage/'+response.data;
				},
				(error) => {
					console.log(error);
				}
			)
		},
		testing() {
			console.log('hay');
		}
	}
}

</script>
