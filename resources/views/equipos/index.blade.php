@extends('template')

@section('title','Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/scannerLector.css') }}">
@endpush

@section('content')

@if(session('success'))
<script>
// Para recuperar el mensaje que quiero que muestre el TOAST
let message = "{{ session('success') }}";


const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
});
Toast.fire({
  icon: "success",
  title: message // ← CORRECTO (sin punto y coma)
});
</script>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<body class="ContenidoPrincipal">
    <div id="contenedorEquipos" class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Listado de Equipos</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('equipos.create') }}">
                    <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
                </a>
                <button type="button" class="btn btn-primary" onclick="openBarcodeModal()">Búsqueda con lector</button>
            </div>
        </div>


        <div class="mb-3">
            <input id="placeholderEquipos" type="text" class="form-control" id="buscador" placeholder="Buscar equipo...">
        </div>

        <div id="contenedorTablaEquipos">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th>Codigo</th>
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
                            {{ $item->contenido_etiqueta ? $item->contenido_etiqueta : 'Sin codigo' }}
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
                                @if($item->estado_equipo->nombre!='Baja / Retirado')
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Eliminar</button>
                                @else
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Restaurar</button>
                                @endif 
                            </div>
                        </td>
                    </tr>

                    <!--Modal detalle-->
                    <div class="modal fade" id="verModal{{$index}}" tabindex="-1" aria-labelledby="modalLabel{{$index}}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{$index}}">Detalle del Equipo</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Modelo:</strong> {{ $item->modelo }}</p>
                                    <p><strong>Número de Serie:</strong> {{ $item->numero_serie ?? 'No disponible' }}</p>
                                    <p><strong>Contenido Etiqueta:</strong> {{ $item->contenido_etiqueta ?? 'No disponible' }}</p>
                                    <p><strong>Marca:</strong> {{ $item->marca && $item->marca->caracteristica ? $item->marca->caracteristica->nombre : 'Sin marca' }}</p>
                                    <p><strong>Estado:</strong> {{ $item->estado_equipo ? $item->estado_equipo->nombre : 'Sin estado' }}</p>
                                    <p><strong>Categorías:</strong></p>
                                    @foreach ($item->categorias as $categoria)
                                        <span class="badge bg-secondary">{{ $categoria->caracteristica->nombre }}</span>
                                    @endforeach
                                    <p class="mt-3"><strong>Detalle:</strong> {{ $item->detalle ?? 'Sin detalle' }}</p>

                                    @if ($item->img_path)
                                        <div class="mt-3">
                                            <strong>Imagen:</strong>
                                            <img src="{{ asset('img/equipos/' . $item->img_path) }}" alt="Imagen equipo" class="img-thumbnail mt-2" style="width: 300px; height: 200px; object-fit: cover;">
                                        </div>
                                    @else
                                        <p class="mt-3"><em>No hay imagen disponible.</em></p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de confirmación eliminar/restaurar -->
                    <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $item->estado_equipo->id != 7 ? '¿Seguro que quieres eliminar el equipo?' : '¿Seguro que quieres restaurar el equipo?' }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('equipos.destroy',['equipo'=>$item->id]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn {{ $item->estado_equipo->id != 7 ? 'btn-danger' : 'btn-primary' }}">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para escáner físico -->
                    <div id="barcodeModal" class="scanner-modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeBarcodeModal()">&times;</span>
                            <h3>Escanear con lector de código</h3>
                            <input type="text" id="barcodeInput" class="form-control" placeholder="Escanee el código aquí">
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/scannerBusqueda.js') }}"></script>
@endpush
