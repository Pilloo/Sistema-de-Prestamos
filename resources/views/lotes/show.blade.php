@extends('template')

@section('title', 'Detalle de Lote')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Detalle del Lote #{{ $loteEquipo->id }}</h3>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="row">
        <div class="col-md-4">
            @if ($loteEquipo->img_path)
                <div class="mt-3">
                    <strong>Imagen:</strong>
                    <img src="{{ asset('img/equipos/' . $loteEquipo->img_path) }}" alt="Imagen equipo" class="img-thumbnail mt-2" style="width: 300px; height: 200px; object-fit: cover;">
                </div>
            @else
                <p class="mt-3"><em>No hay imagen disponible.</em></p>
            @endif
        </div>
        <div class="col-md-8">
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Modelo:</strong> {{ $loteEquipo->modelo }}</li>
                <li class="list-group-item"><strong>Contenido etiqueta:</strong> {{ $loteEquipo->contenido_etiqueta }}</li>
                <li class="list-group-item"><strong>Detalle:</strong> {{ $loteEquipo->detalle }}</li>
                <li class="list-group-item"><strong>Marca:</strong> 
                    {{ $loteEquipo->marca && $loteEquipo->marca->caracteristica ? $loteEquipo->marca->caracteristica->nombre : 'Sin marca' }}
                </li>
                <li class="list-group-item"><strong>Categorías:</strong>
                    @foreach ($loteEquipo->categorias as $listaCategorias)
                        <ul>
                            <li>{{$listaCategorias->caracteristica->nombre ? $listaCategorias->caracteristica->nombre : 'Sin categoría'}}</li>
                        </ul>
                    @endforeach
                </li>
                <li class="list-group-item"><strong>Cantidad Total:</strong> {{ $loteEquipo->cantidad_total }}</li>
                <li class="list-group-item"><strong>Cantidad Disponible:</strong> {{ $loteEquipo->cantidad_disponible }}</li>
            </ul>
            <a href="{{ route('lotes.edit', $loteEquipo->id) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('lotes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
    <div class="mt-4">
        <h5>Equipos en el lote</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Serie</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->id }}</td>
                    <td>{{ $equipo->numero_serie }}</td>
                    <td>{{ $equipo->estado_equipo ? $equipo->estado_equipo->nombre : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
