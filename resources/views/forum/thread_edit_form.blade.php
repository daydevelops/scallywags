<div v-if='editing' class="thread">
	<div class="form-group">
		<div class="row">
			<div class="col-12">
				<input type="text" class="form-control" name='title' id="title" v-model="form_data.title">
			</div>
		</div>
	</div>
	<div class="form-group">
		<textarea class="form-control" name='body' id="body" rows="10" v-model="form_data.body"></textarea>
	</div>
	<div class='form-group'>
		<button type='submit' class="btn btn-primary d-inline m-auto" @click="updateThread()">Save</button>
		<button class="btn btn-danger d-inline m-auto" @click="editing=false">Cancel</button>
	</div>
</div>
