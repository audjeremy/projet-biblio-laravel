@extends('layouts.app')
@section('title','Mon panier')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl font-semibold mb-4">Mon panier</h1>

  {{-- Flash messages --}}
  @if(session('success'))
    <div class="mb-4 p-3 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-4 p-3 rounded-md bg-rose-50 text-rose-700 border border-rose-200">
      {{ session('error') }}
    </div>
  @endif

  @php
    // Filet de sécurité si le contrôleur n’a pas passé $totals
    $totals = $totals ?? [
      'currency' => config('cart.currency', '$'),
      'subtotal' => (float) ($cart->items->sum(fn($i) => $i->quantity * $i->price) ?? 0),
      'discount' => 0.0,
      'gst'      => 0.0,
      'qst'      => 0.0,
      'shipping' => (float) config('cart.shipping_flat', 0),
      'total'    => (float) ($cart->items->sum(fn($i) => $i->quantity * $i->price) ?? 0),
      'coupon'   => session('coupon'),
    ];
  @endphp

  @if($cart->items->isEmpty())
    <div class="p-4 rounded-md bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-between">
      <span>Votre panier est vide.</span>
      <a href="{{ route('books.index') }}"
         class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        ← Continuer mes achats
      </a>
    </div>
  @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {{-- Colonne produits --}}
      <div class="lg:col-span-2">
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-gray-700">
              <tr>
                <th class="px-4 py-2 text-left font-medium">Livre</th>
                <th class="px-4 py-2 text-right font-medium">Prix</th>
                <th class="px-4 py-2 font-medium w-44">Quantité</th>
                <th class="px-4 py-2 text-right font-medium">Sous-total</th>
                <th class="px-4 py-2"></th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @foreach($cart->items as $item)
              <tr class="bg-white">
                <td class="px-4 py-3">
                  <div class="font-medium text-gray-900">{{ $item->book->title }}</div>
                  <div class="text-gray-500 text-xs">{{ $item->book->author }}</div>
                </td>
                <td class="px-4 py-3 text-right">
                  {{ number_format($item->price,2,',',' ') }} {{ config('cart.currency', '$') }}
                </td>
                <td class="px-4 py-3">
                  <form method="POST" action="{{ route('cart.update',$item) }}" class="flex items-center gap-2">
                    @csrf
                    <input type="number" name="quantity" min="1"
                           value="{{ $item->quantity }}"
                           class="w-24 px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200">
                    <button class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                      Mettre à jour
                    </button>
                  </form>
                </td>
                <td class="px-4 py-3 text-right">
                  {{ number_format($item->quantity * $item->price,2,',',' ') }} {{ config('cart.currency', '$') }}
                </td>
                <td class="px-4 py-3 text-right">
                  <form method="POST" action="{{ route('cart.remove',$item) }}">
                    @csrf @method('DELETE')
                    <button class="px-2 py-1 text-xs bg-rose-600 text-white rounded-md hover:bg-rose-700 transition" title="Retirer">
                      ✕
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <a href="{{ route('books.index') }}"
           class="inline-block mt-4 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
          ← Continuer mes achats
        </a>
      </div>

      {{-- Colonne récap / coupon --}}
      <div class="space-y-4">
        {{-- Code promo --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
          <h2 class="text-base font-semibold mb-3">Code promo</h2>

          @if($totals['coupon'])
            <div class="flex items-center justify-between mb-2">
              <div>
                <span class="inline-block px-2 py-0.5 text-xs rounded bg-emerald-100 text-emerald-700">
                  {{ $totals['coupon']['code'] }}
                </span>
                @if(!empty($totals['coupon']['label']))
                  <span class="ml-2 text-gray-500 text-xs">{{ $totals['coupon']['label'] }}</span>
                @endif
              </div>
              <form method="POST" action="{{ route('cart.coupon.remove') }}">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 text-xs bg-rose-600 text-white rounded-md hover:bg-rose-700 transition">
                  Retirer
                </button>
              </form>
            </div>
          @else
            <form method="POST" action="{{ route('cart.coupon.apply') }}" class="flex items-center gap-2">
              @csrf
              <input type="text" name="code" placeholder="Ex: PROMO10"
                     class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200" required>
              <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                Appliquer
              </button>
            </form>
            <p class="mt-2 text-xs text-gray-500">Exemples de test : <code>PROMO10</code>, <code>STUDENT5</code></p>
          @endif
        </div>

        {{-- Récapitulatif --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
          <h2 class="text-base font-semibold mb-3">Récapitulatif</h2>

          <div class="flex items-center justify-between py-1">
            <span>Sous-total</span>
            <span>{{ number_format($totals['subtotal'],2,',',' ') }} {{ $totals['currency'] }}</span>
          </div>

          @if($totals['discount'] > 0)
            <div class="flex items-center justify-between py-1 text-emerald-700">
              <span>Remise</span>
              <span>- {{ number_format($totals['discount'],2,',',' ') }} {{ $totals['currency'] }}</span>
            </div>
          @endif

          <div class="flex items-center justify-between py-1">
            <span>TPS (5%)</span>
            <span>{{ number_format($totals['gst'],2,',',' ') }} {{ $totals['currency'] }}</span>
          </div>
          <div class="flex items-center justify-between py-1">
            <span>TVQ (9.975%)</span>
            <span>{{ number_format($totals['qst'],2,',',' ') }} {{ $totals['currency'] }}</span>
          </div>

          @if(($totals['shipping'] ?? 0) > 0)
            <div class="flex items-center justify-between py-1">
              <span>Livraison</span>
              <span>{{ number_format($totals['shipping'],2,',',' ') }} {{ $totals['currency'] }}</span>
            </div>
          @endif

          <hr class="my-3">
          <div class="flex items-center justify-between font-semibold text-lg">
            <span>Total</span>
            <span>{{ number_format($totals['total'],2,',',' ') }} {{ $totals['currency'] }}</span>
          </div>

          {{-- Checkout --}}
          <a href="{{ route('checkout.stripe') }}"
             class="mt-3 w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
            💳 Payer avec Stripe
          </a>
          <a href="{{ route('paypal.create') }}"
             class="mt-2 w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
            🅿️ Payer avec PayPal
          </a>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
