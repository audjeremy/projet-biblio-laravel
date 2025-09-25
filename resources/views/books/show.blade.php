@extends('layouts.app')
@section('title', $book->title)

@section('content')
@php
  // === Helpers et calculs pour l'affichage du livre ===
  // Helper pour savoir si le livre est "nouveau"
  $isNew = function($createdAt, $days = 10) {
    return $createdAt && \Carbon\Carbon::parse($createdAt)->greaterThanOrEqualTo(now()->subDays($days));
  };

  // Calcul du rabais éventuel (accepte 0–1 ou 0–100)
  $raw = (float) ($book->discount ?? 0);
  $pct = ($raw > 0 && $raw <= 1) ? $raw * 100 : $raw;
  $pct = max(0, min(100, $pct));
  $hasDiscount = $pct > 0;
  $pctDisplay  = fmod($pct,1) === 0.0 ? (int)$pct : round($pct,2);
  $finalPrice  = $hasDiscount ? round((float)$book->price * (1 - $pct/100), 2) : (float)$book->price;

  $newFlag = $isNew($book->created_at, 10);
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
  {{-- === Fil d’ariane simple === --}}
  <div class="text-sm mb-4">
    <a class="text-blue-600 hover:underline" href="{{ route('books.index') }}">← Retour à tous les livres</a>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
    {{-- === Couverture + badge "Nouveau" (identique à /news) === --}}
    <div class="md:col-span-2">
      <div class="relative mb-3">
        @if($newFlag)
          <span class="absolute top-2 right-2 z-10 text-[11px] uppercase tracking-wide bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded shadow">
            Nouveau
          </span>
        @endif
        <div class="flex items-center justify-center rounded-md border h-64 sm:h-72 bg-gradient-to-br from-gray-100 to-gray-200 text-6xl font-bold text-gray-500">
          {{ \Illuminate\Support\Str::substr($book->title,0,1) }}
        </div>
      </div>
    </div>

    {{-- === Infos principales du livre === --}}
    <div class="md:col-span-3">
      <h1 class="text-2xl font-semibold">{{ $book->title }}</h1>
      <p class="text-gray-700 mt-1">
        {{ $book->author }}@if($book->year) · {{ $book->year }}@endif
      </p>

      @if($book->category)
        <div class="mt-2">
          <span class="inline-block px-2 py-0.5 text-xs rounded bg-orange-100 text-orange-700">
            {{ $book->category }}
          </span>
        </div>
      @endif

      <div class="mt-4 text-gray-800 leading-relaxed">
        {{ $book->summary ?: '—' }}
      </div>

      {{-- Prix + remise --}}
      <div class="mt-6">
        @if($hasDiscount)
          <div class="flex items-center gap-3">
            <span class="text-2xl font-semibold text-blue-600">{{ number_format($finalPrice, 2, ',', ' ') }} $</span>
            <span class="text-sm line-through text-gray-400">{{ number_format($book->price, 2, ',', ' ') }} $</span>
            <span class="text-xs bg-rose-600 text-white px-2 py-1 rounded">-{{ $pctDisplay }}%</span>
          </div>
        @else
          <div class="text-2xl font-semibold text-blue-600">
            {{ number_format($book->price, 2, ',', ' ') }} $
          </div>
        @endif
      </div>

      {{-- Actions --}}
      <div class="mt-6 flex flex-wrap gap-3">
        @auth
        <form method="POST" action="{{ route('cart.add',$book) }}">
          @csrf
          <input type="hidden" name="quantity" value="1">
          <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">
            + Ajouter au panier
          </button>
        </form>
        @endauth

        {{-- Admin: Modifier / Supprimer (seulement sur la page détail) --}}
        @can('update', $book)
          <a href="{{ route('books.edit',$book) }}"
             class="px-4 py-2 border border-amber-500 text-amber-700 rounded-md hover:bg-amber-50">
            Modifier
          </a>
        @endcan
        @can('delete', $book)
          <form method="POST" action="{{ route('books.destroy',$book) }}"
                onsubmit="return confirm('Supprimer ce livre ?')">
            @csrf @method('DELETE')
            <button class="px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-700">
              Supprimer
            </button>
          </form>
        @endcan
      </div>

      {{-- Meta --}}
      <div class="mt-6 text-xs text-gray-500">
        Ajouté le {{ optional($book->created_at)->format('d/m/Y H:i') ?? '—' }},
        modifié le {{ optional($book->updated_at)->format('d/m/Y H:i') ?? '—' }}
      </div>
    </div>
  </div>
</div>
@endsection