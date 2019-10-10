<template>
	<div :class="is_best?'best-reply reply':'reply'" :id="'reply'+data.id">
		<div class="row reply-header">
			<div class="col-8">
				<img :src="data.user.image" class='user-thumbnail'>
				<b><small><a :href="'/profile/'+data.user.id" v-text="data.user.name"></a> | <span v-text="ago"></span></small></b>
			</div>
			<div class="col-4 text-right" v-if="signedIn">
				<favourite :item="data" :type="'reply'" class='favourite-wrapper'></favourite>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div v-if='editing'>
					<div class="form-group">
						<p class="text-center" v-text="errors"></p>
						<wysiwyg ref='edit_reply_wysiwyg' v-model="body" :initial_content="body" :name="'body'"></wysiwyg>
						<!-- <textarea class='form-control' v-model='body'></textarea> -->
						<button class="btn btn-primary" @click='update'>Update</button>
						<button class="btn btn-secondary" @click='cancelEdit'>Cancel</button>
					</div>
				</div>
				<div class='reply-body' v-else>
					<p v-html='body'></p>
					<div class="level">
						<span v-if='canEdit()'>
							<button class="btn btn-secondary" @click='editing = true'>Edit</button>
							<button class='btn btn-danger' @click='destroy' >Delete</button>
						</span>
						<span v-if='canMarkAsBest()'>
							<button class='btn btn-success mark-as-best-btn' @click='markAsBest' >Mark As Best</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import moment from 'moment';
export default {
	props: ['data','best_id'],
	data() {
		return {
			editing:false,
			body:this.data.body,
			errors:"",
			is_best:0
		}
	},
	computed: {
		signedIn() {
			return window.App.signedIn
		},
		ago() {
			return moment(this.data.created_at + 'z', 'YYYY-MM-DD HH:mm:ss Z').fromNow();
		}
	},
	created () {
		this.is_best = this.best_id == this.data.id;
	},
	methods: {
		update() {
			axios.patch('/forum/reply/'+this.data.id,{body: this.body})
			.then(
				(response) => {
					console.log(response.data)
					this.editing = false;
					this.data.body = this.body;
				},
				(error) => {
					this.errors = error.response.data;
				}
			)
		},
		destroy() {
			axios.delete('/forum/reply/'+this.data.id)
			.then(response => {
				this.$emit('deleted',this.data.id);
			})
			.catch(errors => {
				console.log(errors);
			})
		},
		cancelEdit() {
			this.body = this.data.body;
			this.editing = false;
		},
		canEdit() {
			return this.authorize(user => this.data.user_id == window.App.user.id);
		},
		markAsBest() {
			$('.mark-as-best-btn').addClass('hidden');
			this.is_best = 1;
			axios.post('/forum/reply/'+this.data.id+'/best')
			.then(
				(response) => {

				},
				(error) => {
					console.log(error);
				}
			);
		},
		canMarkAsBest() {
			return this.best_id==0 && window.is_thread_owner;
		}
	}
}
</script>
