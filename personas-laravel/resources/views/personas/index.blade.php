@extends('layouts.app')
@section('contenido')
<h1>Personas registradas</h1>
<table>
<thead><tr>
<th>ID</th><th>DNI</th><th>Nombre</th><th>Nacimiento</th>
</tr></thead>
<tbody>
@forelse ($personas as $persona)  <th>Acciones</th>
<td>
<a href="{{ route('personas.edit', $persona->id) }}">Editar</a>
<form method="POST"
action="{{ route('personas.destroy', $persona->id) }}"
onsubmit="return confirm('¿Eliminar esta persona?');">
@csrf
@method('DELETE')
<button type="submit">Eliminar</button>
</form>
</td>
<tr>
<td>{{ $persona->id }}</td>
<td>{{ $persona->dni }}</td>
<td>{{ $persona->nombre }}</td>
<td>{{ $persona->fecha_nacimiento->format('d/m/Y') }}</td>
</tr>
@empty
<tr><td colspan="4">No hay personas registradas.</td></tr>
@endforelse
</tbody>
del listado:
<p><a href="{{ route('personas.create') }}">Crear persona</a></p>
</table>
@endsection
