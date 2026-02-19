<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Edit University</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('universities.update', $university) }}">
                        @csrf
                        @method('PATCH')

                        {{-- University Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $university->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required autofocus>
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- City --}}
                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="city"
                                   value="{{ old('city', $university->city) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('city')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('universities.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update University
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>