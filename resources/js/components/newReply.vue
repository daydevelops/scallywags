<template>
	<div>
		<div v-if="signedIn">
			<!-- <div v-if="!is_visible" id='new-reply-btn' class="row">
				<div class="col-12">
					<button class="btn btn-primary m-auto d-block" @click='is_visible=true'>New Reply</button>
				</div>
				<br><br>
			</div> -->
			<div id="new-reply-wrap">
				<p class="text-center" v-text="errors"></p>
				<div class="row">
					<div class="col-8 offset-2">
						<div class="form-group">
							<textarea
							class='form-control'
							name="body"
							id="body"
							rows="5"
							placeholder="Have something to say?"
							v-model="body"
							required></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-8 offset-2 text-right">
						<div class='form-group'>
							<button type='button' class="btn btn-primary d-inline m-auto" @click="addReply()">Submit</button>
							<button type='button' class="btn btn-danger d-inline m-auto" @click="is_visible=false">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<p v-if="!signedIn" class='text-center'>Please <a href='/login'>sign in</a> to comment</p>
	</div>
</template>

<script>
import 'jquery.caret';
import 'at.js';

export default {
	data() {
		return {
			body:"",
			endpoint:location.pathname+'/reply',
			is_visible:true,
			errors:""
		}
	},
	computed: {
		signedIn() {
			return window.App.signedIn
		}
	},
	mounted() {
		$('#body').atwho({
			at:'@',
			delay:750,
			callbacks: {
				remoteFilter: function(query, callback) {
					$.getJSON("/api/users",{name: query}, function(usernames) {
						callback(usernames);
					})
				}
			}
		});
	},
	methods: {
		addReply() {
			axios.post(this.endpoint,{body:this.body})
			.then(
				(response) => {
					this.body = "",
					// console.log(response.data)
					this.$emit('created',response.data)
					this.is_visible = false;
					this.errors = "";
				},
				(error) => {
					// debugger
					this.errors = error.response.data;
				}
			)
		}
	}
}
</script>
