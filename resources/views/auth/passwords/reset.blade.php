@extends('layouts.app', ['title'=> 'Reset Password', 'class'=>'reset-password'])

@section('content')
<form method="POST" action="{{ route('password.update') }}" class="mt-8">
    @csrf
    <input type="hidden" name="token" value="{{ request()->token }}">
    <div>
        <label for="email" class="text-gray-600 block">{{ __('Email Address') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-label="Email address" class="rounded-md shadow-sm @error('email') error @enderror" placeholder="Email address">
    </div>
    <div class="mt-4">
        <label for="password" class="text-gray-600 block">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
    </div>
    <div class="mt-4">
        <label for="password-confirm" class="text-gray-600 block">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
    </div>

    <div class="mt-6">
        <button type="submit" class="group w-full">
            Send Password Reset Link
        </button>
    </div>
</form>

@endsection
