<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, AuditLog};
use Illuminate\Http\Request;
use App\Filters\AuditLogFilter;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AuditLog::class);

        $filter = new AuditLogFilter($request);

        $logs = $filter
            ->apply(AuditLog::with('user')->latest())
            ->paginate(20)
            ->withQueryString();

        $users = User::get(['id', 'first_name', 'last_name']);
        $eventTypes = AuditLog::select('event_type')->distinct()->pluck('event_type');
        $modelTypes = AuditLog::select('model_type')->distinct()->pluck('model_type');
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
