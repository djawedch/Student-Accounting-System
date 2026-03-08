<?php

namespace App\Http\Controllers\Admin;

use App\Filters\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\{StoreUserRequest, UpdateUserRequest};
use App\Models\{AuditLog, Department, University, User};
use App\Scopes\UserRoleScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $user = Auth::user();
        $baseQuery = User::query()
            ->where('role', '!=', 'student')
            ->with('department', 'university');

        $users = (new UserFilter($request))
            ->apply((new UserRoleScope)->apply($baseQuery, $user))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = ['super_admin', 'university_admin', 'department_admin', 'staff_admin'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        $user = Auth::user();

        $universities = match ($user->role) {
            'super_admin' => University::orderBy('name')->get(),
            default => University::where('id', $user->university_id)->get()
        };

        $departments = match ($user->role) {
            'super_admin' => Department::with('university')->orderBy('name')->get(),
            'university_admin' => Department::with('university')->where('university_id', $user->university_id)->orderBy('name')->get(),
            'department_admin', 'staff_admin' => Department::with('university')->where('id', $user->department_id)->orderBy('name')->get(),
            default => collect()
        };

        return view('admin.users.create', compact('universities', 'departments'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        if (in_array($validated['role'], ['department_admin', 'staff_admin']) && !empty($validated['department_id'])) {
            $department = Department::find($validated['department_id']);
            $validated['university_id'] = $department->university_id;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $user = User::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'create',
            'model_type' => 'User',
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('department', 'university');
        
        $this->authorize('view', $user);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'student') {
            return redirect()->route('admin.students.edit', $user)
                ->with('error', 'Please use the Student Management section to edit students.');
        }

        $this->authorize('update', $user);

        $user = Auth::user();

        $universities = match ($user->role) {
            'super_admin' => University::orderBy('name')->get(),
            default => University::where('id', $user->university_id)->get()
        };

        $departments = match ($user->role) {
            'super_admin' => Department::with('university')->orderBy('name')->get(),
            'university_admin' => Department::with('university')->where('university_id', $user->university_id)->orderBy('name')->get(),
            'department_admin', 'staff_admin' => Department::with('university')->where('id', $user->department_id)->orderBy('name')->get(),
            default => collect()
        };

        return view('admin.users.edit', compact('universities', 'departments', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if ($user->role === 'student') {
            return redirect()->route('admin.students.index')
                ->with('error', 'Students cannot be updated here.');
        }

        $this->authorize('update', $user);

        $validated = $request->validated();

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user);

        $user->is_active = !$user->is_active;

        $user->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'event_type' => 'update',
            'model_type' => 'User',
            'model_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->first_name} {$user->last_name} {$status} successfully.");
    }
}
