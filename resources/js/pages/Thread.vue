<script type="text/javascript">
import replies from "../components/Replies.vue"
export default {
	props: ['initial_replies_count','thread'],
	components: {replies},
	data() {
		return {
			replies_count:this.initial_replies_count,
			editing: false,
			form_data: {
				title:this.thread.title,
				body:this.thread.body
			}
		}
	},
	methods: {
		updateThread() {
			axios.patch('/forum/'+this.thread.category.slug+'/'+this.thread.slug,this.form_data)
			.then(
				(response) => {
					console.log(response.data);
					location.reload();
				},
				(error) => {
					this.errors = error.response.data;
				}
			)
		}
	}
}
</script>
