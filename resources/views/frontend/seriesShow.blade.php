@extends('layouts.frontend')

@section('featured')
<div class="py-10 bg-bg-dark"> 
<div class="md:flex max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-300">
    <div class="md:mr-6 md:mb-0 mb:4 md:flex-shrink-0 @if(!$series->cover)  md:flex md:justify-items-stretch @else flex md:justify-start justify-center @endif">
        @if($series->cover)
            @component('frontend.components.cover', ['src'=>$series->cover])
            @endcomponent
        @else
            <image-notfound class="w-32">No Cover</image-notfound>
        @endif
    </div>

    <div class="md:text-left text-center">
        {{-- Series Genre --}}
        {{-- <p class="mt-1 -mb-1 text-sm text-gray-400">Genre</p> --}}

        {{-- Series Title --}}
        <h1 class="font-semibold text-2xl mt-2 text-gray-100">{{ $series->title }}</h1>
        <div class="text-xs text-gray-400 mb-4">

            {{-- Staffs --}}
            {{-- Author --}}
            <p>by <span class="text-gray-200">{{ $series->author }}</span></p>

            {{-- Other staffs --}}
            @if(!empty($series->staffs))
            <p>
            @php $i = 0; @endphp
            @foreach ($series->staffs as $staff_name => $staff)
                @if(!empty($staff))
                {{ ucfirst($staff_name) }}: {{ $staff }}{{ $i >= count($series->staffs) - 1 ? "." : ", " }}
                @php $i++; @endphp
                @endif
            @endforeach
            </p>
            @endif

        </div>

        {{-- Rating --}}
        {{-- <div class="rating flex items-center mt-4 md:justify-start justify-center">
            @for ($i = 0; $i < 5; $i++)
            <svg class="w-5 h-5 text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            @endfor
            <div class="ml-2 text-gray-100">4.5</div>
        </div> --}}

        {{-- Series Actions: Start Reading, Add to Reading List --}}
        <div class="mt-4 mb-6 text-center sm:text-left sm:flex sm:justify-start justify-center">
            
            {{-- Start Reading --}}
            @if($series->chapters->count()> 0)
            <div class="sm:mr-3 sm:mb-0 mb-2">
                <button-start-reading url="{{ $series->chapters->first()->link() }}"></button-start-reading>
            </div>
            @endif

            {{-- Add to Reading List --}}
            @auth
                @php 
                // check if it already exist
                $already_in_reading_list = auth()->user()->reading_list->contains($series->id);
                @endphp
                <button-add-reading-list 
                    id="{{ $series->id }}" 
                    url="{{ route('frontend.reading_list.' . ($already_in_reading_list ? 'destroy' : 'store'), $series->id) }}" 
                    {{ $already_in_reading_list ? 'remove' : '' }}
                >
                    @if($already_in_reading_list)
                        @method('delete')
                    @endif
                    @csrf
                </button-add-reading-list>
            @endauth
        </div>
    
        <!-- Tags -->
        @component('frontend.components.tags', ['tags'=>$tags])
        @endcomponent
    </div>
</div>
</div>
@endsection

@section('content')
@include('notice')
{{-- Tabs: Summary, Chapters --}}
<series-info-tabs class="mt-2">
    <template v-slot:summary>
        @php $summary = explode("\n", $series->summary); @endphp
        @foreach ($summary as $p)
            <p class="mb-3">{{ $p }}</p>
        @endforeach
    </template>

    @php 
    $have_read = auth()->check() ? auth()->user()->have_read : [];
    $ch_slugs = $have_read["series_".$series->id] ?? [];
    @endphp

    <template v-slot:chapters>
        @component('frontend.components.chapterList', [
            'chapters'  => $series->chapters, 
            'series'    => $series
            ])
        @endcomponent
    </template>
</series-info-tabs>

@comments(['model' => $series, 'approved' => true])

@endsection

