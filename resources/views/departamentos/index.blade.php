@extends('template')

@section('title','Departamentos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        background-image: url(img/130.jpg) !important;
        background-size: cover;
        background-repeat: no-repeat;
        font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
    }
    .panel {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 18px 32px rgba(25, 24, 24, 0.452);
    }
    .panel h2 {
        font-weight: 700;
        font-size: 1.4rem;
    }
    .table {
        border-radius: 15px;
        overflow: hidden;
    }
    thead {
        background: #f0f2f5;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.8em;
        border-radius: 12px;
    }
    .btn-sm {
        border-radius: 8px;
        font-weight: 500;
    }
    tbody tr:hover {
        background-color: #f9fafc;
        transition: background 0.2s;
    }
    .btn-agregar {
        background-color: #1e73be !important;
        color: #fff !important;
    }
    .btn-editar {
        background-color: #ffc107 !important;
        color: #000 !important;
    }
    .btn-eliminar {
        background-color: #b85a05 !important;
        color: #fff !important;
    }
    .btn-restaurar {
        background-color: #329b46 !important;
        color: #fff !important;
    }
</style>
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
            <h2>Listado de Departamentos</h2>
            <button class="btn btn-agregar" data-bs-toggle="modal" data-bs-target="#crearDepartamentoModal">+ Agregar Departamento</button>
        </div>

        <div class="table-responsive">
            <table id="tablaDepartamentos" class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Departamento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departamentos as $departamento)
                    <tr>
                        <td>{{ $departamento->id }}</td>    
                        <td>{{ $departamento->caracteristica->nombre }}</td>
                        <td>
                            @if ($departamento->caracteristica->estado == 1)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Eliminado</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-editar btn-sm" data-bs-toggle="modal" data-bs-target="#editarDepartamentoModal-{{ $departamento->id }}">Editar</button>
                            @if ($departamento->caracteristica->estado == 1)
                                <button type="button" class="btn btn-eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $departamento->id }}">Eliminar</button>
                            @else
                                <button type="button" class="btn btn-restaurar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $departamento->id }}">Restaurar</button>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarDepartamentoModal-{{ $departamento->id }}" tabindex="-1" aria-labelledby="editarDepartamentoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('departamentos.update', $departamento->id) }}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Departamento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="nombreDepartamento">Nombre del departamento</label>
                                        <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $departamento->caracteristica->nombre) }}">
                                        @error('nombre')
                                            <small class="text-danger">{{ '*'.$message }}</small>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('departamentos.create')

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaDepartamentos').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true,
        "paging": true,
        "language": {
            "search": "Buscar:",
            "lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ departamentos",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "zeroRecords": "No se encontraron departamentos"
        },
        "columnDefs": [
            { "orderable": false, "targets": 3 }
        ]
    });
});
</script>
@endpush
