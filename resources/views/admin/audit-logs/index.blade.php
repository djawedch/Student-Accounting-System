<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">System Activity Logs</h2>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('admin.audit-logs.index') }}"
                        class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                            <select name="user_id" id="user_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->first_name }} {{ $user->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="event_type" class="block text-sm font-medium text-gray-700">Event</label>
                            <select name="event_type" id="event_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Events</option>
                                @foreach($eventTypes as $type)
                                    <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="model_type" class="block text-sm font-medium text-gray-700">Model</label>
                            <select name="model_type" id="model_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Models</option>
                                @foreach($modelTypes as $model)
                                    <option value="{{ $model }}" {{ request('model_type') == $model ? 'selected' : '' }}>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="md:col-span-5 flex justify-end space-x-2">
                            <a href="{{ route('admin.audit-logs.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">Reset</a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Filter</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP
                                        Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User
                                        Agent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created
                                        At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->user)
                                                {{ $log->user->first_name }} {{ $log->user->last_name }}
                                            @else
                                                <span class="text-gray-400">Deleted User</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($log->event_type == 'create') bg-green-100 text-green-800
                                                    @elseif($log->event_type == 'update') bg-blue-100 text-blue-800
                                                    @elseif($log->event_type == 'delete') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                {{ ucfirst($log->event_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->model_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->model_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->ip_address ?? '—' }}</td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ $log->user_agent ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.audit-logs.show', $log) }}"
                                                class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">No audit logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>