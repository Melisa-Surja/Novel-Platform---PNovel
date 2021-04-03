<nav class="shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
            <div class="flex-shrink-0">
            <a href="{{ route('home') }}" class="flex items-center">
                {{-- <img class="h-8 w-8 mr-2" src="https://tailwindui.com/img/logos/v1/workflow-mark-on-dark.svg" alt="Workflow logo"> --}}
                <span class="block text-gray-300 font-semibold text-xl">{{ config('app.name') }}</span>
            </a>
            </div>
            {{-- <div class="hidden md:block"> --}}
            <div>
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="{{ route('frontend.novels.archive') }}" class="px-3 py-2 rounded-md text-sm font-bold  focus:outline-none bg-gray-100 bg-opacity-5 text-white text-opacity-90 hover:text-opacity-100 hover:bg-opacity-10"><h4>Novels</h4></a>

                    {{-- @php
                    $route_name = Route::currentRouteName(); 
                    $home = $route_name == 'home';
                    @endphp

                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium  focus:outline-none focus:text-white focus:bg-gray-700 {{ $home ? "text-white bg-gray-900" : "text-gray-300 hover:text-white hover:bg-gray-700"  }}">Home</a> --}}
                </div>
            </div>
        </div>

        <div>
            <div class="ml-4 md:ml-6">
            <header-profile 
            @guest 
                login="{{ route('login') }}" guest 
            @else
                :new_notifications="{{ auth()->user()->newNotifications() }}"
                notification_url="{{ route('frontend.notification.index') }}"
                avatar="{{ auth()->user()->avatar }}" 
                profile="{{ route('frontend.profile.edit') }}"
                logout="{{ route('logout') }}"
                reading_list="{{ route('frontend.reading_list.index') }}"
                @can('access backend')
                dashboard="{{ route('backend.dashboard') }}"
                @endcan
            @endguest
            >
                <template v-slot:csrf>
                @csrf
                </template>
            </header-profile>
            </div>
        </div>

        </div>
    </div>

    </nav>