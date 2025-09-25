@extends('layouts.app')
@section('title','Paiement réussi')

@section('content')
@php
    // Sécurise les accès aux champs et évite les notices si certaines clés n'existent pas
    $currency = $order->currency ?? 'CAD';
    $fmt = fn($n) => number_format((float)$n, 2, ',', ' ');
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
  {{-- Bandeau succès --}}
  <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 flex items-start gap-3">
    <div class="shrink-0 mt-0.5">
      <svg class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
    <div>
      <h1 class="text-xl font-semibold text-emerald-800">Paiement confirmé</h1>
      <p class="text-emerald-700">Merci pour votre achat ! Votre commande a été enregistrée avec succès.</p>
    </div>
  </div>

  {{-- Résumé commande --}}
  <div class="bg-white rounded-lg shadow border border-gray-200 p-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
      <div>
        <div class="text-sm text-gray-500">Numéro de commande</div>
        <div class="font-semibold text-gray-900">#{{ $order->id }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Date</div>
        <div class="font-medium text-gray-900">{{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Moyen de paiement</div>
        <div class="font-medium capitalize text-gray-900">
          {{ $order->provider ?? '—' }}
        </div>
      </div>
      <div class="text-right">
        <div class="text-sm text-gray-500">Total payé</div>
        <div class="text-lg font-semibold text-blue-600">
          {{ $fmt($order->total ?? 0) }} {{ $currency }}
        </div>
      </div>
    </div>

    {{-- Articles --}}
    <div class="mt-5">
      <h2 class="text-sm font-semibold text-gray-700 mb-2">Articles</h2>
      <div class="overflow-hidden rounded-md border border-gray-200">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="px-4 py-2 text-left">Titre</th>
              <th class="px-4 py-2 text-left">Auteur</th>
              <th class="px-4 py-2 text-center">Qté</th>
              <th class="px-4 py-2 text-right">Prix unitaire</th>
              <th class="px-4 py-2 text-right">Sous-total</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach(($order->items ?? []) as $it)
              <tr>
                <td class="px-4 py-2">{{ $it->title }}</td>
                <td class="px-4 py-2">{{ $it->author ?? '—' }}</td>
                <td class="px-4 py-2 text-center">{{ $it->quantity }}</td>
                <td class="px-4 py-2 text-right">{{ $fmt($it->unit_price) }} {{ $currency }}</td>
                <td class="px-4 py-2 text-right">{{ $fmt($it->line_total) }} {{ $currency }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Totaux --}}
      <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="text-sm text-gray-500">
          @if(!empty($order->meta['coupon']))
            <div class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200">
              Code promo&nbsp;: <strong class="ml-1">{{ $order->meta['coupon']['code'] ?? '' }}</strong>
            </div>
          @endif
        </div>
        <div class="sm:justify-self-end w-full sm:w-80">
          <dl class="text-sm">
            <div class="flex justify-between py-1">
              <dt>Sous-total</dt>
              <dd>{{ $fmt($order->subtotal ?? 0) }} {{ $currency }}</dd>
            </div>
            @if(($order->discount ?? 0) > 0)
              <div class="flex justify-between py-1">
                <dt>Remise</dt>
                <dd class="text-rose-600">-{{ $fmt($order->discount) }} {{ $currency }}</dd>
              </div>
            @endif
            @if(($order->gst ?? 0) > 0)
              <div class="flex justify-between py-1">
                <dt>TPS</dt>
                <dd>{{ $fmt($order->gst) }} {{ $currency }}</dd>
              </div>
            @endif
            @if(($order->qst ?? 0) > 0)
              <div class="flex justify-between py-1">
                <dt>TVQ</dt>
                <dd>{{ $fmt($order->qst) }} {{ $currency }}</dd>
              </div>
            @endif
            @if(($order->shipping ?? 0) > 0)
              <div class="flex justify-between py-1">
                <dt>Livraison</dt>
                <dd>{{ $fmt($order->shipping) }} {{ $currency }}</dd>
              </div>
            @endif
            <div class="flex justify-between py-2 border-t mt-2 font-semibold">
              <dt>Total</dt>
              <dd class="text-blue-600">{{ $fmt($order->total ?? 0) }} {{ $currency }}</dd>
            </div>
          </dl>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="mt-6 flex flex-wrap items-center gap-2">
      <a href="{{ route('orders.index') }}"
         class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
        Mes achats
      </a>
      <a href="{{ route('books.index') }}"
         class="px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
        Continuer mes achats
      </a>
    </div>
  </div>
</div>
@endsection