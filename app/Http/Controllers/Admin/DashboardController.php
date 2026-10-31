<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Claim;
use App\Models\ContactMessage;
use App\Models\Item;
use App\Models\Meetup;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $counts = [
            'users' => User::where('role', 'user')->count(),
            'restricted' => User::where('restricted', true)->count(),
            'items' => Item::count(),
            'claims' => Claim::count(),
            'pendingClaims' => Claim::where('status', 'pending')->count(),
            'openMeetups' => Meetup::whereIn('status', ['proposed', 'changed', 'confirmed'])->count(),
            'returned' => Item::withStatus('returned')->count(),
            'contact' => ContactMessage::count(),
        ];

        $latestActivity = ActivityLog::with('user')->latest()->take(10)->get();
        $newestUsers = User::where('role', 'user')->latest()->take(5)->get();

        return view('admin.index', compact('counts', 'latestActivity', 'newestUsers'));
    }
}
