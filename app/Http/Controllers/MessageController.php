<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function create(): View
    {
        return view('messages.create'); // la vue ci-dessus
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','max:255'],
            'subject' => ['nullable','string','max:255'],
            'message' => ['required','string','max:5000'],
        ]);

        Message::create($data + ['is_read' => false]); // is_read si ta colonne existe

        return back()->with('ok', 'Message envoyé. Nous vous répondrons rapidement.');
    }
}
