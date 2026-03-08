<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, AuditLog};
use Illuminate\Http\Request;
use App\Filters\AuditLogFilter;
use App\Scopes\AuditLogRoleScope;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AuditLog::class);

        $user = Auth::user();
        $baseQuery = AuditLog::with('user')->latest();

        $logs = (new AuditLogFilter($request))
            ->apply((new AuditLogRoleScope)->apply($baseQuery, $user))
            ->paginate(20)
            ->withQueryString();

        $users = $user->role === 'super_admin'
            ? User::get(['id', 'first_name', 'last_name'])
            : User::where('university_id', $user->university_id)->get(['id', 'first_name', 'last_name']);

        $eventTypes = AuditLog::distinct()->pluck('event_type');
        $modelTypes = AuditLog::distinct()->pluck('model_type');
        $roles = User::distinct()->pluck('role');

        return view('admin.audit-logs.index', compact('logs', 'users', 'eventTypes', 'modelTypes', 'roles'));
    }

    public function show(AuditLog $auditLog)
    {
        $this->authorize('view', $auditLog);

        $auditLog->load('user');

        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
