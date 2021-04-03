@extends('layouts.backend', ['title'=>'Users'])

@push('head_scripts')
@component('backend.components.dtScript')
@endcomponent
@endpush

@section('page_menu')
    {{-- Create New Series --}}
    @if(!isset($create) || $create === true)
        @can("create user")
        <backend-create-new href="{{route("backend.user.create")}}"></backend-create-new>
        @endcan
    @endif
@endsection

@section('content')
<div class="datatable">
<table class="table table-bordered data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>
</div>
@endsection

@push('footer_scripts')
<script type="text/javascript">
    $(function () {
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('backend.user.index') }}",
          columns: [
              {data: 'id', name: 'id', searchable: false},
              {data: 'name', name: 'name'},
              {data: 'email', name: 'email'},
              {data: 'role', name: 'role'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    });
  </script>
@endpush
