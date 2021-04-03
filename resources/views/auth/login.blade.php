@extends('layouts.app', ['title'=>"Sign in to your account"])

@section('content')
<form method="POST" action="{{ route('login') }}" class="mt-8">
    @csrf
    {{-- <input type="hidden" name="remember" value="true"> --}}
    <div class="rounded-md shadow-sm">
        <div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-label="Email address" class="@error('email') error @enderror" placeholder="Email address">

            @error('email')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="-mt-px">
            <input id="password" autocomplete="current-password" aria-label="Password" name="password" type="password" required class="@error('password') error @enderror" placeholder="Password">

            @error('password')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <p class="text-gray-500">
        <small>Test Account: admin@novelplatform.com<br />
        Password: testtest
        </small>
    </p>

    <div class="mt-6 flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox" class="form-checkbox" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="ml-2 block text-sm leading-5 text-gray-900">
            Remember me
            </label>
        </div>
        @if (Route::has('password.request'))
            <div class="text-sm leading-5">
                <a href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <button type="submit" class="group w-full">
            Sign in
        </button>
    </div>
    
    @if (Route::has('register'))
        <div class="mt-6 text-center text-sm text-gray-500">
            Don't have an account? <a href="{{ route('register') }}">Sign up</a>.
        </div>
    @endif
</form>
@endsection
