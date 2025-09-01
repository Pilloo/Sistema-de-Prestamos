@extends('template')

@section('title', 'Roles')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/roles.css') }}" rel="stylesheet" />

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

<body>
    <div class="container py-5">
        <div class="panel">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Listado de Roles</h2>
                <a href="{{ route('roles.create') }}" class="btn btn-agregar">+ Añadir nuevo rol</a>
            </div>

            <div class="table-responsive">
                <table id="tablaRoles" class="table align-middle">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('roles.edit', ['role' => $item]) }}" class="btn btn-editar btn-sm">Editar</a>
                                    <button type="button" class="btn btn-eliminar btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $item->id }}">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modales de confirmación --}}
        @foreach ($roles as $item)
        <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ¿Seguro que deseas eliminar el rol <strong>{{ $item->name }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('roles.destroy', ['role' => $item->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</body>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaRoles').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true,
        "paging": true,
        "language": {
            "search": "Buscar:",
            "lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ roles",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "zeroRecords": "No se encontraron roles"
        },
        "columnDefs": [
            { "orderable": false, "targets": 1 }
        ]
    });
});
</script>

@endpush
