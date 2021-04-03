@extends('layouts.html', [
    'title'         => $title ?? '',
    'body_class'    => 'frontend'
    ])

@section('layout_content')
    <main class="{{ $class ?? Route::current()->uri }} no-menu w-full">
        <div class="max-w-md">
            @if($title)
                <h2>{{ $title }}</h2> 
            @endif
            
            @include('notice')
            
            @yield('content')
        </div>
    </main>
@endsection
