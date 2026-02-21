<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Welcome message --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            {{-- Quick access cards (visible only to admin/super_admin) --}}
            @if(auth()->user() && in_array(auth()->user()->role, ['super_admin', 'university_admin']))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Universities Card --}}
                    <a href="{{ route('universities.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-indigo-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Universities</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage universities and their details</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Departments Card --}}
                    <a href="{{ route('departments.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-green-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Departments</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage departments within universities</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- System Users Card (non‑student) --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-purple-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">System Users</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage admins, staff, and other roles</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Students Card --}}
                    <a href="{{ route('admin.students.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-orange-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Students</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage student profiles and academic info</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Fees Card --}}
                    <a href="{{ route('admin.fees.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-amber-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Fees</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage fee structures by department</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Invoices Card --}}
                    <a href="{{ route('admin.invoices.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-teal-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Invoices</h3>
                                    <p class="text-sm text-gray-600 mt-1">Generate and manage student invoices</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Payments Card --}}
                    <a href="{{ route('admin.payments.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-pink-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Payments</h3>
                                    <p class="text-sm text-gray-600 mt-1">Record and manage payments</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Scholarships Card --}}
                    <a href="{{ route('admin.scholarships.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-blue-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Scholarships</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage scholarship programs</p>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Scholarship Awards Card --}}
                    <a href="{{ route('admin.student-scholarships.index') }}"
                        class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 border-l-4 border-cyan-600">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Scholarship Awards</h3>
                                    <p class="text-sm text-gray-600 mt-1">Manage student scholarship awards</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>