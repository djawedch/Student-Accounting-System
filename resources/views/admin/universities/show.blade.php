<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">University Details</h2>
                        <div>
                            <a href="{{ route('universities.edit', $university) }}"
                               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('universities.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    {{-- University Information --}}
                    <div class="mb-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ $university->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">City</dt>
                                <dd class="mt-1 text-lg text-gray-900">{{ $university->city }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-gray-900">{{ $university->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-gray-900">{{ $university->updated_at->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Departments List --}}
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Departments in this University</h3>

                        @if($university->departments->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($university->departments as $department)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $department->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $department->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $department->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-500">No departments have been added to this university yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>