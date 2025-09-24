@extends('layouts.app')
@section('title','Paiement')

@section('content')
<div class="container">
  <h1 class="mb-3">Paiement</h1>
  <div class="alert alert-info">
    Cette page servira pour l’intégration Stripe/PayPal (prochaine étape).
  </div>
  <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">← Retour au panier</a>
</div>
@endsection
