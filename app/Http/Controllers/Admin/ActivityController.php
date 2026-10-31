<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::with('user')
            ->when($request->query('user'), fn ($q, $v) => $q->where('user_id', $v))
            ->when($request->query('action'), fn ($q, $v) => $q->where('action', $v))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $members = User::where('role', 'user')->orderBy('name')->get(['id', 'name']);
        $actions = ActivityLog::distinct()->orderBy('action')->pluck('action');

        return view('admin.activity', compact('logs', 'members', 'actions'));
    }
}
