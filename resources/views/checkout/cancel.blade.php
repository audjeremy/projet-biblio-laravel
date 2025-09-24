@extends('layouts.app')
@section('title','Paiement annulé')

@section('content')
<div class="container">
  <div class="alert alert-warning">
    ❌ Paiement annulé. Votre panier est toujours actif.
  </div>
  <a href="{{ route('cart.index') }}" class="btn btn-secondary">Retour au panier</a>
</div>
@endsection
