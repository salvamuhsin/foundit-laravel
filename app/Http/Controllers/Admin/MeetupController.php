<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meetup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MeetupController extends Controller
{
    public function index(Request $request): View
    {
        $meetups = Meetup::with(['claim.item.owner', 'claim.claimant'])
            ->when($request->query('status'), fn ($q, $v) => $q->where('status', $v))
            ->orderByDesc('datetime')
            ->paginate(20)
            ->withQueryString();

        return view('admin.meetups', compact('meetups'));
    }
}
