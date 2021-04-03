@if(count($tags) > 0)
<div class="flex mb-1 text-xs flex-wrap">
    @foreach ($tags->values() as $i => $tag)
        <a href="{{ route('frontend.tag.show', $tag->slug) }}" class="tag text-gray-100 text-opacity-75 hover:text-opacity-100 capitalize bg-gray-100 bg-opacity-10 hover:bg-opacity-20 py-1 px-3 rounded-full mr-1 mb-1">
            {{ $tag->name }} ({{ $tag->series_count }})
        </a>
    @endforeach
</div>
@endif