@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => true, 'min' => null, 'max' => null, 'icon' => null])
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-medium text-slate-700">
        @if ($icon)
            <span class="mr-2 inline-flex h-5 w-5 items-center justify-center text-blue-600"><i class="{{ $icon }}"></i></span>
        @endif
        {{ $label }} @if ($required)<span class="text-red-600">*</span>@endif
    </label>
    <div class="relative">
        @if ($icon)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
               @if ($min !== null) min="{{ $min }}" @endif
               @if ($max !== null) max="{{ $max }}" @endif
               class="w-full rounded-xl border px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 {{ $icon ? 'pl-10' : '' }} {{ $errors->has($name) ? 'border-red-500 bg-red-50' : 'border-slate-300 bg-white' }}">
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
