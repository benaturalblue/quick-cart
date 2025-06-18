<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function store(Request $request)
{
    $request->validate(['message' => 'required|string|max:1000']);

    $message = ContactMessage::create([
        'user_id' => auth()->id(),
        'message' => $request->message,
    ]);

    return response()->json($message);
}

public function index()
{
    return ContactMessage::where('user_id', auth()->id())
        ->orderBy('created_at', 'asc')
        ->get();
}

}
