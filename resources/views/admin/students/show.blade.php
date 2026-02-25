<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Details') }}: {{ $student->first_name }} {{ $student->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Student Information</h2>
                        <div>
                            <a href="{{ route('admin.students.edit', $student) }}"
                               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-2">
                                Edit
                            </a>
                            <a href="{{ route('admin.students.index') }}"
                               class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                                Back to List
                            </a>
                        </div>
                    </div>

                    {{-- Personal Information --}}
                    <h3 class="text-lg font-semibold mb-2">Personal Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->date_of_birth->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($student->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    {{-- Academic Information --}}
                    <h3 class="text-lg font-semibold mb-2">Academic Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-gray-900">
                                @if($student->department)
                                    <a href="{{ route('admin.departments.show', $student->department) }}" class="text-indigo-600 hover:underline">
                                        {{ $student->department->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">University</dt>
                            <dd class="mt-1 text-gray-900">
                                @if($student->department && $student->department->university)
                                    <a href="{{ route('admin.universities.show', $student->department->university) }}" class="text-indigo-600 hover:underline">
                                        {{ $student->department->university->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Level</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->student->level ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->student->academic_year ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Study System</dt>
                            <dd class="mt-1 text-gray-900">{{ ucfirst($student->student->study_system ?? 'N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Baccalaureate Year</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->student->baccalaureate_year ?? 'N/A' }}</dd>
                        </div>
                    </dl>

                    {{-- System Information --}}
                    <h3 class="text-lg font-semibold mb-2">System Information</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-gray-900">{{ $student->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>