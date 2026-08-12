@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Activity Log</h1>
            <p class="mt-1 text-sm text-gray-600">Searchable log of system activity.</p>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table id="activity-log-table" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Date/Time</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Username</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Action</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Description</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">IP Address</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#activity-log-table', {
                processing: true,
                serverSide: true,
                ajax: '{{ route('activity-log.data') }}',
                order: [[0, 'desc']],
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'username', name: 'username', orderable: false, searchable: false },
                    { data: 'action_label', name: 'action', orderable: false, searchable: false },
                    { data: 'description', name: 'description' },
                    { data: 'ip_address', name: 'ip_address' },
                ],
            });
        });
    </script>
@endpush
