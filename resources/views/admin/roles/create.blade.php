@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Create role</h1>
            <p class="mt-1 text-sm text-gray-600">Define a new role and assign permissions.</p>
        </div>

        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf

            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name')" required />
                <x-input-error for="name" />
            </div>

            <div>
                <x-input-label value="Permissions" />
                <div class="mt-2 space-y-2">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="permissions[]" value="{{ $permission }}" @checked(in_array($permission, old('permissions', []), true)) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            {{ $permission }}
                        </label>
                    @endforeach
                </div>
                <x-input-error for="permissions" />
                <x-input-error for="permissions.*" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Create role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
