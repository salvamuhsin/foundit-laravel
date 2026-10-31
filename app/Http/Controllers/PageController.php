<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ContactMessage;
use App\Models\Item;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Home page: resources/views/index.blade.php
    public function index(): View
    {
        $recent = Item::with('claims')->latest()->take(6)->get();

        $stats = [
            'items' => Item::count(),
            'returned' => Item::withStatus('returned')->count(),
            'claims' => Claim::count(),
            'members' => User::where('role', 'user')->count(),
        ];

        return view('index', compact('recent', 'stats'));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        ContactMessage::create($data);

        return back()->with('status', 'Thank you. Your message has been sent and we will reply by email.');
    }
}
