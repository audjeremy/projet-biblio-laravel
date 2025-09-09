@extends('layouts.app')
@section('title','Livres')

@section('content')
  @php
    // Vue par défaut = "cards"
    $currentView = request('view', 'cards');
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1 class="h3 mb-0">Livres</h1>
    <div class="d-flex align-items-center gap-2">
      {{-- Toggle Liste / Cards --}}
      <div class="btn-group me-2" role="group" aria-label="Affichage">
        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
           class="btn btn-outline-secondary {{ $currentView === 'list' ? 'active' : '' }}">
          Liste
        </a>
        <a href="{{ request()->fullUrlWithQuery(['view' => 'cards']) }}"
           class="btn btn-outline-secondary {{ $currentView === 'cards' ? 'active' : '' }}">
          Cartes
        </a>
      </div>

      <a href="{{ route('books.create') }}" class="btn btn-primary">
        Ajouter un livre
      </a>
    </div>
  </div>

  {{-- Recherche --}}
  <form method="get" class="row g-2 mb-3" role="search" aria-label="Recherche de livres">
    <div class="col-sm-9">
      <input
        name="q"
        value="{{ request('q') }}"
        class="form-control"
        placeholder="Recherche (titre, auteur, année)"
        aria-label="Rechercher par titre, auteur ou année">
    </div>
    <div class="col-sm-3 d-grid">
      <button class="btn btn-outline-secondary">Rechercher</button>
    </div>
    {{-- Conserver la vue choisie quand on soumet la recherche --}}
    <input type="hidden" name="view" value="{{ $currentView }}">
  </form>

  @if($books->isEmpty())
    <div class="alert alert-info d-flex align-items-center justify-content-between">
      <div>
        Aucun livre{{ request('q') ? " pour « ".e(request('q'))." »" : '' }}.
      </div>
      <a class="btn btn-sm btn-primary" href="{{ route('books.create') }}">Ajouter</a>
    </div>
  @else
    @if($currentView === 'list')
      {{-- ===== Vue LISTE (table) ===== --}}
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Titre</th>
              <th>Auteur</th>
              <th class="d-none d-md-table-cell">Catégorie</th>
              <th>Année</th>
              <th class="d-none d-md-table-cell text-end">Prix</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($books as $b)
            <tr>
              <td>
                <a href="{{ route('books.show',$b) }}" class="text-decoration-none">
                  {{ $b->title }}
                </a>
              </td>
              <td>{{ $b->author }}</td>
              <td class="d-none d-md-table-cell">
                @if($b->category)
                  <span class="badge">{{ $b->category }}</span>
                @else
                  —
                @endif
              </td>
              <td>{{ $b->year ?? '—' }}</td>
              <td class="d-none d-md-table-cell text-end">
                {{ number_format((float)$b->price, 2, '.', ' ') }} $
              </td>
              <td class="text-end">
                <div class="btn-group">
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('books.show',$b) }}">Voir</a>
                  <form method="post" action="{{ route('books.destroy',$b) }}" class="d-inline"
                        onsubmit="return confirm('Supprimer ce livre ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @else
      {{-- ===== Vue CARDS (vignettes visuelles) ===== --}}
      <div class="row g-4">
        @foreach($books as $b)
          <div class="col-sm-6 col-lg-3">
            <div class="card h-100 shadow-soft">
              <div class="card-body d-flex flex-column">
                {{-- Cover / placeholder arrondi en haut --}}
                <div class="mb-3"
                     style="
                       width:100%;
                       height:160px;
                       border-radius:14px;
                       background: linear-gradient(135deg,#efe7de,#f7f1ea);
                       border:1px solid #eadfD5;
                       display:flex; align-items:center; justify-content:center;
                       color:#9A7F67; font-weight:800; font-size:1.5rem;">
                  {{ \Illuminate\Support\Str::substr($b->title,0,1) }}
                </div>

                {{-- Titre & méta --}}
                <h5 class="card-title mb-1">{{ $b->title }}</h5>
                <div class="text-muted mb-2">
                  {{ $b->author }}@if($b->year) · {{ $b->year }}@endif
                </div>

                {{-- Catégorie / tags --}}
                @if($b->category)
                  <div class="mb-2">
                    <span class="badge">{{ $b->category }}</span>
                  </div>
                @endif

                {{-- Résumé tronqué pour homogénéiser la hauteur --}}
                <p class="card-text flex-grow-1">
                  {{ \Illuminate\Support\Str::limit($b->summary, 120, '…') }}
                </p>

                {{-- Prix + actions --}}
                <div class="mt-2 d-flex justify-content-between align-items-center">
                  <div class="fw-bold text-primary">
                    {{ number_format((float)$b->price, 2, '.', ' ') }} $
                  </div>
                  <div class="d-flex gap-2">
                    <a href="{{ route('books.show',$b) }}" class="btn btn-sm btn-outline-secondary">Voir</a>
                    <form method="post" action="{{ route('books.destroy',$b) }}"
                          onsubmit="return confirm('Supprimer ce livre ?')" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                  </div>
                </div>

              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Pagination : conserve la vue + la recherche --}}
    <div class="mt-4">
      {{ $books->appends(['view' => $currentView, 'q' => request('q')])->links('pagination::bootstrap-5') }}
    </div>
  @endif
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