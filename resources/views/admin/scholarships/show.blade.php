<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Details') }}: {{ $scholarship->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Scholarship Information</h2>
                        <div>
                            <a href="{{ route('admin.scholarships.edit', $scholarship) }}"
                               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.scholarships.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID</dt>
                            <dd class="mt-1 text-gray-900">{{ $scholarship->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-gray-900">{{ $scholarship->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-gray-900">{{ number_format($scholarship->amount, 2) }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-gray-900 whitespace-pre-line">{{ $scholarship->description ?? 'No description.' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-gray-900">{{ $scholarship->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $scholarship->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>

                    {{-- List of student awards --}}
                    @if($scholarship->studentScholarships->isNotEmpty())
                        <h3 class="text-lg font-semibold mb-2">Awarded Students</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grant Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scholarship->studentScholarships as $award)
                                    <tr>
                                        <td class="px-6 py-2">{{ $award->student->user->first_name }} {{ $award->student->user->last_name }}</td>
                                        <td class="px-6 py-2">{{ $award->grant_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-2">{{ $award->end_date ? $award->end_date->format('Y-m-d') : '—' }}</td>
                                        <td class="px-6 py-2">{{ ucfirst($award->status) }}</td>
                                        <td class="px-6 py-2">{{ $award->paid_at ? $award->paid_at->format('Y-m-d') : '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>