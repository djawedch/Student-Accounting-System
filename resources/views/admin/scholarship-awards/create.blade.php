<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Award Scholarship') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-semibold mb-4">Bulk Scholarship Award</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.scholarship-awards.store') }}">
                        @csrf

                        {{-- Step 1: University --}}
                        <div class="mb-4">
                            <label for="university_id" class="block text-sm font-medium text-gray-700">University</label>
                            <select id="university_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ auth()->user()->role !== 'super_admin' ? 'disabled' : '' }}>
                                @if(auth()->user()->role === 'super_admin')
                                    <option value="">-- Select University --</option>
                                @endif
                                @foreach($universities as $university)
                                    <option value="{{ $university->id }}"
                                        {{ auth()->user()->university_id == $university->id ? 'selected' : '' }}>
                                        {{ $university->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(auth()->user()->role !== 'super_admin')
                                <input type="hidden" name="university_id" value="{{ auth()->user()->university_id }}">
                            @endif
                        </div>

                        {{-- Step 2: Department --}}
                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <select id="department_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                {{ in_array(auth()->user()->role, ['department_admin', 'staff_admin']) ? 'disabled' : '' }}>
                                @if(!in_array(auth()->user()->role, ['department_admin', 'staff_admin']))
                                    <option value="">-- Select Department --</option>
                                @endif
                                @foreach($universities as $university)
                                    @foreach($university->departments as $department)
                                        <option value="{{ $department->id }}"
                                            data-university="{{ $university->id }}"
                                            {{ auth()->user()->department_id == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @if(in_array(auth()->user()->role, ['department_admin', 'staff_admin']))
                                <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                            @endif
                        </div>

                        {{-- Step 3: Level + Study System --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                                <select id="level"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- All Levels --</option>
                                    <option value="L1">L1</option>
                                    <option value="L2">L2</option>
                                    <option value="L3">L3</option>
                                    <option value="M1">M1</option>
                                    <option value="M2">M2</option>
                                </select>
                            </div>
                            <div>
                                <label for="study_system" class="block text-sm font-medium text-gray-700">Study System</label>
                                <select id="study_system"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- All Systems --</option>
                                    <option value="LMD">LMD</option>
                                    <option value="Classic">Classic</option>
                                </select>
                            </div>
                        </div>

                        {{-- Step 4: Students --}}
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-medium text-gray-700">Select Students</label>
                                <div class="flex gap-2">
                                    <button type="button" id="select-all-students"
                                        class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200">
                                        Select All
                                    </button>
                                    <button type="button" id="deselect-all-students"
                                        class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        Deselect All
                                    </button>
                                </div>
                            </div>
                            <div id="students-container"
                                class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded-md p-4 min-h-[80px] bg-gray-50">
                                <p class="text-sm text-gray-400 col-span-2">
                                    Select a university and department to load students.
                                </p>
                            </div>
                        </div>

                        {{-- Step 5: Scholarships --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Scholarships</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded-md p-4 bg-gray-50">
                                @forelse($scholarships as $scholarship)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="scholarship_ids[]" value="{{ $scholarship->id }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">
                                            {{ $scholarship->name }} — {{ number_format($scholarship->amount, 2) }}
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-400 col-span-2">No scholarships available.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Grant Date --}}
                        <div class="mb-4">
                            <label for="grant_date" class="block text-sm font-medium text-gray-700">Grant Date</label>
                            <input type="date" name="grant_date" id="grant_date"
                                value="{{ old('grant_date', now()->format('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                        </div>

                        {{-- End Date --}}
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="awarded" {{ old('status') == 'awarded' ? 'selected' : '' }}>Awarded</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        {{-- Paid At --}}
                        <div class="mb-4">
                            <label for="paid_at" class="block text-sm font-medium text-gray-700">Paid At <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="date" name="paid_at" id="paid_at"
                                value="{{ old('paid_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Reference --}}
                        <div class="mb-4">
                            <label for="reference" class="block text-sm font-medium text-gray-700">Reference <span class="text-gray-400 font-normal">(optional)</span></label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.scholarship-awards.index') }}"
                                class="px-4 py-2 bg-gray-300 rounded-md mr-2 hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Award Scholarships
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const universitySelect  = document.getElementById('university_id');
    const departmentSelect  = document.getElementById('department_id');
    const levelSelect       = document.getElementById('level');
    const studySystemSelect = document.getElementById('study_system');
    const studentsContainer = document.getElementById('students-container');

    universitySelect.addEventListener('change', function () {
        const universityId = parseInt(this.value);
        const options = departmentSelect.querySelectorAll('option');
        options.forEach(opt => {
            if (!opt.value) return;
            opt.style.display = (!universityId || parseInt(opt.dataset.university) === universityId) ? '' : 'none';
        });
        departmentSelect.value = '';
        clearStudents();
    });

    departmentSelect.addEventListener('change', loadStudents);
    levelSelect.addEventListener('change', loadStudents);
    studySystemSelect.addEventListener('change', loadStudents);

    function loadStudents() {
        const departmentId = parseInt(departmentSelect.value);
        const level        = levelSelect.value;
        const studySystem  = studySystemSelect.value;

        clearStudents();

        if (!departmentId) {
            studentsContainer.innerHTML = '<p class="text-sm text-gray-400 col-span-2">Select a university and department to load students.</p>';
            return;
        }

        fetch(`/admin/students/filter?department_id=${departmentId}&level=${level}&study_system=${studySystem}`)
            .then(res => res.json())
            .then(students => {
                studentsContainer.innerHTML = '';
                if (students.length === 0) {
                    studentsContainer.innerHTML = '<p class="text-sm text-gray-400 col-span-2">No students found.</p>';
                    return;
                }
                students.forEach(student => {
                    const label = document.createElement('label');
                    label.className = 'inline-flex items-center';
                    label.innerHTML = `
                        <input type="checkbox" name="student_ids[]" value="${student.id}"
                            class="student-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">
                            ${student.first_name} ${student.last_name} — Level ${student.level} (${student.study_system})
                        </span>
                    `;
                    studentsContainer.appendChild(label);
                });
            })
            .catch(() => {
                studentsContainer.innerHTML = '<p class="text-sm text-red-400 col-span-2">Failed to load students.</p>';
            });
    }

    function clearStudents() {
        studentsContainer.innerHTML = '';
    }

    document.getElementById('select-all-students').addEventListener('click', () => {
        studentsContainer.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = true);
    });

    document.getElementById('deselect-all-students').addEventListener('click', () => {
        studentsContainer.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
    });

    // Auto-trigger on page load for locked roles
    document.addEventListener('DOMContentLoaded', function () {
        if (departmentSelect.value) {
            loadStudents();
        }
        if (universitySelect.value) {
            const universityId = parseInt(universitySelect.value);
            const options = departmentSelect.querySelectorAll('option');
            options.forEach(opt => {
                if (!opt.value) return;
                opt.style.display = (!universityId || parseInt(opt.dataset.university) === universityId) ? '' : 'none';
            });
        }
    });
</script>