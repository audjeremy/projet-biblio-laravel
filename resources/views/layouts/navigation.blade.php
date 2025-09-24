<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    {{-- Livres + sous-menu Nouveautés --}}
                    <div class="relative group">
                        <x-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">
                            {{ __('Livres') }}
                        </x-nav-link>
                        <div class="absolute left-0 mt-2 hidden group-hover:block bg-white border border-gray-200 rounded-md shadow-lg min-w-[160px] z-30">
                            <a href="{{ route('books.news') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Nouveautés
                            </a>
                        </div>
                    </div>

                    <x-nav-link :href="route('messages.create')" :active="request()->routeIs('messages.create')">
                        {{ __('Contact') }}
                    </x-nav-link>

                    @auth
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center">
                        <span>Mon Panier</span>
                        @php $qty = session('cart.items') ? collect(session('cart.items'))->sum('quantity') : 0; @endphp
                        @if($qty > 0)
                        <span class="ml-1 inline-flex items-center justify-center text-xs rounded-full bg-blue-600 text-white w-5 h-5">{{ $qty }}</span>
                        @endif
                    </a>
                    @endauth

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <x-nav-link :href="route('admin.messages.index')" :active="request()->routeIs('admin.messages.*')">
                        {{ __('Admin') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
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
                        <!-- Profile -->
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Logout -->
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('books.index')" :active="request()->routeIs('books.*')">
                {{ __('Livres') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('books.news')">
                Nouveautés
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('messages.create')" :active="request()->routeIs('messages.create')">
                {{ __('Contact') }}
            </x-responsive-nav-link>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('admin.messages.index')" :active="request()->routeIs('admin.messages.*')">
                {{ __('Admin') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
            @else
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">
                    Connexion
                </x-responsive-nav-link>
                @if (Route::has('register'))
                <x-responsive-nav-link :href="route('register')">
                    Inscription
                </x-responsive-nav-link>
                @endif
            </div>
            @endauth
        </div>
    </div>
</nav>