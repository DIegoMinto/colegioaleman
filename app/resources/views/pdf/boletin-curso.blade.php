<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Boletines - {{ $curso->nombre }} {{ $curso->paralelo }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            background: white;
        }

        .hoja {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 277mm;
            /* alto A4 - márgenes */
            page-break-after: always;
        }

        .hoja:last-child {
            page-break-after: auto;
        }

        .boleta {
            height: 132mm;
            overflow: hidden;
        }

        .linea-corte {
            border-top: 1px dashed #999;
            text-align: center;
            font-size: 8px;
            color: #999;
            margin: 2mm 0;
        }

        .linea-corte::before {
            content: "✂ ------------------------------------------------------------------------------------------------ ✂";
        }
    </style>
</head>

<body class="bg-white text-black font-sans text-xs">

    @foreach (array_chunk($boletines, 2) as $par)
        <div class="hoja">
            @foreach ($par as $i => $data)
                @if ($i === 1)
                    <div class="linea-corte"></div>
                @endif

                <div class="boleta">
                    @include('pdf.partials.boleta-mini', $data)
                </div>
            @endforeach
        </div>
    @endforeach

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>

</html>