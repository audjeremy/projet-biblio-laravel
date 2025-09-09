<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
   
    public function index(Request $request): View
    {
        $query = Book::query();

        // Recherche sur titre / auteur / année
        if ($request->filled('q')) {
            $q = (string) $request->input('q');
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                   ->orWhere('author', 'like', "%{$q}%")
                   ->orWhere('year', 'like', "%{$q}%");
            });
        }

        // Tri du plus récent au plus ancien + pagination
        $books = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString(); // conserve ?q= dans la pagination

        return view('books.index', compact('books'));
    }

    /**
     * Formulaire de création
     */
    public function create(): View
    {
        return view('books.create');
    }

    /**
     * Enregistrement du livre
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'author'   => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'year'     => ['nullable', 'integer', 'min:0'],
            'summary'  => ['nullable', 'string'],
            'price'    => ['required', 'numeric', 'min:0'],
        ]);

        $book = Book::create($data);

        return redirect()
            ->route('books.show', $book)
            ->with('ok', 'Livre créé');
    }

    /**
     * Détails d’un livre
     */
    public function show(Book $book): View
    {
        return view('books.show', compact('book'));
    }


    public function edit(Book $book): View
    {
        return view('books.edit', compact('book'));
    }


    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'author'   => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'year'     => ['nullable', 'integer', 'min:0'],
            'summary'  => ['nullable', 'string'],
            'price'    => ['required', 'numeric', 'min:0'],
        ]);

        $book->update($data);

        return redirect()
            ->route('books.show', $book)
            ->with('ok', 'Livre mis à jour');
    }

    /**
     * Suppression
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('ok', 'Livre supprimé');
    }

    /**
     * Nouveautés = livres ajoutés dans les 10 derniers jours
     */
    public function news(): View
    {
        $since = now()->subDays(10);

        $books = Book::where('created_at', '>=', $since)
            ->orderByDesc('created_at')
            ->get();

        return view('books.news', compact('books', 'since'));
    }
}
