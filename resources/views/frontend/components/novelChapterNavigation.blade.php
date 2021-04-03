
<div class="flex items-center">
    
    <div class="flex-grow p-4 pl-0">
        @if($prev_ch)
        <a href="{{ route('frontend.novelChapter.show', [
            'novel_slug' => $novel_slug,
            'chapter_num'=> $prev_ch
        ]) }}" class="block">
            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
        @else
        <div class="w-8"></div>
        @endif
    </div>

    <chapters-list-trigger>
        @if(empty($slot->__toString()))
        <div class="flex justify-center px-4 py-4">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg> 
        </div>
        @else 
        {{ $slot }}
        @endif
    </chapters-list-trigger>

    <div class="flex-grow flex justify-end p-4 pr-0">
        @if($next_ch)
        <a href="{{ route('frontend.novelChapter.show', [
            'novel_slug' => $novel_slug,
            'chapter_num'=> $next_ch
        ]) }}" class="block">
            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        </a>
        @else
        <div class="w-8"></div>
        @endif
    </div>

</div>