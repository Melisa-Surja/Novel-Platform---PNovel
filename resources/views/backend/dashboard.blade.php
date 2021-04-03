@extends('layouts.backend', ['title'=>'Dashboard'])

@section('content')
{{-- Show comment replies to your chapters/novels --}}
<backend-section-header title="Comments">Comments you've got on your published series.</backend-section-header>
<backend-section-card>
  @foreach ($comments as $comment)
  <a href="{{ $comment['link'] }}">
    <div class="border-b border-gray-300 pb-2 pt-2 text-sm hover:bg-gray-100 -mx-2 px-2">
      <div class="flex mb-1">
        <span class="block flex-grow text-gray-800"><strong>{{ $comment['source'] }}</strong> by {{ $comment['commenter'] }}</span>
        <span class="flex-shrink-0 text-xs italic text-gray-400">{{ $comment['date'] }}</span>
      </div>
      <div class="text-gray-600">
        {{ $comment['comment'] }}
      </div>
    </div>
  </a>
  @endforeach
</backend-section-card>

{{-- Pagination --}}
<div class="mt-4">
{!! $links !!}
</div>
@endsection