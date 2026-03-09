<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fee Details') }}: {{ $fee->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Fee Information</h2>
                        <a href="{{ route('student.fees.index') }}"
                            class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                            Back to List
                        </a>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fee Name</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $fee->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-lg text-gray-900">
                                {{ $fee->department->name }}
                                ({{ $fee->department->university->name ?? 'N/A' }})
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ number_format($fee->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $fee->academic_year }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-gray-900 whitespace-pre-line">
                                {{ $fee->description ?? 'No description provided.' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-gray-900">{{ $fee->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $fee->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>