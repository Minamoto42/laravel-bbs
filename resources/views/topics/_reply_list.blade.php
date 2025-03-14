
<ul class="list-unstyled">
    @foreach ($replies as $index => $reply)
        <li class="d-flex" name="reply{{ $reply->id }}" id="reply{{ $reply->id }}">
            <div class="media-left flex-shrink-0">
                <a href="{{ route('users.show', [$reply->user_id]) }}">
                    <img class="media-object img-thumbnail" alt="{{ $reply->user->name }}"
                         src="{{ $reply->user->avatar }}" style="width:48px;height:48px;"/>
                </a>
            </div>

            <div class="flex-grow-1 ms-2">
                <div class="media-heading mt-0 mb-1 text-secondary">
                    <a class="text-decoration-none" href="{{ route('users.show', [$reply->user_id]) }}"
                       title="{{ $reply->user->name }}">
                        {{ $reply->user->name }}
                    </a>
                    <span class="text-secondary"> • </span>
                    <span class="meta text-secondary"
                          title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>

                    {{-- 回复按钮 --}}
                    <button class="btn btn-sm btn-link text-secondary" data-bs-toggle="collapse"
                            data-bs-target="#replyForm{{ $reply->id }}">
                        <i class="fa-regular fa-comment"></i> Reply
                    </button>

                    {{-- 显示子评论数量，点击展开 --}}
                    <span class="meta float-end">
                        <button class="btn btn-sm text-secondary {{ collapse($reply->id) ? 'collapsed' : '' }}"
                                data-bs-toggle="collapse"
                                data-bs-target="#nestedReplies{{ $reply->id }}"
                                aria-expanded="{{ collapse($reply->id) ? 'true' : 'false' }}">
                            <i class="fa-solid fa-comments"></i> {{ $reply->child->count() }} replies
                        </button>
                    </span>

                    {{-- 删除按钮 --}}
                    @can('destroy', $reply)
                        <span class="meta float-end">
                            <form action="{{ route('replies.destroy', $reply->id) }}" method="post"
                                  onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-default btn-xs pull-left text-secondary">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </span>
                    @endcan
                </div>

                {{-- 显示回复内容 --}}
                <div class="reply-content text-secondary">
                    {!! $reply->message !!}
                </div>

                {{-- 回复输入框，默认折叠 --}}
                <div class="collapse mt-2" id="replyForm{{ $reply->id }}">
                    <form action="{{ route('replies.store', $topic->id) }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                        <label for="message"></label>
                        <textarea class="form-control" name="message" id="message" rows="2"
                                  placeholder="Reply to this comment..."></textarea>
                        <button type="submit" class="btn btn-sm btn-secondary mt-2">Submit</button>
                    </form>
                </div>

                {{-- 子评论 Mock 数据展示 --}}
                <div class="collapse mt-3 ms-4 border-start ps-3 {{ collapse($reply->id) ? 'show' : ''}}"
                     id="nestedReplies{{ $reply->id }}">
                    @if($reply->child->count() > 0)
                        @foreach($reply->child as $child)
                            <div class="d-flex mb-2" name="reply{{ $child->id }}" id="reply{{ $child->id }}">
                                <div class="media-left flex-shrink-0">
                                    <a href="{{ route('users.show', [$child->user_id]) }}">
                                        <img class="media-object img-thumbnail" alt="{{ $child->user->name }}"
                                             src="{{ $child->user->avatar }}" style="width:48px;height:48px;"/>
                                    </a>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <div class="media-heading mt-0 mb-1 text-secondary">
                                        <a class="text-decoration-none" href="{{ route('users.show', [$child->user_id]) }}"
                                           title="{{ $child->user->name }}">
                                            {{ $child->user->name }}
                                        </a>
                                        <span class="text-secondary"> • </span>
                                        <span class="meta text-secondary" title="{{ $child->created_at }}">
                {{ $child->created_at->diffForHumans() }}
                                        </span>
                                        @can('destroy', $reply)
                                            <span class="meta float-end">
                                            <form action="{{ route('replies.destroy', $child->id) }}" method="post"
                                                  onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
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
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </li>

        @if (!$loop->last)
            <hr>
        @endif
    @endforeach
</ul>
