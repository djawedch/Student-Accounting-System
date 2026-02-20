<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Fee') }}: {{ $fee->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Edit Fee</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.fees.update', $fee) }}">
                        @csrf
                        @method('PATCH')

                        {{-- Fee Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Fee Name</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $fee->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        {{-- Department Dropdown --}}
                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <select name="department_id" id="department_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" 
                                        {{ old('department_id', $fee->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }} ({{ $department->university->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" 
                                   value="{{ old('amount', $fee->amount) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required step="0.01" min="0">
                        </div>

                        {{-- Academic Year --}}
                        <div class="mb-4">
                            <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <input type="text" name="academic_year" id="academic_year" 
                                   value="{{ old('academic_year', $fee->academic_year) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required placeholder="e.g., 2025/2026">
                        </div>

                        {{-- Description (optional) --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $fee->description) }}</textarea>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.fees.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update Fee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>