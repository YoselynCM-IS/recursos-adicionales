<!doctype html>
<html>
    <head>
        <title>LISTA</title>
    </head>
    <body>
        <table>
            <tr>
                <th><b>Creado el</b></th>
                <th><b>Código</b></th>
                <th><b>Libro</b></th>
                <th><b>Meses (Vigente)</b></th>
                <th><b>Inicio el</b></th>
                <th><b>Termina el</b></th>
                <th><b>Estado</b></th>
                <th><b>Nombre</b></th>
                <th><b>Correo</b></th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->created_at->format('d-m-Y') }}</td>
                    <td>{{ $user->codigo }}</td>
                    <td>{{ $user->acceso->libro->libro }}</td>
                    <td>{{ $user->acceso->months }}</td>
                    <td>{{ $user->acceso->inicio }}</td>
                    <td>{{ $user->acceso->final }}</td>
                    <td>{{ $user->estado }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                </tr>
            @endforeach 
        </table>
    </body>
</html>