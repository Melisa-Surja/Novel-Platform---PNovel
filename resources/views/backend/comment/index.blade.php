@extends('layouts.indexPost', [
    'title'         => 'Comments',
    'table_header'  => ['Comment', 'Commenter', 'Approved', 'Date', 'Action'],
    'type'          => 'comment',
    'create'        => false
    ])

@section('dt_inner_script')
order: [3,'desc'],
columns: [
    {data: 'comment', name: 'comment'},
    {data: 'commenterName', name: 'commenterName'},
    {data: 'approved', name: 'approved'},
    {data: 'created_at', name: 'created_at', 
        render: function(data,type,row) {
            return row.created;
        }},
    {data: 'action', name: 'action', orderable: false, searchable: false},
],
@endsection

@section('dt_bulkUpdate_select_options')
<option value="approve">Approve</option>
<option value="unapprove">Unapprove</option>
<option value="delete">Delete Permanently</option>
@endsection
