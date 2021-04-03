@php $user = $post @endphp

{{-- Name --}}
<backend-form-input name="name" value="{{old('name', $user['name'])}}" required label="Name"></backend-form-input>

{{-- Email --}}
<backend-form-input name="email" value="{{old('email', $user['email'])}}" required label="Email Address"></backend-form-input>

{{-- Password --}}
<div class="grid grid-cols-6 gap-6">
    <div class="col-span-6 sm:col-span-3">
<backend-form-input type="password" name="password" label="Password"></backend-form-input>
    </div>
    <div class="col-span-6 sm:col-span-3">
<backend-form-input type="password" name="confirm_password" label="Confirm Password"></backend-form-input>
    </div>
</div>

{{-- Role --}}
<backend-form-input type="select" name="role" label="role">
    @foreach ($extra['roles'] as $role)
        <option value="{{ $role }}" @if($user->hasRole($role)) {{ "selected" }} @endif>
            {{ $role }}
        </option>
    @endforeach
</backend-form-input>
