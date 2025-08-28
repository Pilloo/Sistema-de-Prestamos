@extends('template')

@section('title','Inventario de Equipos')

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
            <h2>Inventario de Equipos</h2>
        </div>

        <div class="table-responsive">
            <table id="tablaInventario" class="table align-middle">
                <thead>
                    <tr>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categorías</th>
                        <th>Cantidad Total</th>
                        <th>Cantidad Disponible</th>
                        <th>Lotes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventario as $item)
                    <tr>
                        <td>{{ $item['modelo'] }}</td>
                        <td>{{ $item['marca'] }}</td>
                        <td>
                            @php
                                $categoriasUnicas = collect();
                                foreach($item['lotes'] as $lote) {
                                    foreach($lote->categorias as $categoria) {
                                        $nombreCat = $categoria->caracteristica->nombre ?? 'Sin categoría';
                                        if (!$categoriasUnicas->contains($nombreCat)) {
                                            $categoriasUnicas->push($nombreCat);
                                        }
                                    }
                                }
                            @endphp
                            @forelse($categoriasUnicas as $catNombre)
                                <span class="badge bg-secondary">{{ $catNombre }}</span>
                            @empty
                                <span class="badge bg-secondary">Sin categoría</span>
                            @endforelse
                        </td>
                        <td>{{ $item['cantidad_total'] }}</td>
                        <td>{{ $item['cantidad_disponible'] }}</td>
                        <td>
                            @foreach($item['lotes'] as $lote)
                                <span class="badge bg-info">Lote #{{ $lote->id }}</span>
                            @endforeach
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
  $('#tablaInventario').DataTable({
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
      { "orderable": false, "targets": [2, 5] }
    ]
  });
});
</script>
@endpush
