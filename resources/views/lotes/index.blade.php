@extends('template')

@section('title', 'Listado de Lotes')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Lotes de Equipos</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <a href="{{ route('lotes.create') }}" class="btn btn-primary mb-3">Nuevo Lote</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Categorías</th>
                <th>Cantidad Total</th>
                <th>Cantidad Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lotes as $lote)
            <tr>
                <td>{{ $lote->id }}</td>
                <td>{{ $lote->modelo }}</td>
                <td>
                    {{ $lote->marca && $lote->marca->caracteristica ? $lote->marca->caracteristica->nombre : 'Sin marca' }}
                </td>
                <td>
                    @foreach ($lote->categorias as $listaCategorias)
                        <div class="container" style="font-size: small;">
                            <div class="row">
                                <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{$listaCategorias->caracteristica->nombre}}</span>
                            </div>
                        </div>
                    @endforeach
                </td>
                <td>{{ $lote->cantidad_total }}</td>
                <td>{{ $lote->cantidad_disponible }}</td>
                <td>
                    <a href="{{ route('lotes.show', $lote->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('lotes.edit', $lote->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('lotes.destroy', $lote->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar lote?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
