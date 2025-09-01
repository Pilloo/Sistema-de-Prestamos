@extends('template')

@section('title','Secciones')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/secciones.css') }}" rel="stylesheet" />
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

<body>
    <div class="container py-5">
        <div class="panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Listado de Secciones</h2>
                <button class="btn btn-agregar" data-bs-toggle="modal" data-bs-target="#crearSeccionModal">+ Agregar Sección</button>
            </div>

            <div class="table-responsive">
                <table id="tablaSecciones" class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($secciones as $seccion)
                        <tr>
                            <td>{{ $seccion->id }}</td>    
                            <td>{{ $seccion->caracteristica->nombre }}</td>
                            <td>
                                @if ($seccion->caracteristica->estado == 1)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Eliminado</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-editar btn-sm" data-bs-toggle="modal" data-bs-target="#editarSeccionModal-{{ $seccion->id }}">Editar</button>
                                @if ($seccion->caracteristica->estado == 1)
                                    <button type="button" class="btn btn-eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $seccion->id }}">Eliminar</button>
                                @else
                                    <button type="button" class="btn btn-restaurar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $seccion->id }}">Restaurar</button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Editar Sección -->
                        <div class="modal fade" id="editarSeccionModal-{{ $seccion->id }}" tabindex="-1" aria-labelledby="editarSeccionLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('secciones.update', ['seccione' => $seccion]) }}" method="post">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Sección</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="nombreSeccion">Nombre de la sección</label>
                                            <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $seccion->caracteristica->nombre) }}">
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
</body>

@include('secciones.create')

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaSecciones').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true,
        "paging": true,
        "language": {
            "search": "Buscar:",
            "lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ secciones",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "zeroRecords": "No se encontraron secciones"
        },
        "columnDefs": [
            { "orderable": false, "targets": 3 }
        ]
    });
});
</script>
@endpush
