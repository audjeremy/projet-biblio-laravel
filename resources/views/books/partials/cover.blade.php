@php
  /** @var \App\Models\Book $b */
  /** @var bool $newFlag */
@endphp
<div class="relative mb-3">
  {{-- Badge NOUVEAU identique à /news --}}
  @if($newFlag)
    <span class="absolute top-2 right-2 z-10 text-[11px] uppercase tracking-wide bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded shadow">
      Nouveau
    </span>
  @endif

  {{-- Placeholder “image” identique --}}
  <div class="flex items-center justify-center rounded-md border h-40 bg-gradient-to-br from-gray-100 to-gray-200 text-3xl font-bold text-gray-500">
    {{ \Illuminate\Support\Str::substr($b->title,0,1) }}
  </div>
</div>