<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, AuditLog};
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        $users = User::get(['id', 'first_name', 'last_name']);
        $eventTypes = AuditLog::select('event_type')->distinct()->pluck('event_type');
        $modelTypes = AuditLog::select('model_type')->distinct()->pluck('model_type');

        return view('admin.audit-logs.index', compact('logs', 'users', 'eventTypes', 'modelTypes'));
    }

    public function show(AuditLog $auditLog)
    {
        $auditLog->load('user');
        
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
