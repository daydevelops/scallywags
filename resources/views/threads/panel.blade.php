<div class="thread border-bottom my-2 p-3" onclick='window.location="{{$thread->getPath()}}"'>
    <div class="row thread-header">
        <div class="col-auto text-left">
            <div>
                <a href='{{$thread->user->getPath()}}'>
                    <img src='{{$thread->user->image}}' class='user-thumbnail'>
                </a>
            </div>
        </div>
        <div class='col text-left'>
            <h2 class='thread-title'>
                @if ($thread->hasBeenUpdated())
                    <b>{{$thread->title}}</b>
                @else
                    {{$thread->title}}
                @endif
            </h2>
            <div class="help-block thread-details">
                Posted by: <a class="text-dark" href="{{$thread->user->getPath()}}">{{$thread->user->name}}</a> {{ $thread->created_at->diffForHumans() }}
                <div class="badge badge-info"><a class="text-dark" href="/forum/{{ $thread->category->slug }}">{{ $thread->category->name }}</a></div>
            </div>
            @if ($thread->is_locked)
                <span class="font-weight-light">
                    <i class="fas fa-lock" style="font-size: 0.9rem;"></i>
                    <span style="font-size: 0.9rem;">Locked</span>
                </span>
            @endif
            @if ($thread->is_pinned)
                <span class="font-weight-light">
                    <i class="fas fa-thumbtack" style="font-size: 0.9rem;"></i>
                    <span style="font-size: 0.9rem;">Pinned</span>
                </span>
            @endif
        </div>
        <div class="col-1 text-right p-1">
            @can('favourite',$thread)
                <favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper'></favourite>
            @endcan
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <p class='thread-body text-secondary pt-2'>{!! str_limit($thread->body,400, '...</div>') !!}</p>
        </div>
    </div>
    <div class="row">
        <div class='col-12 text-left'>
            <small>
            <i class="fas fa-comment thread-meta-icon"></i>{{$thread->replies_count}}
            <i class="fas fa-eye thread-meta-icon"></i>{{ $thread->visits }}
            </small>
        </div>
    </div>
</div>
