@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Create permission</h1>
            <p class="mt-1 text-sm text-gray-600">Use dot notation (e.g. reports.view).</p>
        </div>

        <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf

            <div>
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name')" required placeholder="example.permission" />
                <x-input-error for="name" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Create permission
                </button>
                <a href="{{ route('admin.permissions.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endsection
