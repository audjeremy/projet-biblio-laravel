@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
  {{-- Header --}}
  <div class="mb-6">
    <h1 class="text-2xl font-semibold">{{ $book->title }}</h1>
    <p class="text-sm text-gray-500">
      <em>
        Créé le {{ $book->created_at->format('d/m/Y H:i') }}
        • Modifié le {{ $book->updated_at->format('d/m/Y H:i') }}
      </em>
    </p>
  </div>

  {{-- Carte d’info --}}
  <div class="bg-white rounded-lg shadow border border-gray-200 p-5">
    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-3">
      <div>
        <dt class="text-sm font-medium text-gray-600">Auteur</dt>
        <dd class="mt-1 text-gray-900">{{ $book->author }}</dd>
      </div>

      <div>
        <dt class="text-sm font-medium text-gray-600">Catégorie</dt>
        <dd class="mt-1">
          @if($book->category)
            <span class="inline-block px-2 py-0.5 text-xs rounded bg-orange-100 text-orange-700">
              {{ $book->category }}
            </span>
          @else
            —
          @endif
        </dd>
      </div>

      <div>
        <dt class="text-sm font-medium text-gray-600">Année</dt>
        <dd class="mt-1 text-gray-900">{{ $book->year ?? '—' }}</dd>
      </div>

      <div class="sm:col-span-2">
        <dt class="text-sm font-medium text-gray-600">Résumé</dt>
        <dd class="mt-1 text-gray-900">
          {{ $book->summary ?? '—' }}
        </dd>
      </div>

      <div>
        <dt class="text-sm font-medium text-gray-600">Prix</dt>
        <dd class="mt-1 text-blue-600 font-semibold">
          {{ number_format($book->price, 2, ',', ' ') }} $
        </dd>
      </div>
    </dl>

    {{-- Actions --}}
    <div class="mt-6 flex flex-wrap items-center gap-2">
      {{-- Retour (prend en compte si back=la même page) --}}
      <a href="{{ url()->previous() === url()->current() ? route('books.index') : url()->previous() }}"
         class="px-3 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
        ← Retour
      </a>

      @auth
        {{-- + Panier --}}
        <form method="POST" action="{{ route('cart.add', $book) }}">
          @csrf
          <input type="hidden" name="quantity" value="1">
          <button class="px-3 py-2 rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
            + Panier
          </button>
        </form>
      @endauth

      @can('update', $book)
        <a href="{{ route('books.edit', $book) }}"
           class="px-3 py-2 rounded-md border border-amber-500 text-amber-700 hover:bg-amber-50">
          ✎ Modifier
        </a>
      @endcan

      @can('delete', $book)
        <form method="POST" action="{{ route('books.destroy', $book) }}"
              onsubmit="return confirm('Supprimer définitivement « {{ $book->title }} » ?');">
          @csrf
          @method('DELETE')
          <button class="px-3 py-2 rounded-md bg-rose-600 text-white hover:bg-rose-700">
            Supprimer
          </button>
        </form>
      @endcan
    </div>
  </div>
</div>
@endsection
