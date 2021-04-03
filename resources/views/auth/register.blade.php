@extends('layouts.app', ['title'=>'Sign up a new account'])

@section('content')
<form method="POST" action="{{ route('register') }}" class="mt-8">
    @csrf
    <div>
        <div>
            <label for="email" class="text-gray-600 block">{{ __('Email Address') }}</label>

            <input id="email" type="email" class="@error('email') error @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

            @error('email')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mt-4">
            <label for="password" class="text-gray-600 block">{{ __('Password') }}</label>
            <input id="password" autocomplete="current-password" aria-label="Password" name="password" type="password" required class="@error('password') error @enderror" placeholder="Password">

            @error('password')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mt-4">
            <label for="password-confirm" class="text-gray-600 block">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="@error('password') error @enderror" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="group w-full">
            Sign up
        </button>
    </div>
    
    @if (Route::has('login'))
        <div class="mt-6 text-center text-sm text-gray-500">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>.
        </div>
    @endif
</form>
@endsection
