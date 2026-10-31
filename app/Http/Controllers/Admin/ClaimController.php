<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    // Read-only. Claim answers are private to the poster, so they are never loaded into the admin view.
    public function index(Request $request): View
    {
        $like = fn (string $v) => '%'.addcslashes($v, '%_\\').'%';

        $claims = Claim::with(['item.owner', 'claimant'])
            ->when($request->query('status'), fn ($q, $v) => $q->where('status', $v))
            ->when($request->query('q'), fn ($q, $v) => $q->where(fn ($w) => $w
                ->whereHas('item', fn ($i) => $i->where('title', 'like', $like($v)))
                ->orWhereHas('claimant', fn ($u) => $u->where('name', 'like', $like($v)))))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.claims', compact('claims'));
    }
}
