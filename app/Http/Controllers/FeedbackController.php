<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // Store feedback
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Feedback sent successfully!');
    }

    // Optional: show feedbacks for support/admin
    public function index()
    {
        $feedbacks = Feedback::with('user')->latest()->get();
        return view('feedback.index', compact('feedbacks'));
    }
}

