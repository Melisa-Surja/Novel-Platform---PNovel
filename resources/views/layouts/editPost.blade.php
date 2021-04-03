@extends('layouts.backend', [
    'title' => ucwords($mode . ' ' . ($title ?? $post_type)),
    ])


@php
$form = "backend.${post_type}.edit";
$action = ($mode == 'create') ? route("backend.${post_type}.store") : route("backend.${post_type}.update", $post['id']);
@endphp

@section('content')
<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
@if($mode == 'edit') @method('patch') @endif
@csrf

<backend-section-card>
    {{-- Notices --}}
    @include('notice')

    @component($form, ['post'=>$post, 'extra'=>$extra ?? null])
    @endcomponent

    {{-- Poster --}}
    @can('manage users')
    @if($mode == 'edit' && isset($posters))
        <backend-form-input name="poster_id" type="select" label="Poster" required>
            {{-- Only translators are listed --}}
            @foreach ($posters as $poster)
                <option value="{{$poster->id}}" {{old('poster_id', $post['poster_id']) == $poster->id ? 'selected' : ''}}>{{$poster->name}}</option>
            @endforeach
        </backend-form-input>
    @endif
    @endcan
    
    {{-- Save --}}
    <backend-section-card-footer>
        <div class="flex justify-end">
            @if($mode == 'edit' && isset($preview))
            <backend-form-preview-button href="{{$preview}}">Preview</backend-form-preview-button>
            @endif
            <button class="ml-2" type="submit">Save</button>
        </div>
    </backend-section-card-footer>
</backend-section-card>

</form>
@endsection