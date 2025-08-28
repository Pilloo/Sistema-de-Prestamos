@extends('template')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <h4 class="mb-4">Listado de Préstamos</h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Solicitante</th>
                            <th>Aprobador</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prestamos as $prestamo)
                        <tr>
                            <td>{{ $prestamo->id }}</td>
                            <td>{{ $prestamo->solicitante->name ?? 'N/A' }}</td>
                            <td>{{ $prestamo->aprobador->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-secondary">{{ $prestamo->estadoPrestamo->nombre ?? 'N/A' }}</span></td>
                            <td>
                                <a href="{{ route('prestamos.show', $prestamo->id) }}" class="btn btn-sm btn-primary">Ver Detalle</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay préstamos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
