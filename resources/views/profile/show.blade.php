<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Profile Information</h2>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->date_of_birth->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-gray-900">{{ ucfirst($user->role) }}</dd>
                        </div>

                        {{-- University (for super_admin/university_admin) --}}
                        @if($user->university)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">University</dt>
                                <dd class="mt-1 text-gray-900">{{ $user->university->name }} ({{ $user->university->city }})
                                </dd>
                            </div>
                        @endif

                        {{-- Department (for department_admin/staff_admin/student) --}}
                        @if($user->department)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Department</dt>
                                <dd class="mt-1 text-gray-900">{{ $user->department->name }}</dd>
                            </div>
                        @endif

                        {{-- Student-specific fields --}}
                        @if($user->role === 'student' && $user->student)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Level</dt>
                                <dd class="mt-1 text-gray-900">{{ $user->student->level }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                                <dd class="mt-1 text-gray-900">{{ $user->student->academic_year }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Study System</dt>
                                <dd class="mt-1 text-gray-900">{{ ucfirst($user->student->study_system) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Baccalaureate Year</dt>
                                <dd class="mt-1 text-gray-900">{{ $user->student->baccalaureate_year }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="mt-1">
                                @if($user->is_active)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>