@props(['active'])

@if ($active)
    <span
        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
        Activo
    </span>
@else
    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
        Inactivo
    </span>
@endif