@extends('layouts.frontend')


@section('content')
    <big-header>Notifications</big-header>

    @include('notice')

    @if($notifications->count()>0)
    <form class="mb-2" method="POST" action="{{ route('frontend.notification.destroy', 1) }}">
        @csrf
        @method('delete')
        <input type="hidden" name="method" value="all" />
        <button type="submit">Delete All</button>
    </form>
    @else
    You don't have any notification.
    @endif

    <div class="mb-2">
        {!! $links !!}
    </div>

    @foreach ($notifications as $n)
        <notification-item 
            update="{{ route('frontend.notification.update', $n['id']) }}"
            href="{{ $n['link'] }}" 
            :read="{{ json_encode($n['read']) }}"
            >
            {!! $n['note'] !!}
        </notification-item>
    @endforeach

    {!! $links !!}
@endsection