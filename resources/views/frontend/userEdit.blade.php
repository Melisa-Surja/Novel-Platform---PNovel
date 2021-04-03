@extends('layouts.frontend')

@section('content')
<big-header>Edit Profile</big-header>

<form action="{{ route('frontend.profile.update') }}" method="POST" enctype="multipart/form-data">
@method('patch')
@csrf

{{-- Notices --}}
@include('notice')

<h3 class="mb-4">Change Password</h3>

{{-- Change password --}}
<label for="new_password" class="text-sm">New Password:</label>
<backend-form-input required type="password" name="new_password"  value="{{ old('new_password') }}"></backend-form-input>

<label for="confirm_password" class="text-sm">Confirm New Password:</label>
<backend-form-input required type="password" name="confirm_password"  value="{{ old('confirm_password') }}"></backend-form-input>

{{-- Save --}}
<button type="submit">Update Password</button>

</form>
@endsection