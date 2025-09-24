@extends('layouts.app')
@section('title','Nouveautés')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Nouveautés</h1>
    <a href="{{ route('books.index') }}" class="text-sm text-blue-600 hover:underline">← Tous les livres</a>
  </div>

  @if($books->isEmpty())
    <div class="rounded-md border border-blue-200 bg-blue-50 p-4">
      Aucun livre ajouté dans les {{ $days }} derniers jours.
    </div>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($books as $b)
        <div class="bg-white rounded-lg shadow p-4 flex flex-col">
          <div class="mb-3 flex items-center justify-center rounded-md border h-40 bg-gradient-to-br from-gray-100 to-gray-200 text-3xl font-bold text-gray-500 relative">
            {{ \Illuminate\Support\Str::substr($b->title,0,1) }}
            <span class="absolute -top-2 -right-2 text-xs bg-emerald-500 text-white px-2 py-0.5 rounded-full shadow">
              Nouveau
            </span>
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
              <a href="{{ route('books.show',$b) }}" class="px-2 py-1 text-xs border border-blue-500 text-blue-600 rounded hover:bg-blue-50">Voir</a>
              @auth
              <form method="POST" action="{{ route('cart.add',$b) }}">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button class="px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                  + Panier
                </button>
              </form>
              @endauth
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $books->links() }}
    </div>
  @endif
</div>
@endsection