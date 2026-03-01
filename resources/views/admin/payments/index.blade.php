<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">All Payments</h2>
                        <a href="{{ route('admin.payments.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Record Payment
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('admin.payments.index') }}"
                        class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="student" class="block text-sm font-medium text-gray-700">Student Name</label>
                            <input type="text" name="student" id="student" value="{{ request('student') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="First or last name">
                        </div>

                        <div>
                            <label for="invoice_id" class="block text-sm font-medium text-gray-700">Invoice #</label>
                            <input type="number" name="invoice_id" id="invoice_id" value="{{ request('invoice_id') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Invoice ID">
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                                Method</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $method)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference</label>
                            <input type="text" name="reference" id="reference" value="{{ request('reference') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Reference number">
                        </div>

                        <div>
                            <label for="amount_min" class="block text-sm font-medium text-gray-700">Min Amount</label>
                            <input type="number" name="amount_min" id="amount_min" value="{{ request('amount_min') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                step="0.01" min="0" placeholder="0">
                        </div>

                        <div>
                            <label for="amount_max" class="block text-sm font-medium text-gray-700">Max Amount</label>
                            <input type="number" name="amount_max" id="amount_max" value="{{ request('amount_max') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                step="0.01" min="0" placeholder="999999">
                        </div>

                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Payment From</label>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Payment To</label>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="flex items-end space-x-2 lg:col-span-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Filter
                            </button>
                            <a href="{{ route('admin.payments.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Reset
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice
                                        #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment
                                        Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.invoices.show', $payment->invoice) }}"
                                                class="text-indigo-600 hover:underline">
                                                #{{ $payment->invoice_id }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $payment->invoice->student->user->first_name ?? '' }}
                                            {{ $payment->invoice->student->user->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->reference ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $payment->payment_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.payments.show', $payment) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('admin.payments.edit', $payment) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No payments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>