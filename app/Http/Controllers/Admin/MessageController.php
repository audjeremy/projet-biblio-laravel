<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct()
    {
        // réservé admin
        $this->middleware(['auth', 'verified', 'role:admin']);
    }

    public function index(): View
    {
        // tri : non lus en premier, puis récents
        $messages = Message::orderBy('is_read')->latest()->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }

    public function markAsRead(Message $message): RedirectResponse
    {
        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }
        return back()->with('success', 'Message marqué comme lu.');
    }


    public function markAsUnread(Message $message)
    {
        $message->update(['is_read' => false]);

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'Message marqué comme non lu.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();
        return back()->with('success', 'Message supprimé.');
    }
}
