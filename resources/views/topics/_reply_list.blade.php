<ul class="list-unstyled">
    @foreach ($replies as $reply)
        {{-- 只显示顶级回复（parent_id == 0） --}}
        @if ($reply->parent_id == 0)
            <li class="d-flex" name="reply{{ $reply->id }}" id="reply{{ $reply->id }}">
                <div class="media-left">
                    <a href="{{ route('users.show', $reply->user_id) }}">
                        <img class="media-object img-thumbnail mr-3" alt="{{ $reply->user->name }}"
                             src="{{ $reply->user->avatar }}" style="width:48px;height:48px;">
                    </a>
                </div>
                <div class="flex-grow-1 ms-2">
                    <div class="media-heading mt-0 mb-1 text-secondary">
                        <a class="text-decoration-none" href="{{ route('users.show', $reply->user_id) }}"
                           title="{{ $reply->user->name }}">
                            {{ $reply->user->name }}
                        </a>
                        <span class="text-secondary"> • </span>
                        <span class="meta text-secondary" title="{{ $reply->created_at }}">
                            {{ $reply->created_at->diffForHumans() }}
                        </span>
                        {{-- 删除按钮 --}}
                        @can('destroy', $reply)
                            <span class="meta float-end">
                                <form action="{{ route('replies.destroy', $reply->id) }}" method="post"
                                      onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-default btn-xs pull-left text-secondary">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </span>
                        @endcan
                    </div>
                    <div class="reply-content text-secondary">
                        {!! $reply->message !!}
                    </div>

                    {{-- 楼中楼回复列表 --}}
                    @if ($reply->children->count() > 0)
                        <ul class="list-unstyled" style="margin-left: 40px;">
                            @foreach ($reply->children as $child)
                                <li class="d-flex" name="reply{{ $child->id }}" id="reply{{ $child->id }}">
                                    <div class="media-left">
                                        <a href="{{ route('users.show', $child->user_id) }}">
                                            <img class="media-object img-thumbnail mr-3" alt="{{ $child->user->name }}"
                                                 src="{{ $child->user->avatar }}" style="width:48px;height:48px;">
                                        </a>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="media-heading mt-0 mb-1 text-secondary">
                                            <a class="text-decoration-none" href="{{ route('users.show', $child->user_id) }}"
                                               title="{{ $child->user->name }}">
                                                {{ $child->user->name }}
                                            </a>
                                            <span class="text-secondary"> • </span>
                                            <span class="meta text-secondary" title="{{ $child->created_at }}">
                                                {{ $child->created_at->diffForHumans() }}
                                            </span>
                                            {{-- 删除按钮 --}}
                                            @can('destroy', $child)
                                                <span class="meta float-end">
                                                    <form action="{{ route('replies.destroy', $child->id) }}" method="post"
                                                          onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-default btn-xs pull-left text-secondary">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </span>
                                            @endcan
                                        </div>
                                        <div class="reply-content text-secondary">
                                            {!! $child->message !!}
                                        </div>
                                    </div>
                                </li>
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </ul>
                    @endif

                    {{-- 回复表单：用于对当前顶级回复进行楼中楼回复 --}}
                    @auth
                        <form action="{{ route('replies.store', $topic->id) }}" method="POST" style="margin-left: 40px;">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                            <div class="form-group">
                                <textarea name="message" class="form-control" rows="2" placeholder="回复这条评论..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">回复</button>
                        </form>
                    @endauth
                </div>
            </li>
            @if (!$loop->last)
                <hr>
            @endif
        @endif
    @endforeach
</ul>
