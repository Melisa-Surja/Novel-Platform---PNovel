@extends('layouts.html', [
    'title'         => 'Error',
    'body_class'    => 'frontend'
    ])

@section('layout_content')
<container class="flex flex-col min-h-screen">
    <div class="flex-none">
        @include('frontend.components.navbar')
    </div>

    <div class="flex-grow flex items-top justify-center items-center pt-1 pb-8">
        <div class="px-2">
            <img src="{{ asset('images/error.png') }}" class="max-w-full w-96 mb-8 mx-auto md:w-3/4" />
            <div class="text-gray-300 text-center md:text-5xl text-3xl">
                @yield('code') | @yield('message')
            </div>
        </div>
    </div>

    <div class="flex-none">
        @include('frontend.components.footer')
    </div>
</container>
@endsection