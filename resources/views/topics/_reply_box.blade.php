@include('shared._error')

<div class="reply-box">
    <form action="{{ route('replies.store') }}" method="post" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
        <div class="mb-3">
            <label for="message"></label>
            <textarea class="form-control" name="message" id="message" rows="3" placeholder="Share your thoughts."></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share me-1"></i> Reply</button>
    </form>
</div>

<hr>
