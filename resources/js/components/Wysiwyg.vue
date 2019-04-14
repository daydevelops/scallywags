<template>
	<div class='wysiwyg-wrap'>
		<input id="editor" :name="name" type="hidden" :value="content">
		<trix-editor ref='trix' input="editor" @trix-change="changed"></trix-editor>
	</div>
</template>

<script>
import Trix from 'trix';
Vue.config.ignoredElements = ['trix-editor'];
export default {
	props: ['name','initial_content'],
	data() {
		return {
			content:this.initial_content
		}
	},
	methods: {
			changed() {
				this.$emit('input', this.$refs.trix.innerHTML);
			}
		}
}
</script>
<style>

.wysiwyg-wrap {
	background-color: #EEEEEE99;
	padding:20px
}
</style>
