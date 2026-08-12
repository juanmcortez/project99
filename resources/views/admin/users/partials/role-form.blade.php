<form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="flex items-center gap-2">
    @csrf
    @method('PUT')
    <select name="role" class="rounded-md border border-gray-300 px-2 py-1 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        {!! $options !!}
    </select>
    <button type="submit" class="rounded-md bg-indigo-600 px-2 py-1 text-xs font-medium text-white hover:bg-indigo-700">
        Save
    </button>
</form>
