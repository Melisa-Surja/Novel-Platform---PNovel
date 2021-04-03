@php
$have_read = auth()->check() ? auth()->user()->have_read : [];
$ch_slugs = $have_read["series_".$series->id] ?? [];
@endphp


@if(!empty($ch_slugs))
<div class="flex items-center text-xs mb-2">
    <div class="bg-green-300 w-3 h-2 rounded-sm mr-2"></div>
    <div class="text-green-300">You have read this.</div>
</div>
@endif

<div class="w-full">
    @foreach ($chapters as $ch)
        <div class="border-b border-gray-600 text-sm hover:bg-bg-light {{ in_array($ch->chNumSlug, $ch_slugs) ? 'text-green-300 hover:text-green-100' : 'hover:text-white' }}">
            <a href="{{ $ch->link($series->slug) }}" class="block w-full pb-1 pt-1">
                {{ $ch->fullTitle }}
            </a>
        </div>
    @endforeach
</div>

{{-- <table class="w-full">
    @foreach ($chapters as $ch)
        <tr class="border-b border-gray-600 text-sm hover:bg-gray-700 {{ in_array($ch->chNumSlug, $ch_slugs) ? 'text-green-300 hover:text-green-100' : 'hover:text-white' }}">
            <td>
                <a href="{{ $ch->link($series->slug) }}" class="block pb-1 pt-1 pr-2">
                    {{ $ch->chNum }}
                </a>
            </td>
            <td>
                <a href="{{ $ch->link($series->slug) }}" class="block w-full pb-1 pt-1">
                    {{ $ch->title }}
                </a>
            </td>
        </tr>
    @endforeach
</table> --}}