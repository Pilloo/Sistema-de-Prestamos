@extends('template')

@section('title','Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<body class="ContenidoPrincipal">
    <div id="contenedorEquipos" class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Listado de Equipos</h2>
            <a href="{{ route('equipos.create') }}">
                <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
            </a>
        </div>

        <div class="mb-3">
            <input id="placeholderEquipos" type="text" class="form-control" id="buscador" placeholder="Buscar equipo...">
        </div>

        <div id="contenedorTablaEquipos" class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="equipoBody">
                    @foreach($equipos as $index => $item)
                    <tr>
                        <td>{{ $item->id }}</td>    
                        <td>{{ $item->modelo }}</td>
                        <td>
                            {{ $item->marca && $item->marca->caracteristica ? $item->marca->caracteristica->nombre : 'Sin marca' }}
                        </td>
                        <td>
                            @foreach ($item->categorias as $listaCategorias)
                                <div class="container" style="font-size: small;">
                                    <div class="row">
                                        <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{$listaCategorias->caracteristica->nombre}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            {{ $item->estado_equipo ? $item->estado_equipo->nombre : 'Sin estado' }}
                        </td>

                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verModal{{$index}}">Ver</button>
                                <form action="{{ route('equipos.edit', ['equipo' => $item]) }}" method="get">
                                    <button type="submit" class="btn btn-warning">Editar</button>
                                </form>  
                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
