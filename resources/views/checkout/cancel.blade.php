@extends('layouts.app')
@section('title','Paiement annulé')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
  {{-- Bandeau annulation --}}
  <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 p-4 flex items-start gap-3">
    <div class="shrink-0 mt-0.5">
      <svg class="h-6 w-6 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M5.07 19h13.86A2.07 2.07 0 0021 16.93V7.07A2.07 2.07 0 0018.93 5H5.07A2.07 2.07 0 003 7.07v9.86A2.07 2.07 0 005.07 19z"/>
      </svg>
    </div>
    <div>
      <h1 class="text-xl font-semibold text-amber-900">Paiement annulé</h1>
      <p class="text-amber-800">
        Votre transaction a été interrompue ou annulée. Aucun montant n’a été débité.
      </p>
    </div>
  </div>

  <div class="bg-white rounded-lg shadow border border-gray-200 p-5">
    <p class="text-gray-700">
      Vous pouvez revenir à votre panier pour relancer le paiement, modifier votre commande
      ou choisir un autre moyen de paiement.
    </p>

    <div class="mt-6 flex flex-wrap items-center gap-2">
      <a href="{{ route('cart.index') }}"
         class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
        Retourner au panier
      </a>
      <a href="{{ route('books.index') }}"
         class="px-4 py-2 rounded-md border border-gray-300 hover:bg-gray-50">
        Continuer à magasiner
      </a>
    </div>

    <div class="mt-4 text-sm text-gray-500">
      Besoin d’aide ? <a class="text-blue-600 hover:underline" href="{{ route('messages.create') }}">Contactez-nous</a>.
    </div>
  </div>
</div>
@endsection