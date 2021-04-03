@extends('layouts.frontend')



{{-- See if it's a text or JSON Quill --}}
@php
function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}   
$content = $novelChapter->content;
$type = isJson($content) ? "content" : "text";
if ($type == 'text') $content = strip_tags($content);
@endphp

@section('sidebar')
    <chapters-list>
        <h2 class="text-xl font-semibold mb-4"><a href="{{ $novel->link() }}" class="hover:text-white">{{ $novel->title }}</a></h2>
        
        @component('frontend.components.chapterList', [
            'chapters'  => $novelChapter->novel->chapters, 
            'series'    => $novel
            ])
        @endcomponent
    </chapters-list>
@endsection


@section('content')

{{-- Add to "have read" chapter --}}
@auth
    @php 
    $a = auth()->user()->add_read($novelChapter->novel->id, $novelChapter->chNumSlug);
    @endphp 
@endauth

{{-- Cover BG --}}


{{-- Navigation --}}
<div class="mb-8 mt-4">
    @component('frontend.components.novelChapterNavigation', [
        'novel_slug'    => $novel->slug,
        'prev_ch'       => $prev_ch,
        'next_ch'       => $next_ch
    ])
    {{-- Chapter Title --}}
    <div style="flex-grow: 4">
    <h3 class="text-lg block text-center text-gray-400">{{ $novel->title }}</h3>
    <p class="text-lg text-center text-gray-100">{{ $novelChapter->fullTitle }}</p>
    </div>
    @endcomponent
</div>

<div class="mb-8">
<chapter-content type="{{$type}}" content="{{$content}}"></chapter-content>
</div>


{{-- Navigation --}}
@component('frontend.components.novelChapterNavigation', [
    'novel_slug'    => $novel->slug,
    'prev_ch'       => $prev_ch,
    'next_ch'       => $next_ch
])
@endcomponent

@comments(['model' => $novelChapter, 'approved' => true])

@endsection


@push("head_scripts")
<style>
:root {
  --gray-900: #161e2e;
}
.tippy-box {
    background-color:var(--gray-900);
}
.tippy-box[data-placement^='top'] > .tippy-arrow::before {
  border-top-color:var(--gray-900);
}
.tippy-box[data-placement^='bottom'] > .tippy-arrow::before {
  border-bottom-color:var(--gray-900);
}
.tippy-box[data-placement^='left'] > .tippy-arrow::before {
  border-left-color:var(--gray-900);
}
.tippy-box[data-placement^='right'] > .tippy-arrow::before {
  border-right-color:var(--gray-900);
}
.tippy-box .tippy-content {
  white-space: pre-wrap;
}
</style>
@endpush

@push('footer_scripts_before')
<script src="https://cdn.jsdelivr.net/npm/quill-delta-to-html@0.12.0/dist/browser/QuillDeltaToHtmlConverter.bundle.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
@endpush