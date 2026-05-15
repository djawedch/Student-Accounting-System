<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Universities -->
                <a href="{{ route('student.university.show') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-indigo-600">
                        <h4 class="text-lg font-semibold text-gray-900">My University</h4>
                        <p class="text-sm text-gray-600">View your university</p>
                    </div>
                </a>

                <!-- Departments -->
                <a href="{{ route('student.department.show') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-green-600">
                        <h4 class="text-lg font-semibold text-gray-900">My Department</h4>
                        <p class="text-sm text-gray-600">View your department</p>
                    </div>
                </a>

                <!-- Fees (my department) -->
                <a href="{{ route('student.fees.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-amber-600">
                        <h4 class="text-lg font-semibold text-gray-900">Fees</h4>
                        <p class="text-sm text-gray-600">Fees for your department</p>
                    </div>
                </a>

                <!-- My Invoices -->
                <a href="{{ route('student.invoices.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-teal-600">
                        <h4 class="text-lg font-semibold text-gray-900">My Invoices</h4>
                        <p class="text-sm text-gray-600">View your invoices</p>
                    </div>
                </a>

                <!-- My Payments -->
                <a href="{{ route('student.payments.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-pink-600">
                        <h4 class="text-lg font-semibold text-gray-900">My Payments</h4>
                        <p class="text-sm text-gray-600">View your payments</p>
                    </div>
                </a>

                <!-- My Scholarship Awards -->
                <a href="{{ route('student.scholarship-awards.index') }}"
                    class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-blue-600">
                        <h4 class="text-lg font-semibold text-gray-900">My Scholarships</h4>
                        <p class="text-sm text-gray-600">View your scholarship awards</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>