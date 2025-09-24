@extends('layouts.app')
@section('title','Nouveautés')

@section('content')
  <div class="mb-4">
    <h1 class="h3 mb-1">Nouveautés</h1>
    @isset($since)
      <p class="text-muted">Voici les livres ajoutés depuis le {{ $since->format('d/m/Y') }}.</p>
    @endisset
  </div>

  @if($books->isEmpty())
    <div class="alert alert-info">
      Aucun nouveau livre {{ isset($since) ? 'depuis le '.$since->format('d/m/Y') : 'récemment' }}.
    </div>
  @else
    <div class="d-flex flex-column gap-3">
      @foreach($books as $b)
        <div class="card shadow-soft">
          <div class="card-body d-flex flex-column flex-md-row gap-3 align-items-start">
            
            {{-- Zone “couverture” (placeholder si pas d’image) --}}
            <div class="flex-shrink-0" style="width:100px; height:140px; border-radius:12px; background:#f1f1f1; display:flex; align-items:center; justify-content:center; font-weight:bold; color:#888;">
              {{ Str::substr($b->title,0,1) }}
            </div>

            {{-- Infos principales --}}
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">{{ $b->title }}</h5>
                <span class="badge">Nouveau</span>
              </div>
              <div class="text-muted mb-2">
                {{ $b->author }}
                @if($b->year) · {{ $b->year }} @endif
              </div>
              @if($b->category)
                <div class="mb-2"><span class="badge">{{ $b->category }}</span></div>
              @endif
              <p class="mb-2">{{ Str::limit($b->summary,150,'…') }}</p>
              <div class="fw-bold text-primary">{{ number_format((float)$b->price,2,'.',' ') }} $</div>
            </div>

            {{-- Date et action --}}
            <div class="text-end text-muted small">
              <div>Ajouté le</div>
              <div>{{ $b->created_at?->format('d/m/Y') }}</div>
              <a href="{{ route('books.show',$b) }}" class="btn btn-sm btn-outline-secondary mt-2">Voir</a>
            </div>

          </div>
        </div>
      @endforeach
    </div>
  @endif
@endsection