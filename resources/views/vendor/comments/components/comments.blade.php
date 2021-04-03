@php
    if (isset($approved) and $approved == true) {
        $comments = $model->approvedComments;
    } else {
        $comments = $model->comments;
    }
    $guest_commenting = Config::get('comments.guest_commenting');
@endphp


{{-- Warning no comment, no need --}}
{{-- @if($comments->count() < 1)
    <div class="my-16 text-center">
        @lang('comments::comments.there_are_no_comments')
    </div>
@endif --}}


{{-- Comment Form --}}
@auth
    {{-- If Logged in --}}
    @include('comments::_form')
@elseif($guest_commenting == true)
    {{-- Guest Commenting --}}
    @include('comments::_form', [
        'guest_commenting' => true
    ])
@else
    {{-- Must Login to Comment --}}
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">@lang('comments::comments.authentication_required')</h5>
            <p class="card-text">@lang('comments::comments.you_must_login_to_post_a_comment')</p>
            <a href="{{ route('login') }}" class="button">@lang('comments::comments.log_in')</a>
        </div>
    </div>
@endauth


{{-- Comments --}}
@if(count($comments) > 0)
<big-header>
    {{ count($comments) . " Comment" . (count($comments) > 1 ? "s" : "") }}
</big-header>
@endif
<div>
    @php
        $comments = $comments->sortBy('created_at');

        if (isset($perPage)) {
            $page = request()->query('page', 1) - 1;

            $parentComments = $comments->where('child_id', '');

            $slicedParentComments = $parentComments->slice($page * $perPage, $perPage);

            $m = Config::get('comments.model'); // This has to be done like this, otherwise it will complain.
            $modelKeyName = (new $m)->getKeyName(); // This defaults to 'id' if not changed.

            $slicedParentCommentsIds = $slicedParentComments->pluck($modelKeyName)->toArray();

            // Remove parent Comments from comments.
            $comments = $comments->where('child_id', '!=', '');

            $grouped_comments = new \Illuminate\Pagination\LengthAwarePaginator(
                $slicedParentComments->merge($comments)->groupBy('child_id'),
                $parentComments->count(),
                $perPage
            );

            $grouped_comments->withPath(request()->url());
        } else {
            $grouped_comments = $comments->groupBy('child_id');
        }
    @endphp
    @foreach($grouped_comments as $comment_id => $comments)
        {{-- Process parent nodes --}}
        @if($comment_id == '')
            @foreach($comments as $comment)
                @include('comments::_comment', [
                    'comment' => $comment,
                    'grouped_comments' => $grouped_comments,
                    'maxIndentationLevel' => $maxIndentationLevel ?? 3
                ])
            @endforeach
        @endif
    @endforeach
</div>

@isset ($perPage)
    {{ $grouped_comments->links() }}
@endisset


{{-- Modal --}}
@if($comments->count() > 0)
    {{-- @can('reply-to-comment', $comments[0]) --}}
    <modals id="comment_modals">
        {{-- Reply to THIS Comment --}}
        <comment-reply-to
        action="{{ route('comments.reply', 1453463636434636) }}" 
        @if(isset($guest_commenting) and $guest_commenting == true) guest_commenting @endif
        >
            <template v-slot:comment_form>
                @csrf
                @honeypot
            </template>
        </comment-reply-to>
    </modals>
    {{-- @endcan --}}
@endif