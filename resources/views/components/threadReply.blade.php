<reply :attributes='{{$r}}' inline-template v-cloak>
	<div class="reply" id='reply-{{$r->id}}'>
		<div class="row reply-header">
			<div class="col-8">
				<b><small><a href='/profile/{{$r->thread->user->id}}'>{{$r->user->name}}</a> | {{$r->created_at->diffForHumans()}}</small></b>
			</div>
			<div class="col-4 text-right">
				@auth
					@if(!$r->deleted)
						@can('favourite',$r)
							<favourite :item="{{$r}}" :type="'reply'" class='favourite-wrapper'></favourite>
						@endcan
					@endif
				@endauth
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div v-if='editing && !deleted'>
					<div class="form-group">
					<textarea class='form-control' v-model='body'></textarea>
						<button class="btn btn-primary" @click='update'>Update</button>
						<button class="btn btn-secondary" @click='cancelEdit'>Cancel</button>
					</div>
				</div>
				<div class='reply-body' v-else>
					<p v-text='body'></p>
					<div class="level" v-if='!deleted'>
						@can('update',$r)
							<button class="btn btn-secondary" @click='editing = true'>Edit</button>
							<button class='btn btn-danger' @click='destroy' >Delete</button>
						@endcan
					</div>
				</div>
			</div>
		</div>
	</div>
</reply>
