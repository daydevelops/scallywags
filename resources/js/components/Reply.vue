<script>
	export default {
		props: ['attributes'],
		data() {
			return {
				editing:false,
				body:this.attributes.body
			}
		},
		methods: {
			update() {
				axios.patch('/forum/reply/'+this.attributes.id,{body: this.body})
				.then(response => {
					console.log(response);
					this.editing = false;
				})
				.catch(errors => {
					console.log(errors);
				})
			}
		}
	}
</script>
