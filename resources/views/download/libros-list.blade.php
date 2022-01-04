<!doctype html>
<html>
    <head>
        <title>LISTA</title>
    </head>
    <body>
        <table>
            <tr>
                <th><b>Tipo</b></th>
                <th><b>Código</b></th>
                <th><b>Libro</b></th>
            </tr>
            @foreach($libros as $libro)
                <tr>
                    <td>{{ $libro->tipo_id == 1 ? 'común':'inglés' }}</td>
                    <td>{{ $libro->code }}</td>
                    <td>{{ $libro->libro }}</td>
                </tr>
            @endforeach 
        </table>
    </body>
</html>