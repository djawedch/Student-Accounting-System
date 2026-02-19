<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Universities</h2>
                        <a href="{{ route('universities.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Add University</a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 text-red-600">{{ session('error') }}</div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    City</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Departments</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($universities as $university)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $university->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $university->city }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $university->departments_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('universities.show', $university) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('universities.edit', $university) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('universities.destroy', $university) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $universities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>