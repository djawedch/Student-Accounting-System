<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">All Invoices</h2>
                        <a href="{{ route('admin.invoices.create') }}"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Generate New Invoices
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 text-green-600">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issued
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $invoice->student->user->first_name ?? '' }}
                                            {{ $invoice->student->user->last_name ?? '' }}
                                            <span
                                                class="text-xs text-gray-500">({{ $invoice->student->level ?? 'N/A' }})</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $invoice->fee->name }}
                                            <span
                                                class="text-xs text-gray-500">({{ $invoice->fee->department->name ?? '' }})</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ number_format($invoice->fee->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @if($invoice->status == 'paid') bg-green-100 text-green-800
                                                            @elseif($invoice->status == 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->issued_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $invoice->due_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.invoices.show', $invoice) }}"
                                                class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <a href="{{ route('admin.invoices.edit', $invoice) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>