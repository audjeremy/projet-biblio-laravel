@extends('layouts.app')
@section('title','Commande #'.$order->id)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="mb-6">
    <h1 class="text-2xl font-semibold">Commande #{{ $order->id }}</h1>
    <p class="text-sm text-gray-500">
      {{ $order->created_at->format('d/m/Y H:i') }} • {{ strtoupper($order->provider) }} • Statut: <strong>{{ ucfirst($order->status) }}</strong>
    </p>
  </div>

  <div class="bg-white rounded-lg border border-gray-200 p-4">
    <h2 class="font-semibold mb-3">Articles</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="px-4 py-2 text-left">Livre</th>
            <th class="px-4 py-2 text-left">Auteur</th>
            <th class="px-4 py-2 text-right">Qté</th>
            <th class="px-4 py-2 text-right">Prix unitaire</th>
            <th class="px-4 py-2 text-right">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @foreach($order->items as $it)
          <tr>
            <td class="px-4 py-2">{{ $it->title }}</td>
            <td class="px-4 py-2">{{ $it->author }}</td>
            <td class="px-4 py-2 text-right">{{ $it->quantity }}</td>
            <td class="px-4 py-2 text-right">{{ number_format($it->unit_price,2,',',' ') }} {{ $order->currency }}</td>
            <td class="px-4 py-2 text-right">{{ number_format($it->line_total,2,',',' ') }} {{ $order->currency }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4 grid gap-1 text-sm">
      <div class="flex justify-between"><span>Sous-total</span><span>{{ number_format($order->subtotal,2,',',' ') }} {{ $order->currency }}</span></div>
      @if($order->discount > 0)
        <div class="flex justify-between"><span>Remise</span><span>-{{ number_format($order->discount,2,',',' ') }} {{ $order->currency }}</span></div>
      @endif
      @if($order->gst > 0)
        <div class="flex justify-between"><span>TPS</span><span>{{ number_format($order->gst,2,',',' ') }} {{ $order->currency }}</span></div>
      @endif
      @if($order->qst > 0)
        <div class="flex justify-between"><span>TVQ</span><span>{{ number_format($order->qst,2,',',' ') }} {{ $order->currency }}</span></div>
      @endif
      @if($order->shipping > 0)
        <div class="flex justify-between"><span>Frais d’expédition</span><span>{{ number_format($order->shipping,2,',',' ') }} {{ $order->currency }}</span></div>
      @endif
      <div class="flex justify-between font-semibold mt-2 border-t pt-2">
        <span>Total</span><span>{{ number_format($order->total,2,',',' ') }} {{ $order->currency }}</span>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <a href="{{ url()->previous() === url()->current() ? route('orders.index') : url()->previous() }}" class="text-blue-600 hover:underline">← Retour</a>
  </div>
</div>
@endsection