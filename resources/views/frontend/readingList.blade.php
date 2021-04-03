@extends('layouts.frontend')

@section('content')
<big-header>Reading List</big-header>

@include('notice')

@if($series->count() == 0)
<p>There's nothing in your reading list.</p>
@else
<div class="flex font-semibold space-x-4 border-b-2 border-gray-500 pb-2 mb-2">
    <div class="md:w-1/3 w-full">Series</div>
    <div class="w-1/3 md:block hidden">Latest Read</div>
    <div class="w-1/3 md:block hidden">Latest Release</div>
    <div class="flex-shrink-0 w-10"></div>
</div>

<div class="text-sm">
    @foreach ($series as $s)
        @php 
        $latest_read = auth()->user()->latest_read($s);
        $latest_release = $s->chapters->last(); 
        $have_read = auth()->user()->have_read;
        $have_read_count = isset($have_read["series_" . $s->id]) ? count($have_read["series_" . $s->id]) : 0;
        @endphp
        
        <div class="flex items-center space-x-4 border-b border-gray-600 pb-2 mb-2">
            
            {{-- Series --}}
            <div class="md:w-1/3 w-full">
                <a href="{{ $s->link() }}" class="md:underline md:font-normal font-semibold">{{ $s->title }}</a>
                <div class="text-xs mt-1 text-gray-400">{{ $s->chapters->count() - $have_read_count }} chapters unread.</div>
                <div class="block md:hidden">
                    @if($latest_read)
                    <p class="mt-2 mb-1"><span class="font-semibold">Latest Read</span>: <a href="{{ $latest_read->link() }}" class="text-gray-400">
                        {{ $latest_read->fullTitle }}
                    </a></p>
                    @endif
                    <p><span class="font-semibold">Latest Release</span>: <a href="{{ $latest_release->link() }}" class="text-gray-400">
                        {{ $latest_release->fullTitle }}
                    </a></p>
                </div>
            </div>

            {{-- Latest Read --}}
            <div class="w-1/3 md:block hidden">
                @if($latest_read)
                <a href="{{ $latest_read->link() }}">
                    {{ $latest_read->fullTitle }}
                </a>
                @endif
            </div>

            {{-- Latest Release --}}
            <div class="w-1/3 md:block hidden">
                <a href="{{ $latest_release->link() }}">
                    {{ $latest_release->fullTitle }}
                </a>
            </div>

            {{-- Delete --}}
            <div class="flex-shrink-0 w-10">
                <form method="POST" action="{{ route('frontend.reading_list.destroy', $s->id) }}">
                    @method('delete')
                    @csrf
                    <button class="delete">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection