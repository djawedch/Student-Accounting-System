<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Log Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Log Entry #{{ $auditLog->id }}</h2>
                        <a href="{{ route('admin.audit-logs.index') }}"
                            class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Back to List</a>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User</dt>
                            <dd class="mt-1 text-gray-900">
                                @if($auditLog->user)
                                    {{ $auditLog->user->first_name }} {{ $auditLog->user->last_name }} (ID:
                                    {{ $auditLog->user_id }})
                                @else
                                    Deleted User (ID: {{ $auditLog->user_id }})
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                            <dd class="mt-1 text-gray-900">{{ ucfirst($auditLog->event_type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Model Type</dt>
                            <dd class="mt-1 text-gray-900">{{ $auditLog->model_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Model ID</dt>
                            <dd class="mt-1 text-gray-900">{{ $auditLog->model_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                            <dd class="mt-1 text-gray-900">{{ $auditLog->ip_address ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                            <dd class="mt-1 text-gray-900 break-words">{{ $auditLog->user_agent ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-gray-900">{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>