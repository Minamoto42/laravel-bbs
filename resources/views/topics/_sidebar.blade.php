<div class="card ">
    <div class="card-body">
        <a href="{{ route('topics.create') }}" class="btn btn-success w-100" aria-label="Left Align">
            <i class="fas fa-pencil-alt me-2"></i> New Topic
        </a>
    </div>
</div>

@if(count($active_users))
     <div class="card mt-4">
         <div class="card-body active-users pt-2">
             <div class="text-center mt-1 mb-0 text-muted">Active Users</div>
             <hr class="mt-2">
             @foreach($active_users as $user)
                 <a class="d-flex mt-2 text-decoration-none ms-3" href="{{ route('users.show', $user->id) }}">
                     <div class="media-left media-middle me-2 ms-1">
                         <img src="{{ $user->avatar }}" class="media-object" width="24px" height="24px"
                              alt="{{ $user->name }}">
                     </div>
                     <div class="media-body ms-1">
                         <small class="media-heading text-secondary">{{ $user->name }}</small>
                     </div>
                 </a>
             @endforeach
         </div>
     </div>
 @endif