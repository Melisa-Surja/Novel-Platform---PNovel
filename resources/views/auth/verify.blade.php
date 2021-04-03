@extends('layouts.app', ['title'=>'Verify Your Email Address', 'class'=>'reset-password'])

@section('content')
@if (session('resent'))
<notice type="success" class="mt-8">
    {{ __('A fresh verification link has been sent to your email address.') }}
</notice>
@endif
{{ __('Before proceeding, please check your email for a verification link.') }}
{{ __('If you did not receive the email') }},
<form class="mt-8" method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <div class="mt-6">
        <button type="submit" class="group w-full">
            Click here to request another
        </button>
    </div>
</form>



{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
