{{-- Thumbnail --}}
<a href="{{ $novel->link() }}" class="md:flex-shrink-0 md:mr-10 text-center md:text-left flex justify-center">
    @component('frontend.components.cover', ['src'=>$novel->cover])
    @endcomponent
</a>

{{-- Novel Info --}}
<div>
    {{-- Title --}}
    <a href="{{ $novel->link() }}" class="text-2xl text-gray-100 md:mt-2 mt-8 mb-4 block text-center md:text-left"><h3>{{ $novel->title }}</h3></a>

    {{-- Excerpt --}}
    <p class="mb-4 text-gray-300 text-opacity-75">{{ $novel->excerpt }}</p>

    {{-- Start Reading --}}
    @if($novel->chapters_count> 0)
    <div class="mb-6 md:flex md:justify-start">
        <button-start-reading url="{{ $novel->chapters->first()->link() }}"></button-start-reading>
    </div>
    @endif

    {{-- Tags --}}
    @php $tags = $novel->tagsWithType('tags') @endphp
    @component('frontend.components.tags', ['tags' => $tags])
    @endcomponent
</div>