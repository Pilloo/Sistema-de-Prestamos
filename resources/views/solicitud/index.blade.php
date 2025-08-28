@extends('template')

@section('title','Solicitudes de Préstamo')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables CSS para Bootstrap 5 -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/categorias.css') }}" rel="stylesheet" />
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

<style>
  body {
    font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
  }
</style>

<div class="container py-5">
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Listado de Solicitudes de Préstamo</h2>
            <!-- Si necesitas un botón de agregar -->
            {{-- <button class="btn btn-agregar" data-bs-toggle="modal" data-bs-target="#crearSolicitudModal">+ Nueva Solicitud</button> --}}
        </div>

        <div class="table-responsive">
            <table id="tablaSolicitudes" class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Límite</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->id }}</td>
                        <td>{{ $solicitud->solicitante->name ?? 'N/A' }}</td>
                        <td>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</td>
                        <td>{{ $solicitud->fecha_limite_solicitada->format('d/m/Y') }}</td>
                        <td>{{ $solicitud->estadoSolicitud->nombre ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('solicitud.show', $solicitud->id) }}" class="btn btn-info btn-sm">Ver Detalle</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery necesario para DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
  $('#tablaSolicitudes').DataTable({
    "scrollY": "400px",
    "scrollCollapse": true,
    "paging": true,
    "language": {
      "search": "Buscar:",
      "lengthMenu": "Mostrar _MENU_ registros",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ solicitudes",
      "paginate": {
        "next": "Siguiente",
        "previous": "Anterior"
      },
      "zeroRecords": "No se encontraron solicitudes"
    },
    "columnDefs": [
      { "orderable": false, "targets": 5 }
    ]
  });
});
</script>
@endpush
