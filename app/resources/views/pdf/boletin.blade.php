<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Libreta Escolar - {{ $estudiante->persona->apellido_p }} {{ $estudiante->persona->nombres }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            background: white;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
    </style>
</head>

<body class="bg-white text-black font-sans text-xs">
    <div id="boletin" class="my-6">
        @include('pdf.partials.boleta-mini', get_defined_vars())
    </div>
    <script>
        window.onload = function () { window.print(); };
    </script>
</body>

</html>