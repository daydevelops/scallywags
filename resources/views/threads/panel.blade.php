<div class="thread panel" onclick='window.location="{{$thread->getPath()}}"'>
    <div class="row thread-header">
        <div class="col-3 text-left">
            <div>
                <img src='{{$thread->user->image}}' class='user-thumbnail'>
            </div>
            <div>
                <a href='{{$thread->user->getPath()}}'>{{$thread->user->name}}</a></div>
            <div>
                <small><span>posted in: </span>{{$thread->category->name}}</span></small>
            </div>
        </div>
        <div class='col-8 text-left'>
            <h2 class='thread-title'>
                @if ($thread->hasBeenUpdated())
                    <b>{{$thread->title}}</b>
                @else
                    {{$thread->title}}
                @endif
            </h2>
            @if ($thread->is_locked)
                <div class="font-weight-light">
                    <i class="fas fa-lock" style="font-size: 0.9rem;"></i>
                    <span style="font-size: 0.9rem;">Locked</span>
                </div>
            @endif
            @if ($thread->is_pinned)
                <div class="font-weight-light">
                    <i class="fas fa-thumbtack" style="font-size: 0.9rem;"></i>
                    <span style="font-size: 0.9rem;">Pinned</span>
                </div>
            @endif
        </div>
        <div class="col-1 text-right">
            @can('favourite',$thread)
                <favourite :item="{{$thread}}" :type="'thread'" class='favourite-wrapper'></favourite>
            @endcan
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <p class='thread-body'>{!! str_limit($thread->body,400, '...</div>') !!}</p>
        </div>
    </div>
    <div class="row">
        <div class='col-12 text-center'>
            <small>
            <i class="fas fa-clock thread-meta-icon"></i>{{ $thread->created_at->diffForHumans() }}&nbsp;,&nbsp;
            <i class="fas fa-comment thread-meta-icon"></i>{{$thread->replies_count}}&nbsp;,&nbsp;
            <i class="fas fa-eye thread-meta-icon"></i>{{ $thread->visits }}
            </small>
        </div>
    </div>
</div>
