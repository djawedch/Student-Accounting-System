<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Award Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Award Information</h2>
                        <div>
                            @can('update', $award)
                                <a href="{{ route('admin.scholarship-awards.edit', $award) }}"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                    Edit
                                </a>
                            @endcan
                            <a href="{{ route('admin.scholarship-awards.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Award ID</dt>
                            <dd class="mt-1 text-gray-900">{{ $award->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student</dt>
                            <dd class="mt-1 text-gray-900">
                                <a href="{{ route('admin.students.show', $award->student) }}"
                                    class="text-indigo-600 hover:underline">
                                    {{ $award->student->user->first_name }}
                                    {{ $award->student->user->last_name }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Scholarship</dt>
                            <dd class="mt-1 text-gray-900">
                                <a href="{{ route('admin.scholarships.show', $award->scholarship) }}"
                                    class="text-indigo-600 hover:underline">
                                    {{ $award->scholarship->name }}
                                </a>
                                ({{ number_format($award->scholarship->amount, 2) }})
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Grant Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $award->grant_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">End Date</dt>
                            <dd class="mt-1 text-gray-900">
                                {{ $award->end_date ? $award->end_date->format('M d, Y') : '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($award->status == 'active') bg-green-100 text-green-800
                                    @elseif($award->status == 'expired') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($award->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Paid At</dt>
                            <dd class="mt-1 text-gray-900">
                                {{ $award->paid_at ? $award->paid_at->format('M d, Y') : '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Reference</dt>
                            <dd class="mt-1 text-gray-900">{{ $award->reference ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-gray-900">{{ $award->created_at->format('M d, Y H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $award->updated_at->format('M d, Y H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>