@extends('layouts.frontend')

@section('content')

{{-- Title --}}
<big-header>{{ $title }}</big-header>

{{-- Novels --}}
<div class="archive">
@foreach ($novels as $novel)
    <div class="md:flex py-10 px-4 md:px-8 rounded-lg">
        @component('frontend.components.novelDisplay', ['novel'=>$novel])
        @endcomponent
    </div>
@endforeach
</div>

@endsection