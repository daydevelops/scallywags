<script>
	export default {
		props: ['attributes'],
		data() {
			return {
				editing:false,
				deleted:this.attributes.deleted,
				body:this.attributes.body
			}
		},
		methods: {
			update() {
				axios.patch('/forum/reply/'+this.attributes.id,{body: this.body}).bind(this)
				.then(response => {
					console.log(response);
				})
				.catch(errors => {
					console.log(errors);
				})
			},
			destroy() {
				// showAYSM("delete","reply",this.attributes.id,"/forum/reply/{{$r->id}}")
				axios.delete('/forum/reply/'+this.attributes.id)
				.then(response => {
					console.log(response)
					this.body = response.data.body;
					this.deleted = 1;
				})
				.catch(errors => {
					console.log(errors);
				})
			},
			cancelEdit() {
				this.body = this.attributes.body;
				this.editing = false;
			}
		}
	}
</script>
