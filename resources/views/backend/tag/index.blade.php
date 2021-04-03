@extends('layouts.indexPost', [
    'title'         => 'Tags',
    'table_header'  => ['ID', 'Name', 'Count', 'Action'],
    'type'          => 'tag',
    ])

@section('dt_inner_script')
order: [0,'desc'],
columns: [
    {data: 'id', name: 'id'},
    {data: 'name', name: 'name'},
    {data: 'series_count', name: 'series_count'},
    {data: 'action', name: 'action', orderable: false, searchable: false},
]
@endsection

@section('dt_bulkUpdate_select_options')
<option value="delete">Delete</option>
@endsection