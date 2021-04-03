@if($errors->any())
<notice type="error">
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</notice>
@endif
@if (session($session_status ?? 'status'))
<notice type="success">
    {{ session($session_status ?? 'status') }}
</notice>
@endif