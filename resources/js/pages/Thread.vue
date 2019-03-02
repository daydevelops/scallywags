<script type="text/javascript">
import replies from "../components/Replies.vue"
export default {
	props: ['initial_replies_count'],
	components: {replies},
	data() {
		return {
			replies_count:this.initial_replies_count
		}
	}
}
</script>
