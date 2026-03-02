<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Scholarship Awards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Scholarship Awards</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Scholarship</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grant
                                        Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid At
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Reference</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($awards as $award)
                                    <tr>
                                        <td class="px-6 py-4">{{ $award->scholarship->name }}</td>
                                        <td class="px-6 py-4">{{ $award->grant_date->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4">
                                            {{ $award->end_date ? $award->end_date->format('Y-m-d') : '—' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($award->status == 'active') bg-green-100 text-green-800
                                                    @elseif($award->status == 'expired') bg-gray-100 text-gray-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                {{ ucfirst($award->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $award->paid_at ? $award->paid_at->format('Y-m-d') : '—' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $award->reference ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No scholarship awards
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>