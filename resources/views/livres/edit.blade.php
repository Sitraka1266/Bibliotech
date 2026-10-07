@extends('layouts.app')
@section('title', 'Modifier un livre')
@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center gap-4">
            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-xl text-indigo-700">
                <i class="fa-regular fa-pen-to-square"></i>
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-700">Catalogue</p>
                <h1 class="truncate text-2xl font-bold text-slate-900">Modifier « {{ $livre->titre }} »</h1>
                <p class="mt-1 text-sm text-slate-500">Mettez à jour les informations de cet ouvrage.</p>
            </div>
        </div>
        @include('livres._form', ['action' => route('livres.update', $livre)])
    </div>
@endsection
