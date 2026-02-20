<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">New Payment</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.payments.store') }}">
                        @csrf

                        {{-- Invoice selection --}}
                        <div class="mb-4">
                            <label for="invoice_id" class="block text-sm font-medium text-gray-700">Invoice</label>
                            <select name="invoice_id" id="invoice_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">-- Select Invoice --</option>
                                @foreach($invoices as $invoice)
                                    <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                        #{{ $invoice->id }} – {{ $invoice->student->user->first_name }}
                                        {{ $invoice->student->user->last_name }}
                                        ({{ number_format($invoice->fee->amount, 2) }} – Paid:
                                        {{ number_format($invoice->total_paid, 2) }}
                                        , Remaining: {{ number_format($invoice->remaining_amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
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
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="ccp" {{ old('payment_method') == 'ccp' ? 'selected' : '' }}>Ccp
                                </option>
                            </select>
                        </div>

                        {{-- Reference (optional) --}}
                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference
                                (optional)</label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Payment Date --}}
                        <div class="mb-4">
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment
                                Date</label>
                            <input type="date" name="payment_date" id="payment_date"
                                value="{{ old('payment_date', now()->format('Y-m-d')) }}"
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
                                Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>