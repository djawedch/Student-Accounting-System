<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Award Scholarships') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Bulk Award Scholarships</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.student-scholarships.store') }}">
                        @csrf

                        {{-- Students selection --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Students</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($students as $student)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            {{ $student->user->first_name }} {{ $student->user->last_name }} ({{ $student->user->email }}) - Level: {{ $student->level }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Scholarships selection --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Scholarships</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($scholarships as $scholarship)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="scholarship_ids[]" value="{{ $scholarship->id }}"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            {{ $scholarship->name }} ({{ number_format($scholarship->amount, 2) }})
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Common fields for all awards --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label for="grant_date" class="block text-sm font-medium text-gray-700">Grant Date</label>
                                <input type="date" name="grant_date" id="grant_date"
                                       value="{{ old('grant_date', now()->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date (optional)</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ old('end_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    <option value="awarded" {{ old('status') == 'awarded' ? 'selected' : '' }}>Awarded</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="paid_at" class="block text-sm font-medium text-gray-700">Paid At (optional)</label>
                                <input type="date" name="paid_at" id="paid_at"
                                       value="{{ old('paid_at') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference (optional)</label>
                            <input type="text" name="reference" id="reference"
                                   value="{{ old('reference') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.student-scholarships.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Award Scholarships
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>