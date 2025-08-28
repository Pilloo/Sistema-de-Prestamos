@extends('template')

@section('title','Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/equipos.css') }}" rel="stylesheet" />

@endpush

@section('content')

@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: "top-end",
    icon: "success",
    title: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true
});
</script>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="container py-5">
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Listado de Equipos</h2>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-editar" onclick="openBarcodeModal()">🔍 Búsqueda con lector</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="tablaEquipos" class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Equipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="equipoBody">
                    @foreach($equipos as $index => $item)
                    <tr>
                        <td>{{ $item->id }}</td>    
                        <td>{{ $item->lote ? $item->lote->modelo : 'Sin lote' }}</td>
                        <td>
                            <span class="badge {{ $item->estado_equipo && $item->estado_equipo->nombre!='Baja / Retirado' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->estado_equipo ? $item->estado_equipo->nombre : 'Sin estado' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('equipos.show', $item->id) }}" class="btn btn-ver btn-sm">Ver</a>
                                <form action="{{ route('equipos.edit', ['equipo' => $item]) }}" method="get">
                                    <button type="submit" class="btn btn-editar btn-sm">Editar</button>
                                </form> 
                                @if($item->estado_equipo->nombre!='Baja / Retirado')
                                    <button type="button" class="btn btn-eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Eliminar</button>
                                @else
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}">Restaurar</button>
                                @endif 
                            </div>
                        </td>
                    </tr>



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
                                        <button type="submit" class="btn {{ $item->estado_equipo->id != 7 ? 'btn-eliminar' : 'btn-success' }}">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para escáner físico -->
<div id="barcodeModal" class="scanner-modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeBarcodeModal()">&times;</span>
        <h3>Escanear con lector de código</h3>
        <input type="text" id="barcodeInput" class="form-control" placeholder="Escanee el código aquí">
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
  $('#tablaEquipos').DataTable({
    "scrollY": "400px",
    "scrollCollapse": true,
    "paging": true,
    "language": {
      "search": "Buscar:",
      "lengthMenu": "Mostrar _MENU_ registros",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ equipos",
      "paginate": {
        "next": "Siguiente",
        "previous": "Anterior"
      },
      "zeroRecords": "No se encontraron equipos"
    },
    "columnDefs": [
      { "orderable": false, "targets": 3 }
    ]
  });
});

// Funciones para el modal del escáner
function openBarcodeModal(){
    document.getElementById("barcodeModal").style.display="block";
}
function closeBarcodeModal(){
    document.getElementById("barcodeModal").style.display="none";
}
</script>
@endpush
