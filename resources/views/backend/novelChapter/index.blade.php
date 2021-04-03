@extends('layouts.indexPost', [
    'title'         => 'Chapters',
    'table_header'  => ['ID', 'Chapter', 'Title', 'Poster', 'Date', 'Action'],
    'type'          => 'novelChapter',
    'count'         => $count
    ])
    
{{-- Select Novel Filter --}}
@section('extra_menu')
<div class="-mb-6">
    <backend-form-input name="series_id" placeholder="Select a Novel:" type="select">
        @foreach ($novels as $novel)
        <option value="{{$novel->id}}">{{$novel->title}}</option>
        @endforeach
    </backend-form-input>
</div>
@endsection

@section('dt_inner_script')
order: [0,'desc'],
columns: [
    {data: 'id', name: 'id'},
    {data: 'chNum', name: 'chNum', searchable: false},
    {data: 'the_title', name: 'the_title'},
    {data: 'poster.name', name: 'poster.name'},
    {data: 'published_at', name: 'published_at', 
        render: function(data,type,row) {
            return row.published;
        }},
    {data: 'action', name: 'action', orderable: false, searchable: false},
]
@endsection

@push('footer_scripts')
<script type="text/javascript">
$(function () {
    $("#series_id").change(function(e) {
        table.ajax
        .url("{{ route('backend.novelChapter.index') }}" + "/?novel_id=" + $("#series_id").val())
        .load();
    });
});
</script>
@endpush
