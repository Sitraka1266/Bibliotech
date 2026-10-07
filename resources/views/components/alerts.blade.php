@if (session('success'))
    <div class="mb-4 rounded-md border border-green-300 bg-green-50 px-4 py-3 text-green-800">
        <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
    </div>
@endif
@foreach ((request()->routeIs('emprunts.create') ? ['suppression', 'auth'] : ['emprunt', 'suppression', 'auth']) as $key)
    @foreach ($errors->get($key) as $message)
        <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-red-800">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ $message }}
        </div>
    @endforeach
@endforeach
@if ($errors->any() && ! $errors->hasAny(['emprunt', 'suppression', 'auth']))
    <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-red-800">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>Le formulaire contient des erreurs. Veuillez les corriger ci-dessous.
    </div>
@endif
