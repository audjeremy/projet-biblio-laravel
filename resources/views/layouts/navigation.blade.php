<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <div class="flex">
        <!-- Logo + Titre -->
        <div class="shrink-0 flex items-center">
          <a href="{{ route('books.index') }}" class="flex items-center gap-2">
            <span class="text-2xl">📚</span>
            <span class="font-semibold tracking-tight">Librairie E-J</span>
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
          <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*') && !request()->routeIs('books.news')">
            {{ __('Livres') }}
          </x-nav-link>

          <x-nav-link :href="route('books.news')" :active="request()->routeIs('books.news')">
            {{ __('Nouveautés') }}
          </x-nav-link>

          <x-nav-link :href="route('messages.create')" :active="request()->routeIs('messages.create')">
            {{ __('Contact') }}
          </x-nav-link>

          @auth
            @if(auth()->user()->role === 'admin')
              <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
              </x-nav-link>
              <x-nav-link :href="route('admin.messages.index')" :active="request()->routeIs('admin.messages.*')">
                {{ __('Messages') }}
              </x-nav-link>
            @else
              <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                {{ __('Mes achats') }}
              </x-nav-link>
            @endif
          @endauth
        </div>
      </div>

      <!-- Right side: Cart + Settings -->
      <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
        @auth
          <!-- Panier -->
          <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-gray-900">
            🛒
          </a>

          <!-- User dropdown -->
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 transition">
                <div>{{ Auth::user()->name }}</div>
                <div class="ms-1">
                  <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
                  </svg>
                </div>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
              </x-dropdown-link>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                  onclick="event.preventDefault(); this.closest('form').submit();">
                  {{ __('Log Out') }}
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        @else
          <div class="space-x-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Connexion</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Inscription</a>
            @endif
          </div>
        @endauth
      </div>

      <!-- Hamburger -->
      <div class="-me-2 flex items-center sm:hidden">
        <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{ 'hidden': open, 'inline-flex': !open }"
                  class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{ 'hidden': !open, 'inline-flex': open }"
                  class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Menu -->
  <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.*') && !request()->routeIs('books.news')">
        {{ __('Livres') }}
      </x-responsive-nav-link>

      <x-responsive-nav-link :href="route('books.news')" :active="request()->routeIs('books.news')">
        {{ __('Nouveautés') }}
      </x-responsive-nav-link>

      <x-responsive-nav-link :href="route('messages.create')" :active="request()->routeIs('messages.create')">
        {{ __('Contact') }}
      </x-responsive-nav-link>

      @auth
        <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
          🛒 Mon panier
        </x-responsive-nav-link>

        @if(auth()->user()->role === 'admin')
          <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-responsive-nav-link>
          <x-responsive-nav-link :href="route('admin.messages.index')" :active="request()->routeIs('admin.messages.*')">
            {{ __('Messages') }}
          </x-responsive-nav-link>
        @else
          <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
            {{ __('Mes achats') }}
          </x-responsive-nav-link>
        @endif
      @endauth
    </div>
  </div>
</nav>