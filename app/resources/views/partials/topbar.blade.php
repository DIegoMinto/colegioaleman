<header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shadow-sm">
    <h2 class="text-base font-bold text-brand-900 tracking-wide uppercase">
        Colegio Boliviano Alemán Cardenal Maurer
    </h2>

    <div class="flex items-center gap-3">
        <div class="text-right">
            <p class="text-xs font-bold text-brand-800 uppercase tracking-wider">
                {{ auth()->user()->role->nombre }}
            </p>
            <p class="text-sm font-semibold text-gray-700">
                {{ auth()->user()->persona->nombres }}
            </p>
        </div>

        <div
            class="w-10 h-10 rounded-full bg-brand-900 text-white font-semibold flex items-center justify-center text-sm shadow">
            {{ strtoupper(substr(auth()->user()->persona->nombres, 0, 2)) }}
        </div>
    </div>
</header>