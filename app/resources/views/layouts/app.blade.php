<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Calificaciones')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased text-gray-900">

    @auth
        <div class="flex min-h-screen">
            @include('partials.sidebar')

            <div class="flex-1 flex flex-col min-w-0">
                @include('partials.topbar')

                <main class="flex-1 p-8 overflow-y-auto">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="min-h-screen">
            @yield('content')
        </main>
    @endauth

</body>

</html>