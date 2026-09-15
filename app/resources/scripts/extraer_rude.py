#!/usr/bin/env python3

import sys
import json
import pdfplumber


def limpiar(valor):
    if valor is None:
        return ''
    return ' '.join(str(valor).split())


def main():
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'Falta la ruta del archivo PDF.'}))
        sys.exit(1)

    ruta = sys.argv[1]
    filas = []

    try:
        with pdfplumber.open(ruta) as pdf:
            for page in pdf.pages:
                tablas = page.extract_tables()
                for tabla in tablas:
                    for fila in tabla:
                        if not fila or len(fila) < 6:
                            continue

                        primera = limpiar(fila[0])
                        if not primera.isdigit():
                            continue  

                        filas.append({
                            'numero_fila': primera,
                            'codigo_rude': limpiar(fila[1]),
                            'carnet': limpiar(fila[2]),
                            'nombre_completo': limpiar(fila[3]),
                            'genero': limpiar(fila[4]),
                            'fecha_nacimiento_texto': limpiar(fila[5]),
                            'departamento': limpiar(fila[7]) if len(fila) > 7 else '',
                            'matricula': limpiar(fila[-1]),
                        })
    except Exception as e:
        print(json.dumps({'error': str(e)}))
        sys.exit(1)

    print(json.dumps(filas, ensure_ascii=False))


if __name__ == '__main__':
    main()