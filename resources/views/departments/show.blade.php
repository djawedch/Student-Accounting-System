<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Department Details') }}: {{ $department->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Department Information</h2>
                        <div>
                            <a href="{{ route('departments.edit', $department) }}"
                               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('departments.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    {{-- Department Details --}}
                    <div class="mb-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Department ID</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ $department->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ $department->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">University</dt>
                                <dd class="mt-1 text-lg text-gray-900">
                                    <a href="{{ route('universities.show', $department->university) }}" class="text-indigo-600 hover:underline">
                                        {{ $department->university->name }}
                                    </a>
                                    ({{ $department->university->city }})
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-gray-900">{{ $department->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-gray-900">{{ $department->updated_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Users in this Department --}}
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Users in this Department</h3>

                        @if($department->users->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($department->users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($user->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500">No users are assigned to this department yet.</p>
                        @endif
                    </div>

                    {{-- Fees defined for this Department --}}
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Fees in this Department</h3>

                        @if($department->fees->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($department->fees as $fee)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($fee->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->academic_year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->description ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500">No fees have been defined for this department yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>