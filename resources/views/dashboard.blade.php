@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

  @if(($mode ?? null) === 'admin')
    <h1 class="text-2xl font-semibold mb-4">Tableau de bord (Admin)</h1>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="p-4 bg-white rounded-lg border shadow">
        <div class="text-sm text-gray-500">Ventes totales</div>
        <div class="text-2xl font-semibold mt-1">{{ number_format($totalSales, 2, ',', ' ') }} $</div>
      </div>
      <div class="p-4 bg-white rounded-lg border shadow">
        <div class="text-sm text-gray-500"># Commandes</div>
        <div class="text-2xl font-semibold mt-1">{{ $ordersCount }}</div>
      </div>
      <div class="p-4 bg-white rounded-lg border shadow">
        <div class="text-sm text-gray-500">Messages non lus</div>
        <div class="text-2xl font-semibold mt-1">{{ $unreadCount }}</div>
      </div>
      <div class="p-4 bg-white rounded-lg border shadow">
        <div class="text-sm text-gray-500">Nouveaux livres (10 j)</div>
        <div class="text-2xl font-semibold mt-1">{{ $newBooks10d }}</div>
      </div>
    </div>

    {{-- Grids --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- Dernières commandes --}}
      <div class="bg-white rounded-lg border shadow">
        <div class="p-4 border-b">
          <h2 class="font-semibold">Dernières commandes</h2>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-3 py-2 text-left">#</th>
                <th class="px-3 py-2 text-left">Client</th>
                <th class="px-3 py-2 text-left">Total</th>
                <th class="px-3 py-2 text-left">Date</th>
                <th class="px-3 py-2 text-left">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @foreach($latestOrders as $o)
              <tr>
                <td class="px-3 py-2">{{ $o->id }}</td>
                <td class="px-3 py-2">{{ optional($o->user)->name ?? '—' }}</td>
                <td class="px-3 py-2">{{ number_format($o->total,2,',',' ') }} $</td>
                <td class="px-3 py-2">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-3 py-2">
                  <a class="text-blue-600 hover:underline" href="{{ route('admin.orders.show',$o) }}">Voir</a>
                </td>
              </tr>
              @endforeach
              @if($latestOrders->isEmpty())
              <tr><td class="px-3 py-4 text-gray-500" colspan="5">Aucune commande.</td></tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>

      {{-- Derniers messages --}}
      <div class="bg-white rounded-lg border shadow">
        <div class="p-4 border-b">
          <h2 class="font-semibold">Derniers messages</h2>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-3 py-2 text-left">Nom</th>
                <th class="px-3 py-2 text-left">Email</th>
                <th class="px-3 py-2 text-left">Sujet</th>
                <th class="px-3 py-2 text-left">Statut</th>
                <th class="px-3 py-2 text-left">Reçu</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @foreach($latestMessages as $m)
              <tr class="{{ $m->is_read ? '' : 'bg-orange-50' }}">
                <td class="px-3 py-2">{{ $m->name }}</td>
                <td class="px-3 py-2"><a href="mailto:{{ $m->email }}" class="text-blue-600 hover:underline">{{ $m->email }}</a></td>
                <td class="px-3 py-2">{{ $m->subject ?? '—' }}</td>
                <td class="px-3 py-2">{{ $m->is_read ? 'Lu' : 'Non lu' }}</td>
                <td class="px-3 py-2">{{ $m->created_at->format('d/m/Y H:i') }}</td>
              </tr>
              @endforeach
              @if($latestMessages->isEmpty())
              <tr><td class="px-3 py-4 text-gray-500" colspan="5">Aucun message.</td></tr>
              @endif
            </tbody>
          </table>
          <div class="mt-3">
            <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:underline">Voir tous les messages →</a>
          </div>
        </div>
      </div>

      {{-- Derniers livres --}}
      <div class="bg-white rounded-lg border shadow lg:col-span-2">
        <div class="p-4 border-b">
          <h2 class="font-semibold">Derniers livres</h2>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-3 py-2 text-left">Titre</th>
                <th class="px-3 py-2 text-left">Auteur</th>
                <th class="px-3 py-2 text-left">Catégorie</th>
                <th class="px-3 py-2 text-left">Prix</th>
                <th class="px-3 py-2 text-left">Créé</th>
                <th class="px-3 py-2 text-left">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @foreach($latestBooks as $b)
              <tr>
                <td class="px-3 py-2">{{ $b->title }}</td>
                <td class="px-3 py-2">{{ $b->author }}</td>
                <td class="px-3 py-2">{{ $b->category ?? '—' }}</td>
                <td class="px-3 py-2">{{ number_format($b->price,2,',',' ') }} $</td>
                <td class="px-3 py-2">{{ $b->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-3 py-2">
                  <a class="text-blue-600 hover:underline" href="{{ route('books.show',$b) }}">Voir</a>
                </td>
              </tr>
              @endforeach
              @if($latestBooks->isEmpty())
              <tr><td class="px-3 py-4 text-gray-500" colspan="6">Aucun livre.</td></tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

  @else
    {{-- Vue utilisateur simple : ses commandes --}}
    <h1 class="text-2xl font-semibold mb-4">Mes commandes</h1>
    <div class="bg-white rounded-lg border shadow">
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-3 py-2 text-left">#</th>
              <th class="px-3 py-2 text-left">Total</th>
              <th class="px-3 py-2 text-left">Date</th>
              <th class="px-3 py-2 text-left">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            @foreach(($orders ?? collect()) as $o)
            <tr>
              <td class="px-3 py-2">{{ $o->id }}</td>
              <td class="px-3 py-2">{{ number_format($o->total,2,',',' ') }} $</td>
              <td class="px-3 py-2">{{ $o->created_at->format('d/m/Y H:i') }}</td>
              <td class="px-3 py-2">
                <a href="{{ route('orders.show',$o) }}" class="text-blue-600 hover:underline">Voir</a>
              </td>
            </tr>
            @endforeach
            @if(($orders ?? collect())->isEmpty())
            <tr><td class="px-3 py-4 text-gray-500" colspan="4">Aucune commande.</td></tr>
            @endif
          </tbody>
        </table>
      </div>
      @if(method_exists(($orders ?? null),'links'))
        <div class="p-4">{{ $orders->links() }}</div>
      @endif
    </div>
  @endif

</div>
@endsection