@extends('layouts.indexPost', [
    'title'         => 'Novels',
    'table_header'  => ['ID', 'Cover', 'Title', 'Poster', 'Date', 'Action'],
    'type'          => 'series',
    'count'         => $count
    ])

@section('dt_inner_script')
order: [0,'desc'],
columns: [
    {data: 'id', name: 'id'},
    {data: 'cover_image', name: 'cover_image', orderable: false, searchable: false},
    {data: 'title', name: 'title'},
    {data: 'poster.name', name: 'poster.name'},
    {data: 'published_at', name: 'published_at', 
        render: function(data,type,row) {
            return row.published;
        }},
    {data: 'action', name: 'action', orderable: false, searchable: false},
]
@endsection
