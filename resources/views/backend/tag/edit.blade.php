@php $tag = $post @endphp

{{-- Name --}}
<div class="mb-6">
    <backend-form-input name="name" value="{{ old('name', $tag->name) }}" required label="Name"></backend-form-input>
</div>

{{-- Slug --}}
<div class="mb-6">
    <backend-form-input name="slug" value="{{ old('slug', $tag->slug) }}" required label="Slug"></backend-form-input>
</div>