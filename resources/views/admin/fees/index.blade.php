<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fees Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">All Fees</h2>
                        <a href="{{ route('admin.fees.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Add New Fee
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 text-red-600">{{ session('error') }}</div>
                    @endif

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('admin.fees.index') }}"
                        class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Fee Name</label>
                            <input type="text" name="name" id="name" value="{{ request('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Search by fee name">
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="department" id="department" value="{{ request('department') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Department name">
                        </div>

                        <div>
                            <label for="university" class="block text-sm font-medium text-gray-700">University</label>
                            <input type="text" name="university" id="university" value="{{ request('university') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="University name">
                        </div>

                        <div>
                            <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic
                                Year</label>
                            <input type="text" name="academic_year" id="academic_year"
                                value="{{ request('academic_year') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="e.g., 2025/2026">
                        </div>

                        <div>
                            <label for="amount_min" class="block text-sm font-medium text-gray-700">Min Amount</label>
                            <input type="number" name="amount_min" id="amount_min" value="{{ request('amount_min') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                step="0.01" min="0" placeholder="0">
                        </div>

                        <div>
                            <label for="amount_max" class="block text-sm font-medium text-gray-700">Max Amount</label>
                            <input type="number" name="amount_max" id="amount_max" value="{{ request('amount_max') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                step="0.01" min="0" placeholder="999999">
                        </div>

                        <div class="flex items-end space-x-2 lg:col-span-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.fees.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Reset
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        University</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Academic Year</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($fees as $fee)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fee->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fee->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fee->department->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $fee->department->university->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($fee->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $fee->academic_year }}</td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ $fee->description ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.fees.show', $fee) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('admin.fees.edit', $fee) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.fees.destroy', $fee) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this fee?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No fees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $fees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>