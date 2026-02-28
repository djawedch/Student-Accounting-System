<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}: {{ $user->first_name }} {{ $user->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Edit User</h2>

                    {{-- Display validation errors --}}
                    @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')

                        {{-- First Name --}}
                        <div class="mb-4">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name"
                                value="{{ old('first_name', $user->first_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required autofocus>
                            @error('first_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="mb-4">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $user->last_name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            @error('last_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $user->email) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password (optional) --}}
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                New Password <span class="text-gray-500 font-normal">(leave blank to keep current)</span>
                            </label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Date of Birth --}}
                        <div class="mb-4">
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                value="{{ old('date_of_birth', $user->date_of_birth->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            @error('date_of_birth')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Department Dropdown --}}
                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <select name="department_id" id="department_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }} ({{ $department->university->name }})
                                </option>
                                @endforeach
                            </select>
                            @error('department_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div x-data="{ role: '{{ old('role', $user->role ?? '') }}' }">
                            <div class="mb-4">
                                <label for="role">Role</label>
                                <select name="role" id="role" x-model="role" required>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="university_admin">University Admin</option>
                                    <option value="department_admin">Department Admin</option>
                                    <option value="staff_admin">Staff</option>
                                </select>
                            </div>

                            <div x-show="role === 'university_admin'" x-cloak>
                                <label for="university_id">University</label>
                                <select name="university_id" id="university_id">
                                    <option value="">Select University</option>
                                    @foreach($universities as $university)
                                    <option value="{{ $university->id }}">{{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="['department_admin', 'staff_admin'].includes(role)" x-cloak>
                                <label for="department_id">Department</label>
                                <select name="department_id" id="department_id">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="role === 'super_admin'">
                                <!-- no university or department fields -->
                            </div>
                        </div>

                        {{-- Active Status --}}
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Active (uncheck to deactivate)</span>
                            </label>
                            @error('is_active')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.users.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>