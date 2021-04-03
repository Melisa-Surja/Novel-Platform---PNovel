@extends('layouts.backend', ['title'=>'Settings'])


@section('content')

@include('notice')

<form action="{{ route('backend.settings.update') }}" method="POST">
@csrf

<backend-section-card>
    <backend-section-card-header title="General Settings"></backend-section-card-header>

    {{-- Featured Novels --}}
    <backend-form-input label="Featured Novels" desc="Comma separated. Featured at the top of homepage." name="featured" value="{{ old('featured', implode(",", $settings['featured'])) }}"></backend-form-input>
</backend-section-card>

<backend-section-card>
    <backend-section-card-header title="Comment Moderation">One keyword per line.</backend-section-card-header>

    {{-- Comment Blacklist --}}
    <backend-form-input type="textarea" label="Blacklisted Keywords" desc="Will immediately be deleted." name="comment_blacklist" value="{{ old('comment_blacklist', implode("\n", $settings['comment_blacklist'])) }}" rows="6"></backend-form-input>

    {{-- Comment moderation --}}
    <backend-form-input type="textarea" label="Moderated Keywords" desc="Will be put on moderation, waiting approval." name="comment_moderated" value="{{ old('comment_moderated', implode("\n", $settings['comment_moderated'])) }}" rows="6"></backend-form-input>
</backend-section-card>

<backend-section-fixed-save></backend-section-fixed-save>
</form>
@endsection