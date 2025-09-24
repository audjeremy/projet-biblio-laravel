<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    /** GET /contact : afficher le formulaire (vue de ton coéquipier) */
    public function create(): View
    {
        return view('messages.create');
    }

    /** POST /contact : valider + enregistrer + flash + redirect back */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','max:255'],
            'subject' => ['nullable','string','max:255'],
            'message' => ['required','string','max:5000'],
        ]);

        Message::create($data + ['is_read' => false]); // garde son is_read=false

        return back()->with('ok', 'Message envoyé. Nous vous répondrons rapidement.');
    }

    /** GET /messages : liste paginée (préservée pour compat) */
    public function index(): View
    {
        $messages = Message::latest()->paginate(10);
        return view('messages.index', compact('messages'));
    }
}