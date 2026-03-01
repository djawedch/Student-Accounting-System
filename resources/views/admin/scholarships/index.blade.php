<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">All Scholarships</h2>
                        <a href="{{ route('admin.scholarships.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Add New Scholarship
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 text-red-600">{{ session('error') }}</div>
                    @endif

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('admin.scholarships.index') }}"
                        class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Scholarship Name</label>
                            <input type="text" name="name" id="name" value="{{ request('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Search by name">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <input type="text" name="description" id="description" value="{{ request('description') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Search in description">
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
                            <a href="{{ route('admin.scholarships.index') }}"
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
                                        Description</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Created</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($scholarships as $scholarship)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $scholarship->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $scholarship->name }}</td>
                                        <td class="px-6 py-4 max-w-xs truncate">{{ $scholarship->description ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($scholarship->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $scholarship->created_at->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.scholarships.show', $scholarship) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('admin.scholarships.edit', $scholarship) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('admin.scholarships.destroy', $scholarship) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this scholarship?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No scholarships found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $scholarships->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>