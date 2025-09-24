<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    public function __construct()
    {
        // Lie automatiquement aux méthodes de BookPolicy :
        // index->viewAny, show->view, create/store->create, edit/update->update, destroy->delete
        $this->authorizeResource(Book::class, 'book');
    }

    /**
     * GET /books : liste avec recherche et filtres (+ pagination)
     */
    public function index(Request $request): View
    {
        $query = Book::query();

        // Recherche globale titre / auteur / année (?q=...)
        if ($request->filled('q')) {
            $q = (string) $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('year', 'like', "%{$q}%");
            });
        }

        // Filtres avancés facultatifs (UI viendra après)
        if ($request->filled('author')) {
            $query->where('author', 'like', '%'.$request->author.'%');
        }
        if ($request->filled('year')) {
            $query->where('year', (int) $request->year);
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%'.$request->category.'%');
        }
        if ($request->filled('min')) {
            $query->where('price', '>=', (float) $request->min);
        }
        if ($request->filled('max')) {
            $query->where('price', '<=', (float) $request->max);
        }

        // Tri par titre + pagination
        $books = $query
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        return view('books.index', compact('books'));
    }

    /**
     * GET /news : livres ajoutés récemment (30 derniers jours)
     */
    // app/Http/Controllers/BookController.php

public function news(): View
{
    $days = 10; // fenêtre "nouveau"
    $books = Book::where('created_at', '>=', now()->subDays($days))
        ->orderByDesc('created_at')
        ->paginate(12)
        ->withQueryString();

    return view('books.news', [
        'books' => $books,
        'days'  => $days,
    ]);
}

    public function create(): View
    {
        return view('books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'year'     => 'nullable|integer',
            'summary'  => 'nullable|string',
            'price'    => 'required|numeric|min:0',
        ]);

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès.');
    }

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
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'year'     => 'nullable|integer',
            'summary'  => 'nullable|string',
            'price'    => 'required|numeric|min:0',
        ]);

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Livre mis à jour avec succès.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès.');
    }
}