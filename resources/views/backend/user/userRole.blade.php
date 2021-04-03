@extends('layouts.backend', ['title'=>'User Roles'])

@section('page_menu')
{{-- Open modal for create new permission and create new role --}}
<modals>
    <template v-slot:trigger>
        <button>Create a new role</button>
    </template>

    <form action="{{ route('backend.role.store') }}" method="POST">
        @csrf
        <backend-form-input name="role" label="New Role:" required></backend-form-input>
        <button type="submit">Submit</button>
    </form>
</modals>
<modals>
    <template v-slot:trigger>
        <button>Create a new permission</button>
    </template>

    <form action="{{ route('backend.permission.store') }}" method="POST">
        @csrf
        <backend-form-input name="permission" label="New Permission:" required></backend-form-input>
        <button type="submit">Submit</button>
    </form>
</modals>
@endsection


@section('content')
@include('notice')

<div class="grid grid-cols-3 gap-3">
    <div class="col-span-3 sm:col-span-1">
        <div class="rounded-md shadow-lg">
            <div class="rounded-md bg-white shadow-xs overflow-hidden" role="roles" aria-orientation="vertical" aria-labelledby="user-roles">
                @foreach ($roles as $i => $role)
                <backend-role role="{{ $role }}" {{ $role == 'Reader' ? "checked" : "" }} {{ count($roles) == $i + 1 ? "last" : "" }}></backend-role>
                @endforeach
            </div>
        </div>
        <modals>
            <template v-slot:trigger>
                <button class="mt-4">Delete a role</button>
            </template>
        
            <form action="{{ route('backend.role.destroy') }}" method="POST">
                @csrf
                @method('delete')
                <backend-form-input type="select" name="role" placeholder="Select a role to delete:" required>
                    @foreach ($roles as $role)
                        @if(!in_array($role, $important_roles))
                        <option value="{{ $role }}">{{ $role }}</option>
                        @endif
                    @endforeach
                </backend-form-input>
                <button type="submit">Delete</button>
            </form>
        </modals>
    </div>
    <div class="col-span-3 sm:col-span-2 -mt-5 text-xs text-gray-600">
        <backend-section-card>
            <backend-permissions :url="{{ json_encode($url) }}" :permissions="{{ json_encode($permissions) }}"></backend-permissions>
        </backend-section-card>
            
        <modals>
            <template v-slot:trigger>
                <button class="mt-4">Delete a permission</button>
            </template>

            <form action="{{ route('backend.permission.destroy') }}" method="POST">
                @csrf
                @method('delete')
                <backend-form-input type="select" name="permission" placeholder="Select a permission to delete:" required>
                    @foreach ($permissions as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </backend-form-input>
                <button type="submit">Delete</button>
            </form>
        </modals>
    </div>
</div>
@endsection