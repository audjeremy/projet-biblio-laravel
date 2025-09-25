<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct()
    {
        // Lie aux policies (index->viewAny, show->view, create/store->create, etc.)
        $this->authorizeResource(Book::class, 'book');
    }

    /**
     * GET /books : liste avec recherche + filtres + pagination
     */
    public function index(Request $request): View
    {
        $query = Book::query();

        // --- Filtre "Promotions" (rabais > 0) ---
        if ($request->boolean('promo')) {
            $query->where('discount', '>', 0);
        }

        // --- Recherche globale (?q=...) : multi-termes sur titre/auteur/summary/catégorie/année ---
        if ($request->filled('q')) {
            $terms = preg_split('/\s+/', (string) $request->q, -1, PREG_SPLIT_NO_EMPTY);
            $query->where(function ($outer) use ($terms) {
                foreach ($terms as $t) {
                    $like = "%{$t}%";
                    $outer->where(function ($inner) use ($like, $t) {
                        $inner->orWhere('title', 'like', $like)
                              ->orWhere('author', 'like', $like)
                              ->orWhere('summary', 'like', $like)
                              ->orWhere('category', 'like', $like)
                              ->orWhere('year', 'like', $t); // laisse l’année en string pour couvrir "194"
                    });
                }
            });
        }

        // --- Filtres avancés ---
        if ($request->filled('author')) {
            $query->where('author', 'like', '%'.trim((string)$request->author).'%');
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%'.trim((string)$request->category).'%');
        }
        if ($request->filled('year')) {
            $year = (int) $request->year;
            if ($year > 0) {
                $query->where('year', $year);
            }
        }
        if ($request->filled('min')) {
            $min = (float) str_replace(',', '.', (string) $request->min);
            $query->where('price', '>=', $min);
        }
        if ($request->filled('max')) {
            $max = (float) str_replace(',', '.', (string) $request->max);
            $query->where('price', '<=', $max);
        }

        // --- Tri (optionnel) ---
        $sort = (string) $request->get('sort', 'title_asc');
        switch ($sort) {
            case 'price_asc':  $query->orderBy('price', 'asc');  break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'newest':     $query->orderBy('created_at', 'desc'); break;
            case 'title_desc': $query->orderBy('title', 'desc'); break;
            case 'title_asc':
            default:           $query->orderBy('title', 'asc');  break;
        }

        $books = $query->paginate(12)->withQueryString();

        // fenêtre "Nouveau"
        $badgeDays = 10;

        return view('books.index', compact('books', 'badgeDays'));
    }

    /**
     * GET /news : livres ajoutés récemment (10 jours par défaut)
     */
    public function news(): View
    {
        $days = 10;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'year'     => 'nullable|integer',
            'summary'  => 'nullable|string',
            'price'    => 'required|numeric|min:0',
            // remise en pourcentage (0–100) optionnelle
            'discount' => 'nullable|numeric|min:0|max:100',
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

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'author'   => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'year'     => 'nullable|integer',
            'summary'  => 'nullable|string',
            'price'    => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Livre mis à jour avec succès.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès.');
    }
}