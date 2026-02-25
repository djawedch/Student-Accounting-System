<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\{StoreUserRequest, UpdateUserRequest};
use App\Models\{Department, User};
use Illuminate\Support\Facades\Hash;

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

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

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

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($user->role === 'student') {
            return redirect()->route('admin.students.index')
                ->with('error', 'Students cannot be updated here.');
        }

        $validated = $request->validated();

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
