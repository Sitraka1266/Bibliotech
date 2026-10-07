<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'BiblioTech') · BiblioTech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800">
    @php
        $links = [
            ['dashboard', 'Tableau de bord', 'dashboard', 'fa-chart-line'],
            ['livres.index', 'Livres', 'livres*', 'fa-book'],
            ['adherents.index', 'Adhérents', 'adherents*', 'fa-users'],
            ['emprunts.index', 'Emprunts', 'emprunts*', 'fa-arrow-right-arrow-left'],
        ];
    @endphp

    <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-slate-900 text-white shadow-xl lg:flex">
        @include('layouts._sidebar', ['mobile' => false])
    </aside>

    <dialog
        data-sidebar-dialog
        aria-label="Menu de navigation"
        class="m-0 h-dvh max-h-none w-[min(18rem,85vw)] max-w-none border-0 bg-transparent p-0 text-left backdrop:bg-slate-950/60 open:fixed open:inset-y-0 open:left-0 open:right-auto open:ml-0 open:mr-auto"
    >
        @include('layouts._sidebar', ['mobile' => true])
    </dialog>

    <div class="min-h-screen lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm lg:hidden">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-bold text-slate-900">
                <span class="flex size-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                    <i class="fa-solid fa-book-open-reader"></i>
                </span>
                BiblioTech
            </a>
            <button
                type="button"
                data-sidebar-open
                aria-label="Ouvrir le menu"
                aria-haspopup="dialog"
                class="flex size-10 items-center justify-center rounded-lg text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </header>

        <main class="mx-auto w-full max-w-[1600px] px-4 py-5 sm:px-6 lg:px-8 lg:py-8">
            <x-alerts />
            @yield('content')
        </main>
    </div>

    <dialog
        data-delete-dialog
        aria-labelledby="delete-dialog-title"
        aria-describedby="delete-dialog-description"
        class="m-auto w-[min(28rem,calc(100%-2rem))] rounded-2xl border border-slate-200 bg-white p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/60"
    >
        <div class="p-6 sm:p-7">
            <div class="flex items-start gap-4">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-red-100 text-lg text-red-700">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
                <div class="min-w-0 flex-1">
                    <h2 id="delete-dialog-title" class="text-lg font-bold text-slate-900">Confirmer la suppression</h2>
                    <p id="delete-dialog-description" class="mt-2 text-sm leading-6 text-slate-600">
                        Voulez-vous vraiment supprimer
                        <span data-delete-item class="font-semibold text-slate-800"></span> ?
                        Cette action est irréversible.
                    </p>
                </div>
            </div>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    data-delete-cancel
                    class="rounded-xl border border-slate-300 px-5 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2"
                >
                    Annuler
                </button>
                <button
                    type="button"
                    data-delete-confirm
                    class="rounded-xl bg-red-600 px-5 py-2.5 font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Supprimer
                </button>
            </div>
        </div>
    </dialog>
</body>
</html>
