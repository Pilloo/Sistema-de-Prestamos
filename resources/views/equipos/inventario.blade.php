@extends('template')

@section('title','Inventario de Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container py-5">
    <div class="panel">
        <h2 class="mb-4">Inventario de Equipos</h2>
        <div class="table-responsive">
            <table class="table align-middle table-bordered">
                <thead>
                    <tr>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Categoría</th>
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
                        <td>{{ $item['categoria'] }}</td>
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
