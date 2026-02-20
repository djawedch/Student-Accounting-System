<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Details') }} #{{ $invoice->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Invoice Information</h2>
                        <div>
                            <a href="{{ route('admin.invoices.edit', $invoice) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.invoices.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Invoice ID</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $invoice->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($invoice->status == 'paid') bg-green-100 text-green-800
                                    @elseif($invoice->status == 'unpaid') bg-yellow-100 text-yellow-800
                                    @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student</dt>
                            <dd class="mt-1 text-gray-900">
                                <a href="{{ route('admin.students.show', $invoice->student) }}"
                                    class="text-indigo-600 hover:underline">
                                    {{ $invoice->student->user->first_name }} {{ $invoice->student->user->last_name }}
                                </a>
                                <br>
                                <span class="text-sm text-gray-600">Level: {{ $invoice->student->level }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-gray-900">{{ $invoice->student->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fee</dt>
                            <dd class="mt-1 text-gray-900">
                                <a href="{{ route('admin.fees.show', $invoice->fee) }}"
                                    class="text-indigo-600 hover:underline">
                                    {{ $invoice->fee->name }}
                                </a>
                                <br>
                                <span class="text-sm text-gray-600">Department:
                                    {{ $invoice->fee->department->name }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ number_format($invoice->fee->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Issued Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $invoice->issued_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-gray-900">{{ $invoice->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $invoice->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>