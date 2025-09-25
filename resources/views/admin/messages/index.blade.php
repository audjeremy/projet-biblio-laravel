@extends('layouts.app')
@section('title','Messages reçus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Messages reçus</h1>

    {{-- Compteur non lus --}}
    @php $unread = $messages->where('is_read', false)->count(); @endphp
    <div class="text-sm text-gray-600">
      Non lus :
      <span class="inline-flex items-center px-2 py-0.5 rounded bg-orange-100 text-orange-800 font-medium">
        {{ $unread }}
      </span>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-4 p-3 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
      {{ session('success') }}
    </div>
  @endif

  @if($messages->isEmpty())
    <div class="p-4 rounded-md border border-blue-200 bg-blue-50">
      Aucun message reçu.
    </div>
  @else
    <div class="overflow-x-auto rounded-lg border border-gray-200">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-2 text-left">Nom</th>
            <th class="px-4 py-2 text-left">Email</th>
            <th class="px-4 py-2 text-left">Sujet</th>
            <th class="px-4 py-2 text-left">Reçu</th>
            <th class="px-4 py-2 text-left">Statut</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @foreach($messages as $m)
          <tr class="{{ $m->is_read ? 'bg-white' : 'bg-orange-50' }}">
            <td class="px-4 py-2 font-medium">{{ $m->name }}</td>
            <td class="px-4 py-2">
              <a href="mailto:{{ $m->email }}" class="text-blue-600 hover:underline">{{ $m->email }}</a>
            </td>
            <td class="px-4 py-2">{{ $m->subject ?: '—' }}</td>
            <td class="px-4 py-2">{{ $m->created_at->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-2">
              @if($m->is_read)
                <span class="px-2 py-0.5 text-xs rounded bg-gray-200">Lu</span>
              @else
                <span class="px-2 py-0.5 text-xs rounded bg-orange-200 text-orange-800">Non lu</span>
              @endif
            </td>
            <td class="px-4 py-2 text-right space-x-2">
              {{-- Voir contenu (details/summary natif) --}}
              <details class="group inline-block">
                <summary class="px-2 py-1 text-xs border border-blue-500 text-blue-600 rounded hover:bg-blue-50 cursor-pointer">
                  Voir
                </summary>
                <div class="mt-2 p-3 bg-white border rounded shadow-sm w-[36rem] max-w-[90vw]">
                  <div class="text-sm text-gray-500 mb-1">Message :</div>
                  <div class="whitespace-pre-line">{{ $m->message }}</div>
                </div>
              </details>

              {{-- Marquer comme lu / non lu --}}
              @if(!$m->is_read)
                <form action="{{ route('admin.messages.read',$m) }}" method="POST" class="inline">
                  @csrf @method('PATCH')
                  <button class="px-2 py-1 text-xs bg-emerald-600 text-white rounded hover:bg-emerald-700">
                    Marquer comme lu
                  </button>
                </form>
              @else
                <form action="{{ route('admin.messages.unread',$m) }}" method="POST" class="inline">
                  @csrf @method('PATCH')
                  <button class="px-2 py-1 text-xs bg-amber-500 text-white rounded hover:bg-amber-600">
                    Marquer comme non lu
                  </button>
                </form>
              @endif

              {{-- Supprimer --}}
              <form action="{{ route('admin.messages.destroy',$m) }}" method="POST" class="inline"
                    onsubmit="return confirm('Supprimer ce message ?')">
                @csrf @method('DELETE')
                <button class="px-2 py-1 text-xs bg-rose-600 text-white rounded hover:bg-rose-700">
                  Supprimer
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $messages->links() }}
    </div>
  @endif
</div>
@endsection