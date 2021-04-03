@extends('layouts.app', ['title'=>'Reset Password', 'class'=>'reset-password'])

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="mt-8">
    @csrf
    <div>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-label="Email address" class="rounded-md shadow-sm @error('email') error @enderror" placeholder="Email address">
    </div>

    <div class="mt-6">
        <button type="submit" class="group w-full">
            Send Password Reset Link
        </button>
    </div>
</form>
@endsection
