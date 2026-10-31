<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $like = fn (string $v) => '%'.addcslashes($v, '%_\\').'%';

        $items = Item::with(['owner', 'claims'])
            ->withCount('claims')
            ->when($request->query('type'), fn ($q, $v) => $q->where('type', $v))
            ->withStatus($request->query('status'))
            ->when($request->query('q'), fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('title', 'like', $like($v))
                ->orWhere('location', 'like', $like($v))
                ->orWhereHas('owner', fn ($u) => $u->where('name', 'like', $like($v)))))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.items', compact('items'));
    }
}
