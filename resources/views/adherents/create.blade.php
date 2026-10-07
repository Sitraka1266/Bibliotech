@extends('layouts.app')
@section('title', 'Ajouter un adhérent')
@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center gap-4">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-xl text-blue-700">
                <i class="fa-solid fa-user-plus"></i>
            </span>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Adhérents</p>
                <h1 class="text-2xl font-bold text-slate-900">Ajouter un adhérent</h1>
                <p class="mt-1 text-sm text-slate-500">Renseignez les informations du nouvel adhérent.</p>
            </div>
        </div>
        @include('adherents._form', ['action' => route('adherents.store')])
    </div>
@endsection
