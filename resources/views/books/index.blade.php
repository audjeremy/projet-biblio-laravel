{{-- resources/views/books/index.blade.php --}}
@extends('layouts.app')
@section('title','Livres')

@section('content')
@php
  // Vue par défaut = "cards" (comme ta première version)
  $currentView = request('view', 'cards');
  $badgeDays   = $badgeDays ?? 10; // fenêtre "Nouveau"
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  {{-- Header + Toggle --}}
  <div class="flex flex-wrap justify-between items-center gap-2 mb-6">
    <h1 class="text-2xl font-semibold">Livres</h1>

    <div class="flex items-center gap-2">
      {{-- Toggle Liste / Cartes --}}
      <div class="flex rounded-md shadow-sm" role="group" aria-label="Affichage">
        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
           class="px-3 py-1.5 border {{ $currentView === 'list' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
           Liste
        </a>
        <a href="{{ request()->fullUrlWithQuery(['view' => 'cards']) }}"
           class="px-3 py-1.5 border-t border-b {{ $currentView === 'cards' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
           Cartes
        </a>
      </div>
    {{-- Filtres rapides --}}
<div class="flex items-center gap-2">
  @php
    // Construit l’URL "Toutes" = URL actuelle sans le param promo
    $allUrl = request()->fullUrlWithQuery(collect(request()->query())->except('promo')->toArray());
    // Construit l’URL "Promotions" = URL actuelle avec promo=1
    $promoUrl = request()->fullUrlWithQuery(array_merge(request()->query(), ['promo' => 1]));
    $isPromo = request()->boolean('promo');
  @endphp

  <a href="{{ $allUrl }}"
     class="px-3 py-1.5 rounded-md border text-sm
            {{ $isPromo ? 'border-gray-300 text-gray-700 hover:bg-gray-50' : 'bg-blue-600 text-white border-blue-600' }}">
    Toutes
  </a>

  <a href="{{ $promoUrl }}"
     class="px-3 py-1.5 rounded-md border text-sm
            {{ $isPromo ? 'bg-rose-600 text-white border-rose-600' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}">
    Promotions
  </a>
</div>
      {{-- Bouton ajouter (admin seulement) --}}
      @can('create', \App\Models\Book::class)
        <a href="{{ route('books.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
           Ajouter un livre
        </a>
      @endcan
    </div>
  </div>

  {{-- Flash message --}}
  @if(session('success'))
    <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-emerald-700">
      {{ session('success') }}
    </div>
  @endif

  {{-- Recherche --}}
<form method="get" class="grid grid-cols-1 sm:grid-cols-12 gap-2 mb-6" role="search" aria-label="Recherche de livres">
  <div class="sm:col-span-3">
    <input type="text" name="q" value="{{ request('q') }}"
      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
      placeholder="Recherche globale (titre, auteur, année)">
  </div>
  <div class="sm:col-span-2">
    <input type="text" name="author" value="{{ request('author') }}"
      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
      placeholder="Auteur">
  </div>
  <div class="sm:col-span-2">
    <input type="text" name="category" value="{{ request('category') }}"
      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
      placeholder="Catégorie">
  </div>
  <div class="sm:col-span-1">
    <input type="number" name="year" value="{{ request('year') }}"
      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
      placeholder="Année">
  </div>
  <div class="sm:col-span-2">
    <input type="number" step="0.01" name="min" value="{{ request('min') }}"
      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
      placeholder="Prix min">
  </div>
  <div class="sm:col-span-2">
    <input type="number" step="0.01" name="max" value="{{ request('max') }}"
      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
      placeholder="Prix max">
  </div>

  {{-- Conserver la vue choisie (list/cards) --}}
  <input type="hidden" name="view" value="{{ $currentView ?? request('view', 'cards') }}">

  <div class="sm:col-span-12 flex gap-2">
    <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Rechercher</button>
    <a href="{{ route('books.index', ['view' => $currentView ?? 'cards']) }}"
       class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
      Réinitialiser
    </a>
  </div>
</form>

  {{-- Résultats --}}
  @if($books->isEmpty())
    <div class="p-4 rounded-md border border-blue-200 bg-blue-50 flex items-center justify-between">
      <span>
        Aucun livre{{ request('q') ? " pour « ".e(request('q'))." »" : '' }}.
      </span>
      @can('create', \App\Models\Book::class)
        <a class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
           href="{{ route('books.create') }}">
           Ajouter
        </a>
      @endcan
    </div>
  @else
    {{-- ===== Vue LISTE ===== --}}
    @if($currentView === 'list')
      <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="px-4 py-2 text-left">Titre</th>
              <th class="px-4 py-2 text-left">Auteur</th>
              <th class="px-4 py-2 text-left hidden md:table-cell">Catégorie</th>
              <th class="px-4 py-2 text-left">Année</th>
              <th class="px-4 py-2 text-right hidden md:table-cell">Prix</th>
              <th class="px-4 py-2 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach($books as $b)
              @php
                $isNew = $b->created_at && $b->created_at->gte(now()->subDays($badgeDays));
              @endphp
              <tr>
                <td class="px-4 py-3">
                  <a href="{{ route('books.show',$b) }}" class="text-blue-600 hover:underline">
                    {{ $b->title }}
                  </a>
                  @if($isNew)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                      Nouveau
                    </span>
                  @endif
                </td>
                <td class="px-4 py-3">{{ $b->author }}</td>
                <td class="px-4 py-3 hidden md:table-cell">
                  @if($b->category)
                    <span class="inline-block px-2 py-0.5 text-xs rounded bg-orange-100 text-orange-700">
                      {{ $b->category }}
                    </span>
                  @else
                    —
                  @endif
                </td>
                <td class="px-4 py-3">{{ $b->year ?? '—' }}</td>
                <td class="px-4 py-3 text-right hidden md:table-cell">
                  {{ number_format((float)$b->price, 2, ',', ' ') }} $
                </td>
                <td class="px-4 py-3 text-right">
                  <div class="flex gap-2 justify-end">
                    <a href="{{ route('books.show',$b) }}"
                       class="px-2 py-1 text-xs border border-blue-500 text-blue-600 rounded hover:bg-blue-50">
                       Voir
                    </a>

                    @auth
                      <form method="POST" action="{{ route('cart.add',$b) }}">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button class="px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                          + Panier
                        </button>
                      </form>
                    @endauth

                    @can('update', $b)
                      <a href="{{ route('books.edit',$b) }}"
                         class="px-2 py-1 text-xs border border-amber-500 text-amber-600 rounded hover:bg-amber-50">
                         Modifier
                      </a>
                    @endcan

                    @can('delete', $b)
                      <form method="POST" action="{{ route('books.destroy',$b) }}"
                            onsubmit="return confirm('Supprimer ce livre ?')">
                        @csrf @method('DELETE')
                        <button class="px-2 py-1 text-xs bg-rose-600 text-white rounded hover:bg-rose-700">
                          Supprimer
                        </button>
                      </form>
                    @endcan
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    {{-- ===== Vue CARTES ===== --}}
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($books as $b)
          @php
            $isNew = $b->created_at && $b->created_at->gte(now()->subDays($badgeDays));
          @endphp
          <div class="bg-white rounded-lg shadow p-4 flex flex-col">
            {{-- Placeholder couverture --}}
            <div class="mb-3 flex items-center justify-center rounded-md border h-40 bg-gradient-to-br from-gray-100 to-gray-200 text-3xl font-bold text-gray-500 relative">
              {{ \Illuminate\Support\Str::substr($b->title,0,1) }}
              @if($isNew)
                <span class="absolute -top-2 -right-2 text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full shadow">
                  Nouveau
                </span>
              @endif
            </div>

            <h5 class="text-lg font-semibold">{{ $b->title }}</h5>
            <p class="text-sm text-gray-600">{{ $b->author }} @if($b->year) · {{ $b->year }}@endif</p>

            @if($b->category)
              <span class="mt-1 inline-block px-2 py-0.5 text-xs rounded bg-orange-100 text-orange-700">
                {{ $b->category }}
              </span>
            @endif

            <p class="flex-grow mt-2 text-sm text-gray-700">
              {{ \Illuminate\Support\Str::limit($b->summary, 120, '…') }}
            </p>

            <div class="mt-3 flex items-center justify-between">
              <span class="font-semibold text-blue-600">
                {{ number_format((float)$b->price, 2, ',', ' ') }} $
              </span>
              <div class="flex gap-2">
                <a href="{{ route('books.show',$b) }}"
                   class="px-2 py-1 text-xs border border-blue-500 text-blue-600 rounded hover:bg-blue-50">
                   Voir
                </a>

                @auth
                  <form method="POST" action="{{ route('cart.add',$b) }}">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button class="px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                      + Panier
                    </button>
                  </form>
                @endauth

                @can('update', $b)
                  <a href="{{ route('books.edit',$b) }}"
                     class="px-2 py-1 text-xs border border-amber-500 text-amber-600 rounded hover:bg-amber-50">
                     Modifier
                  </a>
                @endcan

                @can('delete', $b)
                  <form method="POST" action="{{ route('books.destroy',$b) }}"
                        onsubmit="return confirm('Supprimer ce livre ?')">
                    @csrf @method('DELETE')
                    <button class="px-2 py-1 text-xs bg-rose-600 text-white rounded hover:bg-rose-700">
                      Supprimer
                    </button>
                  </form>
                @endcan
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Pagination : conserve la vue + la recherche --}}
    <div class="mt-6">
      {{ $books->appends(['view' => $currentView, 'q' => request('q')])->links() }}
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  (function() {
    // Si l'URL n'a pas de ?view=..., appliquer le dernier choix mémorisé
    const url = new URL(window.location.href);
    if (!url.searchParams.get('view')) {
      const saved = localStorage.getItem('books_view');
      if (saved && (saved === 'list' || saved === 'cards')) {
        url.searchParams.set('view', saved);
        window.location.replace(url.toString());
        return;
      }
    }
    // Mémoriser le choix quand on clique sur un bouton de toggle
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[href*="view="]');
      if (!a) return;
      const u = new URL(a.href);
      const v = u.searchParams.get('view');
      if (v) localStorage.setItem('books_view', v);
    });
  })();
</script>
@endpush