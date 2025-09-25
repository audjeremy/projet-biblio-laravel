@extends('layouts.app')
@section('title','Livres')

@section('content')
@php
  // Vue par défaut = "cards"
  $currentView = request('view', 'cards');

  // Construit proprement les URLs des filtres
  $allParams   = request()->except(['promo','page']);                    // retire promo + pagination
  $promoParams = array_merge(request()->except('page'), ['promo' => 1]); // garde le reste, force promo=1

  $allUrl   = url()->current() . (count($allParams) ? ('?'.http_build_query($allParams)) : '');
  $promoUrl = url()->current() . '?' . http_build_query($promoParams);

  $isPromo = request()->boolean('promo');

  // Helper "est nouveau"
  $isNew = function($createdAt, $days) {
    return $createdAt && \Carbon\Carbon::parse($createdAt)->greaterThanOrEqualTo(now()->subDays($days));
  };

  // fenêtre "Nouveau" en jours (fourni par le contrôleur sinon 10)
  $badgeDays = $badgeDays ?? 10;

  // Helper pour calculer le rabais (accepte 0–1 ou 0–100)
  $computePrice = function($price, $discountRaw) {
      $raw = (float) ($discountRaw ?? 0);
      $pct = ($raw > 0 && $raw <= 1) ? $raw * 100 : $raw;     // 0.10 => 10%
      $pct = max(0, min(100, $pct));                          // clamp
      $has = $pct > 0;
      $final = $has ? round($price * (1 - $pct/100), 2) : (float) $price;

      // Pour l’affichage du pourcentage sans .00 si entier
      $pctDisplay = fmod($pct,1) === 0.0 ? (int)$pct : round($pct,2);

      return [
        'has'        => $has,
        'pct'        => $pct,
        'pctDisplay' => $pctDisplay,
        'final'      => $final,
      ];
  };
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  {{-- Header --}}
  <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <div class="flex items-center gap-3">
      <h1 class="text-2xl font-semibold">Livres</h1>

      {{-- Filtres rapides: Toutes / Promotions --}}
      <div class="flex rounded-md overflow-hidden border border-gray-300">
        <a href="{{ $allUrl }}"
           class="px-3 py-1.5 text-sm {{ $isPromo ? 'bg-white text-gray-700 hover:bg-gray-50' : 'bg-blue-600 text-white' }}">
          Toutes
        </a>
        <a href="{{ $promoUrl }}"
           class="px-3 py-1.5 text-sm {{ $isPromo ? 'bg-rose-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
          Promotions
        </a>
      </div>
    </div>

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
  @foreach (['success','ok','error'] as $f)
    @if(session($f))
      <div class="mb-4 rounded-md border 
                  {{ $f==='error' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }} p-3">
        {{ session($f) }}
      </div>
    @endif
  @endforeach

{{-- Recherche & filtres avancés --}}
<form method="get" class="grid grid-cols-1 lg:grid-cols-12 gap-2 mb-6" role="search" aria-label="Recherche de livres" id="recherche">
  {{-- Recherche globale --}}
  <div class="lg:col-span-4">
    <input type="text" name="q" value="{{ request('q') }}"
           class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
           placeholder="Recherche (titre, auteur, résumé, catégorie, année)">
  </div>

  {{-- Auteur --}}
  <div class="lg:col-span-2">
    <input type="text" name="author" value="{{ request('author') }}"
           class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
           placeholder="Auteur">
  </div>

  {{-- Catégorie --}}
  <div class="lg:col-span-2">
    <input type="text" name="category" value="{{ request('category') }}"
           class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
           placeholder="Catégorie">
  </div>

  {{-- Année (exacte) --}}
  <div class="lg:col-span-1">
    <input type="number" name="year" value="{{ request('year') }}"
           class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
           placeholder="Année">
  </div>

  {{-- Prix min / max --}}
  <div class="lg:col-span-1">
    <input type="number" step="0.01" min="0" name="min" value="{{ request('min') }}"
           class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
           placeholder="Min $">
  </div>
  <div class="lg:col-span-1">
    <input type="number" step="0.01" min="0" name="max" value="{{ request('max') }}"
           class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200"
           placeholder="Max $">
  </div>

  {{-- Tri --}}
  <div class="lg:col-span-1">
    <select name="sort" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-200">
      @php $sort = request('sort','title_asc'); @endphp
      <option value="title_asc"  {{ $sort==='title_asc'  ? 'selected' : '' }}>Titre A→Z</option>
      <option value="title_desc" {{ $sort==='title_desc' ? 'selected' : '' }}>Titre Z→A</option>
      <option value="price_asc"  {{ $sort==='price_asc'  ? 'selected' : '' }}>Prix ↑</option>
      <option value="price_desc" {{ $sort==='price_desc' ? 'selected' : '' }}>Prix ↓</option>
      <option value="newest"     {{ $sort==='newest'     ? 'selected' : '' }}>Plus récents</option>
    </select>
  </div>

  {{-- Boutons --}}
  <div class="lg:col-span-12 flex flex-wrap gap-2">
    <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">Filtrer</button>

    {{-- Réinitialiser (conserve view & promo si tu veux les garder, sinon simple route('books.index')) --}}
    @php
      $resetParams = [];
      if (request()->has('view'))  $resetParams['view']  = request('view');
      if (request()->has('promo')) $resetParams['promo'] = request('promo');
      $resetUrl = $resetParams ? (route('books.index') . '?'. http_build_query($resetParams)) : route('books.index');
    @endphp
    <a href="{{ $resetUrl }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
      Réinitialiser
    </a>
  </div>

  {{-- Conserver l’affichage & le filtre promo --}}
  <input type="hidden" name="view" value="{{ request('view','cards') }}">
  @if(request()->boolean('promo'))
    <input type="hidden" name="promo" value="1">
  @endif
</form>

  {{-- Résultats --}}
  @if($books->isEmpty())
    <div class="p-4 rounded-md border border-blue-200 bg-blue-50 flex items-center justify-between">
      <span>
        Aucun livre{{ request('q') ? " pour « ".e(request('q'))." »" : '' }}{{ $isPromo ? ' en promotion' : '' }}.
      </span>
      @can('create', \App\Models\Book::class)
        <a class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
           href="{{ route('books.create') }}">
          Ajouter
        </a>
      @endcan
    </div>
  @else
    {{-- Vue LISTE --}}
    @if($currentView === 'list')
      <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 text-gray-700">
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
                $priceInfo = $computePrice($b->price, $b->discount ?? 0);
                $hasDiscount = $priceInfo['has'];
                $finalPrice  = $priceInfo['final'];
                $pctDisplay  = $priceInfo['pctDisplay'];
                $newFlag     = $isNew($b->created_at, $badgeDays);
              @endphp
              <tr>
                <td class="px-4 py-3">
                  <a href="{{ route('books.show',$b) }}" class="text-blue-600 hover:underline font-medium">
                    {{ $b->title }}
                  </a>
                  @if($newFlag)
                    <span class="ml-2 text-[11px] uppercase tracking-wide bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded">Nouveau</span>
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
                  @if($hasDiscount)
                    <div class="flex items-center gap-2 justify-end">
                      <span class="font-semibold text-blue-600">{{ number_format($finalPrice, 2, ',', ' ') }} $</span>
                      <span class="text-xs line-through text-gray-400">{{ number_format($b->price, 2, ',', ' ') }} $</span>
                      <span class="text-[11px] bg-rose-600 text-white px-1.5 py-0.5 rounded">-{{ $pctDisplay }}%</span>
                    </div>
                  @else
                    <span class="font-semibold text-blue-600">{{ number_format($b->price, 2, ',', ' ') }} $</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-right">
                  <div class="flex flex-wrap gap-2 justify-end">
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
                    {{-- Pas de Modifier/Supprimer ici (uniquement sur la page show) --}}
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      {{-- Vue CARTES (élargie : sm:2, lg:3, xl:4) --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($books as $b)
          @php
            $priceInfo = $computePrice($b->price, $b->discount ?? 0);
            $hasDiscount = $priceInfo['has'];
            $finalPrice  = $priceInfo['final'];
            $pctDisplay  = $priceInfo['pctDisplay'];
            $newFlag     = $isNew($b->created_at, $badgeDays);
          @endphp
          <div class="bg-white rounded-lg shadow p-4 flex flex-col border min-w-0">
            {{-- Couverture + badge NOUVEAU (identique à /news) --}}
            <div class="relative mb-3">
              @if($newFlag)
                <span class="absolute top-2 right-2 z-10 text-[11px] uppercase tracking-wide bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded shadow">
                  Nouveau
                </span>
              @endif
              <div class="flex items-center justify-center rounded-md border h-40 bg-gradient-to-br from-gray-100 to-gray-200 text-3xl font-bold text-gray-500">
                {{ \Illuminate\Support\Str::substr($b->title,0,1) }}
              </div>
            </div>

            <h5 class="text-lg font-semibold break-words">{{ $b->title }}</h5>
            <p class="text-sm text-gray-600">{{ $b->author }} @if($b->year) · {{ $b->year }}@endif</p>

            @if($b->category)
              <span class="mt-1 inline-block px-2 py-0.5 text-xs rounded bg-orange-100 text-orange-700">
                {{ $b->category }}
              </span>
            @endif

            <p class="flex-grow mt-2 text-sm text-gray-700">
              {{ \Illuminate\Support\Str::limit($b->summary, 120, '…') }}
            </p>

            <div class="mt-3 flex items-center justify-between gap-3">
              {{-- Prix (avec remise si applicable) --}}
              @if($hasDiscount)
                <div class="flex items-center gap-2">
                  <span class="font-semibold text-blue-600 whitespace-nowrap">{{ number_format($finalPrice, 2, ',', ' ') }} $</span>
                  <span class="text-xs line-through text-gray-400 whitespace-nowrap">{{ number_format($b->price, 2, ',', ' ') }} $</span>
                  <span class="text-[11px] bg-rose-600 text-white px-1.5 py-0.5 rounded whitespace-nowrap">-{{ $pctDisplay }}%</span>
                </div>
              @else
                <span class="font-semibold text-blue-600 whitespace-nowrap">
                  {{ number_format($b->price, 2, ',', ' ') }} $
                </span>
              @endif

              <div class="flex flex-wrap gap-2 justify-end">
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
                {{-- Pas de Modifier/Supprimer ici --}}
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Pagination --}}
    <div class="mt-6">
      {{ $books->appends(request()->except('page'))->links() }}
    </div>
  @endif
</div>

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
@endsection