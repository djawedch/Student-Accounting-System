<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}: {{ $user->first_name }} {{ $user->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">User Information</h2>
                        <div>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $user->id }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $user->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-lg text-gray-900">
                                {{ $user->date_of_birth ? $user->date_of_birth->format('F j, Y') : 'N/A' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ ucfirst($user->role) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($user->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-lg text-gray-900">
                                @if($user->department)
                                    <a href="{{ route('admin.departments.show', $user->department) }}" class="text-indigo-600 hover:underline">
                                        {{ $user->department->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">University</dt>
                            <dd class="mt-1 text-lg text-gray-900">
                                @if($user->department && $user->department->university)
                                    <a href="{{ route('admin.universities.show', $user->department->university) }}" class="text-indigo-600 hover:underline">
                                        {{ $user->department->university->name }}
                                    </a>
                                    ({{ $user->department->university->city }})
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>

                    {{-- Optional: Display audit log entries for this user --}}
                    @if($user->auditLogs && $user->auditLogs->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold mb-4">Recent Activity</h3>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Event</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($user->auditLogs->take(5) as $log)
                                        <tr>
                                            <td class="px-6 py-2 whitespace-nowrap">{{ $log->event_type }}</td>
                                            <td class="px-6 py-2 whitespace-nowrap">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                            <td class="px-6 py-2 whitespace-nowrap">{{ $log->ip_address }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>