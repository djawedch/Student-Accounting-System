<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Payment') }} #{{ $payment->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Edit Payment</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.payments.update', $payment) }}">
                        @csrf
                        @method('PATCH')

                        {{-- Invoice info (read-only, for context) --}}
                        <div class="mb-4 p-4 bg-gray-50 rounded-md">
                            <p><strong>Invoice #{{ $payment->invoice_id }}</strong></p>
                            <p>Student: {{ $payment->invoice->student->user->first_name }}
                                {{ $payment->invoice->student->user->last_name }}</p>
                            <p>Fee: {{ $payment->invoice->fee->name }} - Amount:
                                {{ number_format($payment->invoice->fee->amount, 2) }}</p>
                            <p>Current total paid: {{ number_format($payment->invoice->total_paid, 2) }}</p>
                        </div>

                        {{-- Amount --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required step="0.01" min="0.01">
                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                                Method</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">-- Select Method --</option>
                                <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer
                                </option>
                                <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>Check</option>
                                <option value="online" {{ old('payment_method', $payment->payment_method) == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>

                        {{-- Reference (optional) --}}
                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference</label>
                            <input type="text" name="reference" id="reference"
                                value="{{ old('reference', $payment->reference) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Payment Date --}}
                        <div class="mb-4">
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment
                                Date</label>
                            <input type="date" name="payment_date" id="payment_date"
                                value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.payments.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>