@extends('template')

@section('title','Categorias')

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
    background-color: rgba(255, 0, 0, 0.5); /* Color rojo semitransparente */
       background-image: url('{{ asset('img/template/fondoPrincipal.jpg') }}') !important;
       background-size: cover;
       background-repeat: no-repeat;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
  }
</style>

<div class="container py-5">
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Listado de Categorías</h2>
            <button class="btn btn-agregar" data-bs-toggle="modal" data-bs-target="#crearCategoriaModal">+ Agregar Categoría</button>
        </div>

        <!-- Buscador (queda oculto porque DataTables ya tiene buscador integrado) -->
        {{-- <div class="mb-3">
            <input type="text" id="buscar" class="form-control" placeholder="Buscar Categoría...">
        </div> --}}

        <div class="table-responsive">
            <table id="tablaCategorias" class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->caracteristica->nombre }}</td>
                        <td>
                            @if($categoria->caracteristica->estado == 1)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-editar btn-sm" data-bs-toggle="modal" data-bs-target="#editarCategoriaModal-{{ $categoria->id }}">Editar</button>
                            @if ($categoria->caracteristica->estado == 1)
                                <button class="btn btn-eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $categoria->id }}">Eliminar</button>
                            @else
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $categoria->id }}">Restaurar</button>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Editar -->
                    <div class="modal fade" id="editarCategoriaModal-{{ $categoria->id }}" tabindex="-1" aria-labelledby="editarCategoriaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('categorias.update', $categoria->id) }}" method="post">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Categoría</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="nombreCategoria">Nombre de la categoría</label>
                                        <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $categoria->caracteristica->nombre) }}">
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

@include('categorias.create')

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
  $('#tablaCategorias').DataTable({
    "scrollY": "400px",
    "scrollCollapse": true,
    "paging": true,
    "language": {
      "search": "Buscar:",
      "lengthMenu": "Mostrar _MENU_ registros",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ categorías",
      "paginate": {
        "next": "Siguiente",
        "previous": "Anterior"
      },
      "zeroRecords": "No se encontraron categorías"
    },
    "columnDefs": [
      { "orderable": false, "targets": 3 }
    ]
  });
});
</script>
@endpush
