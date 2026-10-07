<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion · BiblioTech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-7 shadow-2xl shadow-slate-950/30 ring-1 ring-slate-200">
        <div class="mb-6 flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">BiblioTech</h1>
                <p class="text-sm text-slate-500">Espace bibliothécaire</p>
            </div>
        </div>

        <x-alerts />

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5" novalidate>
            @csrf
            <x-field name="email" label="Email" type="email" icon="fa-solid fa-envelope" />
            <x-field name="password" label="Mot de passe" type="password" icon="fa-solid fa-lock" />

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700">
                <i class="fa-solid fa-right-to-bracket"></i>
                Se connecter
            </button>
        </form>

        <div class="mt-5 rounded-xl bg-slate-50 px-3 py-2 text-center text-xs text-slate-600">
            <i class="fa-solid fa-circle-info mr-1 text-blue-600"></i>
            Compte de test : bibliothecaire@bibliotech.test / password
        </div>

        <p class="mt-5 text-center text-sm text-slate-600">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 transition hover:text-blue-700 hover:underline">
                Créer un compte
            </a>
        </p>
    </div>
</body>

</html>