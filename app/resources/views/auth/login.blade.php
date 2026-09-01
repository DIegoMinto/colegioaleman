<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Colegio Boliviano Alemán</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100">

    <main class="min-h-screen flex">

        <section class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-brand-900">

            <div class="absolute inset-0 bg-gradient-to-br from-brand-950 via-brand-900 to-brand-800"></div>

            <div class="relative z-10 flex flex-col justify-between w-full p-12 xl:p-16 text-white">

                <div class="flex justify-center">
                    <div class="w-48 h-48 logo-circle">
                        <img src="{{ asset('images/escudo-colegio.png') }}" alt="Logo Colegio Boliviano Alemán"
                            class="w-36 h-36 object-contain">
                    </div>
                </div>

                <div class="max-w-xl mx-auto text-center">

                    <h2 class="text-3xl xl:text-4xl font-semibold tracking-tight mb-5">
                        Colegio Boliviano Alemán
                    </h2>

                    <p class="text-brand-100 text-base xl:text-lg leading-relaxed">
                        Formando estudiantes con excelencia académica,
                        valores y una visión integral para el futuro.
                    </p>

                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 max-w-2xl mx-auto">

                    <div>
                        <h3 class="text-lg font-semibold mb-2">
                            Misión
                        </h3>

                        <p class="text-sm text-brand-100 leading-relaxed">
                            Brindar una educación integral de calidad,
                            formando personas responsables, críticas,
                            creativas y comprometidas con la sociedad.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">
                            Visión
                        </h3>

                        <p class="text-sm text-brand-100 leading-relaxed">
                            Ser una institución educativa reconocida por
                            su excelencia, innovación y formación integral
                            de sus estudiantes.
                        </p>
                    </div>

                </div>

            </div>

        </section>

        <section class="w-full lg:w-1/2 min-h-screen flex items-center justify-center bg-white px-6 py-12">

            <div class="w-full max-w-md">

                <div class="mb-10">

                    <div class="lg:hidden flex justify-center mb-8">
                        <div class="w-28 h-28 bg-brand-900 shadow-lg logo-circle">
                            <img src="{{ asset('images/escudo-colegio.png') }}" alt="Logo Colegio Boliviano Alemán"
                                class="w-20 h-20 object-contain">
                        </div>
                    </div>

                    <p class="text-sm font-medium text-brand-800 mb-2">
                        Bienvenido
                    </p>

                    <h1 class="text-3xl sm:text-4xl font-semibold text-gray-900 tracking-tight">
                        WILKOMMEN
                    </h1>

                    <p class="mt-3 text-gray-500 leading-relaxed">
                        Ingrese sus credenciales para acceder a la plataforma.
                    </p>

                </div>

                @if ($errors->any())
                    <div class="error-box">
                        <ul class="space-y-1 text-sm text-brand-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">

                    @csrf

                    <div>
                        <label for="user" class="form-label">
                            Usuario
                        </label>

                        <input type="text" name="user" id="user" value="{{ old('user') }}" required
                            autocomplete="username" placeholder="Ingrese su usuario" class="form-input">
                    </div>

                    <div>
                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            placeholder="Ingrese su contraseña" class="form-input">
                    </div>

                    <button type="submit" class="btn-primary">
                        Ingresar
                    </button>

                </form>

                <div class="mt-10 border-t border-gray-100 pt-6 text-center">

                    <p class="text-xs leading-relaxed text-gray-400">
                        Si tiene problemas con el inicio de sesión,
                        por favor comuníquese con administración.
                    </p>

                </div>

            </div>

        </section>

    </main>

</body>

</html>