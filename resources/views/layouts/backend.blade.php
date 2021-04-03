@extends('layouts.html', [
    'title'         => $title ?? '',
    'body_class'    => 'backend'
    ])


@php
$sidebar_links = [
    sidebar_link('ViewGridIcon','Dashboard','backend.dashboard'),
    sidebar_link('BookOpenIcon','Novels','backend.series.index','backend.series'),
    sidebar_link('TagIcon','Tags','backend.tag.index','backend.tag', 'edit all series'),
    sidebar_link('DocumentTextIcon','Chapters','backend.novelChapter.index','backend.novelChapter'),
    sidebar_link('ChatAlt2Icon','Comments','backend.comment.index','backend.comment', 'edit all comment'),
    sidebar_link('UserGroupIcon','Users','backend.user.index','backend.user.', 'manage users'),
    sidebar_link('IdentificationIcon','User Roles','backend.userRole.index','backend.userRole', 'manage users'),
    sidebar_link('CogIcon','Settings','backend.settings.index','backend.settings', 'manage settings'),
];

function sidebar_link($icon, $title, $route, $check = false, $permission = "") {
    $route_name = Route::currentRouteName(); 
    $check = $check ? $check : $route;
    $current = strpos($route_name, $check) !== false;
    return [
        'icon'=>$icon, 
        'title'=>$title, 
        'route'=>$route, 
        'current'=>$current,
        'permission'=>$permission
    ];
}
@endphp

@section('layout_content')
<container>
    <div class="flex h-screen">
    <backend-sidebar>
        @foreach ($sidebar_links as $link)
            @if(empty($link['permission']) || auth()->user()->can($link['permission']))
            <backend-sidebar-link href="{{route($link['route'])}}" title="{{$link['title']}}" icon="{{$link['icon']}}" :current="{{json_encode($link['current'])}}"></backend-sidebar-link>
            @endif
        @endforeach
    </backend-sidebar>
    
    <div class="w-full bg-cool-gray-200 dark:bg-cool-gray-700 overflow-y-auto">
        <backend-header></backend-header>
        <main class="h-full px-4 md:px-8">
            <div class="pt-9 pb-16 md:pt-6">
                <div class="flex items-center space-x-4 mb-8">
                    <h1 class="text-2xl mb-0 font-semibold text-gray-800">
                        {{$title ?? ""}}
                    </h1>
                    @yield('page_menu')
                </div>
                @yield('content')
            </div>
        </main>
    </div>
    </div>
</container>
@endsection


@prepend('head_scripts')
<script>
    var site_name = "{{ config('app.name') }}";
    var site_url = "{{ route('home') }}";
    var backend_url = "{{ route('backend.dashboard') }}";
</script>
@endprepend


