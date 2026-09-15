<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Administrativos</title>
    <style>
        @page {
            margin: 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
        }

        .encabezado {
            display: table;
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .encabezado img {
            height: 55px;
        }

        .encabezado .titulo {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }

        .encabezado h1 {
            font-size: 15px;
            margin: 0;
            text-transform: uppercase;
        }

        .encabezado p {
            font-size: 9px;
            color: #555;
            margin: 2px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #eee;
            font-weight: bold;
        }

        .izq {
            text-align: left;
        }

        .mayus {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="encabezado">
        <div class="titulo">
            <h1>Listado de Personal Administrativo</h1>
            <p>Generado el {{ now()->format('d/m/Y H:i') }} &middot; Total: {{ $administrativos->count() }}</p>
        </div>
    </div>

    @include('pdf.partials.listado-personas', ['registros' => $administrativos, 'mostrarRude' => false])
</body>

</html>