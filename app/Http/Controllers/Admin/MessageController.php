<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Contracts\View\View;

class MessageController extends Controller
{
    // Who messaged whom and when. The view only prints the length of each message, never the text.
    public function index(): View
    {
        $messages = Message::with(['sender', 'recipient', 'item'])->latest()->paginate(25);

        return view('admin.messages', compact('messages'));
    }
}
