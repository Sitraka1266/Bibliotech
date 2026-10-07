<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription · BiblioTech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-blue-950 px-4 py-8">
    <div class="w-full max-w-lg rounded-2xl bg-white p-7 shadow-2xl shadow-slate-950/30 ring-1 ring-slate-200">
        <div class="mb-6 flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Créer un compte</h1>
                <p class="text-sm text-slate-500">Accès bibliothécaire BiblioTech</p>
            </div>
        </div>

        <x-alerts />

        <form method="POST" action="{{ route('register.post') }}" class="space-y-5" novalidate>
            @csrf
            <x-field name="name" label="Nom complet" icon="fa-solid fa-user" />
            <x-field name="email" label="Email" type="email" icon="fa-solid fa-envelope" />
            <x-field name="password" label="Mot de passe" type="password" icon="fa-solid fa-lock" />
            <x-field name="password_confirmation" label="Confirmer le mot de passe" type="password" icon="fa-solid fa-shield-halved" />

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 py-3 font-semibold text-white transition hover:bg-blue-700">
                <i class="fa-solid fa-user-plus"></i>
                S'inscrire
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-600">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="font-semibold text-blue-600 transition hover:text-blue-700 hover:underline">
                Se connecter
            </a>
        </p>
    </div>
</body>

</html>
