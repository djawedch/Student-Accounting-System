<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Department, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'student')->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:super_admin,university_admin,department_admin,staff_admin',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('department.university');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'student') {
            return redirect()->route('admin.students.edit', $user)
                ->with('error', 'Please use the Student Management section to edit students.');
        }

        $departments = Department::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'student') {
            return redirect()->route('admin.students.index')
                ->with('error', 'Students cannot be updated here.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|in:super_admin,university_admin,department_admin,staff_admin',
            'is_active' => 'sometimes|boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->first_name} {$user->last_name} {$status} successfully.");
    }
}
