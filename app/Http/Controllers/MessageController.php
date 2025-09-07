<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    /** GET /contact : afficher le formulaire */
    public function create(): View
    {
        return view('contact');
    }

    /** POST /contact : valider + enregistrer + flash + redirect */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'email'   => ['required','email','max:255'],
            'subject' => ['nullable','string','max:255'],
            'message' => ['required','string','min:5'],
        ]);

        Message::create($data);

        return redirect()->route('contact')->with('ok', 'Merci ! Votre message a bien été envoyé.');
    }

    /** GET /messages : liste paginée des messages */
    public function index(): View
    {
        $messages = Message::latest()->paginate(10);
        return view('messages.index', compact('messages'));
    }
}
