@extends('template')

@section('title', 'Lotes de Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/lotes.css') }}" rel="stylesheet" />

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
            <h2>Lotes de Equipos</h2>
            <a href="{{ route('lotes.create') }}" class="btn btn-agregar">+ Nuevo Lote</a>
        </div>

        <div class="table-responsive">
            <table id="tablaLotes" class="table align-middle ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categorías</th>
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
                            @foreach ($lote->categorias as $categoria)
                                <span class="badge bg-secondary">{{ $categoria->caracteristica->nombre }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('lotes.show', $lote->id) }}" class="btn btn-sm btn-ver">Ver</a>
                            <a href="{{ route('lotes.edit', $lote->id) }}" class="btn btn-sm btn-editar">Editar</a>
                            <form action="{{ route('lotes.destroy', $lote->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-eliminar" onclick="return confirm('¿Eliminar lote?')">Eliminar</button>
                            </form>
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
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaLotes').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true,
        "paging": true,
        "language": {
            "search": "Buscar:",
            "lengthMenu": "Mostrar _MENU_ registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ lotes",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            },
            "zeroRecords": "No se encontraron lotes"
        },
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ]
    });
});
</script>
@endpush
