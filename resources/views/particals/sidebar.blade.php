<div id="sidebar" class='mt-2 p-2 '>
	<h3 class='text-center'>Welcome to ScallyWags!</h3>
	<p class='text-center'><small>An open source forum for those wags that like to scally.</small></p>
	<hr>
	<a class='btn btn-primary btn-lrg d-block' href='/forum/new'>Submit New Post</a>
	<hr>
	<h4 class='text-center'><b>Rules</b></h4>
	<small>
		<ol class="pl-3">
			<li>Profanity prohibited</li>
			<li>Please do not post anything deemed NSFW</li>
			<li>Do not post spam or marketing links unless explicitly asked</li>
			<li>Be friendly</li>
		</ol>
	</small>
	<hr>
	<h4 class='text-center'><b>Trending Threads</b></h4>
	@foreach ($trending_threads as $tt)
		<p class='trending-thread text-left'><a href="{{$tt->path}}">{{$tt->title}}</a></p>
	@endforeach
</div>
