@extends('layouts.app')
@section('title','Paiement réussi')

@section('content')
<div class="container">
  <div class="alert alert-success">
    ✅ Merci, votre paiement a bien été reçu !
  </div>

  @isset($order)
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Commande #{{ $order->id }}</h5>
        <ul class="mb-0">
          <li>Total : <strong>{{ number_format($order->total, 2, ',', ' ') }} {{ $order->currency }}</strong></li>
          <li>Statut : <span class="badge bg-success">{{ $order->status }}</span></li>
          <li>Fournisseur : {{ strtoupper($order->provider) }}</li>
        </ul>
      </div>
    </div>
  @endisset

  <a href="{{ route('books.index') }}" class="btn btn-primary">Retourner à la boutique</a>
</div>
@endsection
