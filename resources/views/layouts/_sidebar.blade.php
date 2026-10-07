<div class="flex min-h-full flex-col bg-slate-900 text-white">
    <div class="flex h-16 items-center justify-between border-b border-white/10 px-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-bold tracking-wide">
            <span class="flex size-9 items-center justify-center rounded-lg bg-blue-500/20 text-blue-300">
                <i class="fa-solid fa-book-open-reader"></i>
            </span>
            BiblioTech
        </a>
        @if ($mobile)
            <button
                type="button"
                data-sidebar-close
                aria-label="Fermer le menu"
                class="flex size-9 items-center justify-center rounded-lg text-slate-300 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        @endif
    </div>

    <nav aria-label="Navigation principale" class="flex-1 space-y-1 px-3 py-6">
        @foreach ($links as [$route, $label, $pattern, $icon])
            <a
                href="{{ route($route) }}"
                @if (request()->routeIs($pattern)) aria-current="page" @endif
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition {{ request()->routeIs($pattern) ? 'bg-blue-600 font-semibold text-white shadow-sm' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
            >
                <i class="fa-solid {{ $icon }} w-5 text-center"></i>
                {{ $label }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <p class="mb-3 truncate px-2 text-sm text-slate-300" title="{{ auth()->user()->name }}">
            <i class="fa-solid fa-circle-user mr-2 text-slate-400"></i>{{ auth()->user()->name }}
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition hover:bg-white/10 hover:text-white">
                <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                Déconnexion
            </button>
        </form>
    </div>
</div>
