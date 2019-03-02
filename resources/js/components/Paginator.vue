<template>
	<div v-if="shouldPaginate">
		<ul class="pagination">
			<li class="page-item" v-show="prevURL" @click.prevent="page--">
				<a class="page-link" href="#" aria-label="Previous" rel="prev">
					<span aria-hidden="true">&laquo; Previous</span>
				</a>
			</li>
			<li class="page-item" v-show="nextURL" @click.prevent="page++">
				<a class="page-link" href="#" aria-label="Next" rel="next">
					<span aria-hidden="true">Next &raquo;</span>
				</a>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	props: ['data'],
	data() {
		return {
			page: 1,
			prevURL: false,
			nextURL: false
		}
	},
	watch: {
		data() {
			this.page = this.data.current_page;
			this.prevURL = this.data.prev_page_url;
			this.nextURL = this.data.next_page_url;
		},
		page() {
			this.newPage();
			this.updateURL();
		}
	},
	computed: {
		shouldPaginate() {
			return !!this.nextURL || !!this.prevURL;
		}
	},
	methods: {
		newPage() {
			this.$emit('newPage',this.page);
		},
		updateURL() {
			history.pushState(null,null,'?page='+this.page);
		}
	}
}
</script>
