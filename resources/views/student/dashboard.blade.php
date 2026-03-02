<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Welcome, {{ $user->first_name }} {{ $user->last_name }}!</h3>
                    <p class="mt-2">You are logged in as a student.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Universities -->
                <a href="{{ route('student.universities.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-indigo-600">
                        <h4 class="text-lg font-semibold">Universities</h4>
                        <p class="text-sm text-gray-600">View all universities</p>
                    </div>
                </a>

                <!-- Departments -->
                <a href="{{ route('student.departments.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-green-600">
                        <h4 class="text-lg font-semibold">Departments</h4>
                        <p class="text-sm text-gray-600">View all departments</p>
                    </div>
                </a>

                <!-- Fees (my department) -->
                <a href="{{ route('student.fees.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-amber-600">
                        <h4 class="text-lg font-semibold">Fees</h4>
                        <p class="text-sm text-gray-600">Fees for your department</p>
                    </div>
                </a>

                <!-- My Invoices -->
                <a href="{{ route('student.invoices.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-teal-600">
                        <h4 class="text-lg font-semibold">My Invoices</h4>
                        <p class="text-sm text-gray-600">View your invoices</p>
                    </div>
                </a>

                <!-- My Payments -->
                <a href="{{ route('student.payments.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-pink-600">
                        <h4 class="text-lg font-semibold">My Payments</h4>
                        <p class="text-sm text-gray-600">View your payments</p>
                    </div>
                </a>

                <!-- My Scholarship Awards -->
                <a href="{{ route('student.scholarship-awards.index') }}" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6 border-l-4 border-blue-600">
                        <h4 class="text-lg font-semibold">My Scholarships</h4>
                        <p class="text-sm text-gray-600">View your scholarship awards</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>