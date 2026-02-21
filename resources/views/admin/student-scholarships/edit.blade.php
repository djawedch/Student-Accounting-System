<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Scholarship Award') }} #{{ $studentScholarship->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Edit Award</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.student-scholarships.update', $studentScholarship) }}">
                        @csrf
                        @method('PATCH')

                        {{-- Student selection (disabled? or read-only) --}}
                        <div class="mb-4">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select name="student_id" id="student_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id', $studentScholarship->student_id) == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->first_name }} {{ $student->user->last_name }}
                                        ({{ $student->user->email }}) - Level: {{ $student->level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Scholarship selection --}}
                        <div class="mb-4">
                            <label for="scholarship_id"
                                class="block text-sm font-medium text-gray-700">Scholarship</label>
                            <select name="scholarship_id" id="scholarship_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">-- Select Scholarship --</option>
                                @foreach($scholarships as $scholarship)
                                    <option value="{{ $scholarship->id }}" {{ old('scholarship_id', $studentScholarship->scholarship_id) == $scholarship->id ? 'selected' : '' }}>
                                        {{ $scholarship->name }} ({{ number_format($scholarship->amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Grant Date --}}
                        <div class="mb-4">
                            <label for="grant_date" class="block text-sm font-medium text-gray-700">Grant Date</label>
                            <input type="date" name="grant_date" id="grant_date"
                                value="{{ old('grant_date', $studentScholarship->grant_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- End Date (optional) --}}
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date
                                (optional)</label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $studentScholarship->end_date ? $studentScholarship->end_date->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="awarded" {{ old('status', $studentScholarship->status) == 'awarded' ? 'selected' : '' }}>Awarded</option>
                                <option value="paid" {{ old('status', $studentScholarship->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('status', $studentScholarship->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        {{-- Paid At (optional) --}}
                        <div class="mb-4">
                            <label for="paid_at" class="block text-sm font-medium text-gray-700">Paid At
                                (optional)</label>
                            <input type="date" name="paid_at" id="paid_at"
                                value="{{ old('paid_at', $studentScholarship->paid_at ? $studentScholarship->paid_at->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Reference (optional) --}}
                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference
                                (optional)</label>
                            <input type="text" name="reference" id="reference"
                                value="{{ old('reference', $studentScholarship->reference) }}"
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
                                Update Award
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>