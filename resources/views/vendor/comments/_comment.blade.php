@inject('markdown', 'Parsedown')
@php
    // TODO: There should be a better place for this.
    $markdown->setSafeMode(true);

    if (!isset($indentationLevel)) {
        $indentationLevel = 1;
    } else {
        $indentationLevel++;
    }
@endphp

<comment-body
id="{{ $comment->getKey() }}"
avatar="{{ Gravatar::get($comment->commenter->email ?? $comment->guest_email) }}"
username="{{ $comment->commenter->name ?? $comment->guest_name }}"
:indentation="{{ $indentationLevel }}"
date="{{ $comment->created_at->diffForHumans() }}"
>
    {{-- Parent preview if it's a reply --}}
    @if(isset($parent) && ($indentationLevel > $maxIndentationLevel) )
    <template v-slot:parent>
        <div class="flex items-center text-gray-300 opacity-50 text-xs mb-2 px-2 py-1">
            <svg class="w-4 h-4 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" transform="scale(-1,1)" transform-origin="center" />
            </svg>
            <img class="lazyload w-4 h-4 rounded-full flex-shrink-0 mr-2" data-src="{{ Gravatar::get($parent->commenter->email ?? $parent->guest_email) }}" />
            <h5 class="flex-shrink-0 font-semibold mr-2">{{ $comment->commenter->name ?? $comment->guest_name }}</h5>
            <div class="flex-grow truncate">{!! $markdown->line($parent->comment) !!}</div>
        </div>
    </template>
    @endif
    
    {{-- Comment Content --}}
    <template v-slot:comment>{!! $markdown->line($comment->comment) !!}</template>

    {{-- Recursion for children --}}
    <template v-slot:children>
        @if($grouped_comments->has($comment->getKey()) && $indentationLevel < $maxIndentationLevel)
            {{-- TODO: Don't repeat code. Extract to a new file and include it. --}}
            @foreach($grouped_comments[$comment->getKey()] as $child)
                @include('comments::_comment', [
                    'comment' => $child,
                    'grouped_comments' => $grouped_comments,
                    'parent'    => $comment
                ])
            @endforeach
        @endif
    </template>
</comment-body>


{{-- Recursion for children --}}
@if($grouped_comments->has($comment->getKey()) && $indentationLevel >= $maxIndentationLevel)
    {{-- TODO: Don't repeat code. Extract to a new file and include it. --}}
    @foreach($grouped_comments[$comment->getKey()] as $child)
        @include('comments::_comment', [
            'comment' => $child,
            'grouped_comments' => $grouped_comments,
            'parent'    => $comment
        ])
    @endforeach
@endif