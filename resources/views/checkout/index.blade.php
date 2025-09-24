@extends('layouts.app')
@section('title','Paiement')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl font-semibold mb-4">Paiement</h1>

  <div class="bg-white rounded-lg border p-4">
    <p class="text-gray-700 mb-3">
      Choisissez votre mode de paiement. Les totaux finaux (taxes et frais) seront calculés au moment du paiement.
    </p>
    <div class="flex flex-col sm:flex-row gap-3">
      <a href="{{ route('checkout.stripe') }}"
         class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
        💳 Payer avec Stripe
      </a>
      <a href="{{ route('paypal.create') }}"
         class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
        🅿️ Payer avec PayPal
      </a>
    </div>
    <a href="{{ route('cart.index') }}" class="inline-block mt-4 text-blue-700 hover:underline">← Revenir au panier</a>
  </div>
</div>
@endsection