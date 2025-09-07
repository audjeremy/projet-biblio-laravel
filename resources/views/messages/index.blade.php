@extends('layouts.app')
@section('title','Messages')

@section('content')
  <h1 class="h3 mb-3">Messages reçus</h1>

  @if($messages->isEmpty())
    <div class="alert alert-info">Aucun message pour le moment.</div>
  @else
    <div class="vstack gap-3">
      @foreach($messages as $m)
        <article class="border rounded p-3">
          <header class="d-flex justify-content-between align-items-center">
            <h3 class="h6 mb-0">{{ $m->subject ?? '(Sans sujet)' }}</h3>
            <small class="text-muted">{{ $m->created_at->format('Y-m-d H:i') }}</small>
          </header>
          <p class="mb-1"><strong>{{ $m->name }}</strong> — {{ $m->email }}</p>
          <p class="mb-0">{{ $m->message }}</p>
        </article>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $messages->links() }}
    </div>
  @endif
@endsection