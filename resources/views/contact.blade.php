@extends('layouts.app')
@section('title','Contacter Nous')

@section('content')
  <h1 class="h3 mb-3">Contacter Nous</h1>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form method="post" action="{{ route('messages.store') }}" class="vstack gap-3">
    @csrf
    <div>
      <label class="form-label">Nom</label>
      <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="form-label">Email</label>
      <input name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="form-label">Sujet (optionnel)</label>
      <input name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror">
      @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
      <label class="form-label">Message</label>
      <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
      @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button class="btn btn-primary">Envoyer</button>
  </form>

  <hr class="my-4">
  <div class="text-muted">
    <h2 class="h5">Informations de la Librairie E-J</h2>
    <p>Adresse: 123 Rue Principale, Montréal</p>
    <p>Téléphone: (514) 555-1234 — Email: librairieej@mail.com</p>
  </div>
@endsection