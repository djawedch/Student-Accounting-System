<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Details') }} #{{ $payment->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Payment Information</h2>
                        <div>
                            <a href="{{ route('admin.payments.edit', $payment) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.payments.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment ID</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ $payment->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Invoice</dt>
                            <dd class="mt-1 text-lg text-gray-900">
                                <a href="{{ route('admin.invoices.show', $payment->invoice) }}"
                                    class="text-indigo-600 hover:underline">
                                    #{{ $payment->invoice_id }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student</dt>
                            <dd class="mt-1 text-gray-900">
                                <a href="{{ route('admin.students.show', $payment->invoice->student) }}"
                                    class="text-indigo-600 hover:underline">
                                    {{ $payment->invoice->student->user->first_name ?? '' }}
                                    {{ $payment->invoice->student->user->last_name ?? '' }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fee</dt>
                            <dd class="mt-1 text-gray-900">{{ $payment->invoice->fee->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-lg text-gray-900">{{ number_format($payment->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Reference</dt>
                            <dd class="mt-1 text-gray-900">{{ $payment->reference ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                            <dd class="mt-1 text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Recorded At</dt>
                            <dd class="mt-1 text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $payment->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>