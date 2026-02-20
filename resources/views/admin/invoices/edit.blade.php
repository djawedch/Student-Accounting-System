<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice') }} #{{ $invoice->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Edit Invoice</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}">
                        @csrf
                        @method('PATCH')

                        {{-- Read-only info (for context) --}}
                        <div class="mb-4 p-4 bg-gray-50 rounded-md">
                            <p><strong>Student:</strong> {{ $invoice->student->user->first_name }}
                                {{ $invoice->student->user->last_name }} ({{ $invoice->student->level }})</p>
                            <p><strong>Fee:</strong> {{ $invoice->fee->name }} -
                                {{ number_format($invoice->fee->amount, 2) }}</p>
                        </div>

                        {{-- Status dropdown --}}
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="unpaid" {{ old('status', $invoice->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="partially_paid" {{ old('status', $invoice->status) == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>
                                    Paid</option>
                                <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>

                        {{-- Issued Date --}}
                        <div class="mb-4">
                            <label for="issued_date" class="block text-sm font-medium text-gray-700">Issued Date</label>
                            <input type="date" name="issued_date" id="issued_date"
                                value="{{ old('issued_date', $invoice->issued_date->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- Due Date --}}
                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" name="due_date" id="due_date"
                                value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
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
                                Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>