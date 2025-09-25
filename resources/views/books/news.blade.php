@extends('layouts.app')
@section('title','Nouveautés')

@section('content')
@php
  $days = $days ?? 10;
  $isNew = function($createdAt, $days) {
    return $createdAt && \Carbon\Carbon::parse($createdAt)->greaterThanOrEqualTo(now()->subDays($days));
  };

  // Helper pour calculer le rabais de manière robuste
  $computePrice = function($price, $discountRaw) {
      $raw = (float) ($discountRaw ?? 0);
      $pct = ($raw > 0 && $raw <= 1) ? $raw * 100 : $raw;     // 0.10 => 10%
      $pct = max(0, min(100, $pct));                          // clamp
      $has = $pct > 0;
      $final = $has ? round($price * (1 - $pct/100), 2) : (float) $price;

      $pctDisplay = fmod($pct,1) === 0.0 ? (int)$pct : round($pct,2);

      return [
        'has'        => $has,
        'pctDisplay' => $pctDisplay,
        'final'      => $final,
      ];
  };
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Nouveautés ({{ $days }} jours)</h1>
    <a href="{{ route('books.index') }}" class="text-sm text-blue-600 hover:underline">← Retour à tous les livres</a>
  </div>

  @if($books->isEmpty())
    <div class="p-4 rounded-md border border-blue-200 bg-blue-50">
      Aucun nouveau livre récemment.
    </div>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($books as $b)
        @php
          $priceInfo = $computePrice($b->price, $b->discount ?? 0);
          $hasDiscount = $priceInfo['has'];
          $finalPrice  = $priceInfo['final'];
          $pctDisplay  = $priceInfo['pctDisplay'];
          $newFlag = $isNew($b->created_at, $days);
        @endphp

        <div class="bg-white rounded-lg shadow p-4 flex flex-col relative">
          {{-- Badge NOUVEAU sur l’image --}}
          <div class="relative mb-3">
            @if($newFlag)
              <span class="absolute top-2 right-2 z-10 text-[11px] uppercase tracking-wide bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded shadow">
                Nouveau
              </span>
            @endif

            <div class="flex items-center justify-center rounded-md border h-44 sm:h-48 bg-gradient-to-br from-gray-100 to-gray-200 text-3xl font-bold text-gray-500">
              {{ \Illuminate\Support\Str::substr($b->title,0,1) }}
            </div>
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
            {{-- Prix (avec remise si applicable) --}}
            @if($hasDiscount)
              <div class="flex items-center gap-2">
                <span class="font-semibold text-blue-600 whitespace-nowrap">{{ number_format($finalPrice, 2, ',', ' ') }} $</span>
                <span class="text-xs line-through text-gray-400 whitespace-nowrap">{{ number_format($b->price, 2, ',', ' ') }} $</span>
                <span class="text-[11px] bg-rose-600 text-white px-1.5 py-0.5 rounded whitespace-nowrap">-{{ $pctDisplay }}%</span>
              </div>
            @else
              <span class="font-semibold text-blue-600">
                {{ number_format($b->price, 2, ',', ' ') }} $
              </span>
            @endif

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
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-6">
      {{ $books->appends(request()->except('page'))->links() }}
    </div>
  @endif
</div>
@endsection