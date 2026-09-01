@props([
    'title',
    'subtitle' => null,
    'actionRoute' => null,
    'actionLabel' => null,
    'backRoute' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200">
    <div>
        <h1 class="text-2xl font-bold text-brand-900 tracking-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @if ($actionRoute)
        <a href="{{ $actionRoute }}" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ $actionLabel }}
        </a>
    @elseif ($backRoute)
        <a href="{{ $backRoute }}" class="btn-secondary">
            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    @endif
</div>