<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Bulk Invoice Generation</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.invoices.store') }}">
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
                                            {{ $student->user->first_name }} {{ $student->user->last_name }}
                                            ({{ $student->user->email }}) - Level: {{ $student->level }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Fees selection --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Fees</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($fees as $fee)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="fee_ids[]" value="{{ $fee->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            {{ $fee->name }} ({{ $fee->department->name }}) -
                                            {{ number_format($fee->amount, 2) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Issued date (default today) --}}
                        <div class="mb-4">
                            <label for="issued_date" class="block text-sm font-medium text-gray-700">Issued Date</label>
                            <input type="date" name="issued_date" id="issued_date"
                                value="{{ old('issued_date', now()->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- Due date (default 30 days) --}}
                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date"
                                value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.invoices.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Generate Invoices
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>