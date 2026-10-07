<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BiblioTech') · BiblioTech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <nav class="bg-slate-800 text-white shadow">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-6 gap-y-2 px-4 py-3">
            <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-wide">
                <i class="fa-solid fa-book-open-reader mr-2"></i>BiblioTech
            </a>
            @php
                $links = [
                    ['dashboard', 'Tableau de bord', 'dashboard'],
                    ['livres.index', 'Livres', 'livres*'],
                    ['adherents.index', 'Adhérents', 'adherents*'],
                    ['emprunts.index', 'Emprunts', 'emprunts*'],
                ];
            @endphp
            @foreach ($links as [$route, $label, $pattern])
                <a href="{{ route($route) }}"
                   class="rounded px-2 py-1 text-sm hover:bg-slate-700 {{ request()->routeIs($pattern) ? 'bg-slate-700 font-semibold' : '' }}">{{ $label }}</a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                @csrf
                <span class="mr-2 hidden text-sm text-slate-300 sm:inline">{{ auth()->user()->name }}</span>
                <button class="rounded bg-slate-600 px-3 py-1 text-sm hover:bg-slate-500">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i>Déconnexion
                </button>
            </form>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-6">
        <x-alerts />
        @yield('content')
    </main>
</body>
</html>
