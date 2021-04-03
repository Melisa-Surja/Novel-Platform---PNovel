@extends('layouts.backend', ['title'=>$title ?? ''])

@push('head_scripts')
@component('backend.components.dtScript')
@endcomponent
@endpush

@section('page_menu')
    {{-- Create New Series --}}
    @if(!isset($create) || $create === true)
        @can("create $type")
        <backend-create-new href="{{route("backend.$type.create")}}"></backend-create-new>
        @endcan
    @endif
@endsection

@section('content')
<div class="datatable">
    {{-- Show all and trashed --}}
    <div id="count" class="hidden px-1 text-sm mb-4" style="white-space: nowrap"></div>

    @include('notice')
    
    <div class="flex justify-between items-center space-x-4 mb-2">
        {{-- Bulk Update Selection --}}
        <div class="flex-shrink-0">
            @component('backend.components.dtBulkUpdateSelect', [
                'action'        => route("backend.$type.bulkUpdate"),
                'permission'    => "edit all $type"
                ])
                @yield('dt_bulkUpdate_select_options')
            @endcomponent
        </div>

        @yield('extra_menu')
    </div>

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                @foreach ($table_header as $h)
                <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                @foreach ($table_header as $h)
                <th>{{ $h }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@php 
$var_bulkUpdate_script = [
    'ajax'          => route("backend.$type.index"),
    'permission'    => "edit all $type"
];
if(isset($count)) $var_bulkUpdate_script['count'] = $count;
@endphp

@prepend('footer_scripts')
    @component('backend.components.dtBulkUpdateScript', $var_bulkUpdate_script)
    @yield('dt_inner_script')
    @endcomponent
@endprepend