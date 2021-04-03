@extends('layouts.html', [
    'title'         => $title ?? '',
    'body_class'    => 'frontend'
    ])

@push('seo')
  {{-- SEO --}}
  {!! SEO::generate(true) !!}
  {{-- Feed --}}
  @include('feed::links')
@endpush

@section('layout_content')
<container>
  <div class="w-full">

    @include('frontend.components.navbar')

    @yield('sidebar')

    @yield('featured')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-gray-300 mb-24">
      @yield('content')
    </main>

    @include('frontend.components.footer')
    
  </div>
</container>
@endsection
