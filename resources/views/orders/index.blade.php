@extends('layouts.app')
@section('title','Mes achats')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <h1 class="text-2xl font-semibold mb-6">Mes achats</h1>

  @if($orders->isEmpty())
    <div class="rounded-md border border-gray-200 p-4 bg-white">
      Aucune commande pour l’instant.
    </div>
  @else
    <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
          <tr>
            <th class="px-4 py-2 text-left">Date</th>
            <th class="px-4 py-2 text-left">Fournisseur</th>
            <th class="px-4 py-2 text-left">Statut</th>
            <th class="px-4 py-2 text-right">Total</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
        @foreach($orders as $o)
          <tr>
            <td class="px-4 py-2">{{ $o->created_at->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-2 uppercase">{{ $o->provider }}</td>
            <td class="px-4 py-2">{{ ucfirst($o->status) }}</td>
            <td class="px-4 py-2 text-right">{{ number_format($o->total,2,',',' ') }} {{ $o->currency }}</td>
            <td class="px-4 py-2 text-right">
              <a href="{{ route('orders.show',$o) }}" class="px-2 py-1 text-xs border rounded hover:bg-gray-50">Voir</a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
  @endif
</div>
@endsection