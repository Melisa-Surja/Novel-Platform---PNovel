@extends('layouts.frontend')

{{-- Featured Novel --}}
@section('featured')
@if(isset($featured) && !empty($featured))
<div class="pt-16 px-6 bg-center bg-no-repeat bg-cover bg-bg-dark"> 
{{-- style="background-image: url('{{ asset('images/featured_bg.jpg') }}');'"> --}}
    <div class="md:flex max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-gray-300 rounded-3xl" style="background-image: linear-gradient(to bottom right, rgba(255,255,255,0.1), rgba(200,200,200,0.1))">
        @component('frontend.components.novelDisplay', ['novel'=>$featured])
        @endcomponent
    </div>
</div>
@endif
@endsection

@section('content')
<div class="mt-2">&nbsp;</div>

<!-- Slider main container -->
<big-header>
    <div class="flex-grow flex items-center justify-between">
        Recommended
        <a href="{{ route('frontend.novels.archive') }}" class="ml-2 flex-shrink-0 text-sm underline text-gray-300 hover:text-white">View All</a>
    </div>
</big-header>
<div class="swiper-container">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        @foreach ($novels as $novel)
        <div class="swiper-slide">
            <a href="{{ $novel->link() }}" class="mb-4 relative block rounded-md overflow-hidden"  style="min-width:120px; min-height:170px">
                <img data-src="{{ $novel->cover }}" class="swiper-lazy cover rounded-md" />
                <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
            </a>
            <a href="{{ $novel->link() }}" class="text-white text-md block"><h6>{{ $novel->title }}</h6></a>
            <div class="text-gray-300 text-opacity-75">{{ $novel->author ?? $novel->poster->name }}</div>
            
        </div>
        @endforeach
    </div>
</div>

<div class="mt-10">&nbsp;</div>
<big-header>Latest Releases</big-header>
<div class="latest-releases">
    @foreach ($latest as $i => $ch)
    <div class="flex justify-between sm:p-6 sm:pr-10 p-4 rounded-md items-center">
        <div class="flex-grow">
            <a href="{{ $ch->novel->link() }}" class="text-gray-100 flex-grow mb-1">
                <h6>{{ $ch->novel->title }}</h6>
            </a>
            <p>
                <a href="{{ $ch->link() }}" class="text-gray-300 text-opacity-75 text-xs ">
                    {{ !empty($ch->title) ? "Chapter " : "" }} {{ $ch->fullTitle }}
                </a>
            </p>
        </div>
        <div class="date flex-shrink-0 text-gray-300 text-xs">{{ $ch->published_at->diffForHumans() }}</div>
    </div>
    @endforeach
</div>
@endsection


@push('head_scripts')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<style>
.swiper-slide {
    height: calc((100% - 30px) / 2);
}
</style>
@endpush

@push('footer_scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
var mySwiper = new Swiper('.swiper-container', {
    loop: true,
    lazy: true,
    grabCursor: true,

    breakpoints: {
        // when window width is >= 320px
        320: {
        slidesPerView: 2.2,
        spaceBetween: 20
        },
        // when window width is >= 480px
        480: {
        slidesPerView: 3.3,
        spaceBetween: 20
        },
        // when window width is >= 640px
        640: {
        slidesPerView: 4.3,
        spaceBetween: 30
        },
        800: {
        slidesPerView: 5.4,
        spaceBetween: 30
        }
    }
})
</script>
@endpush