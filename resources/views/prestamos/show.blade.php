@extends('template')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <h4 class="mb-4">Detalle del Préstamo #{{ $prestamo->id }}</h4>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Solicitante</h6>
                    <p>{{ $prestamo->solicitante->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Aprobador</h6>
                    <p>{{ $prestamo->aprobador->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Estado</h6>
                    <span class="badge bg-secondary">{{ $prestamo->estadoPrestamo->nombre ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Solicitud Asociada</h6>
                    <a href="{{ route('solicitud.show', $prestamo->solicitud->id) }}" class="btn btn-sm btn-info">Ver Solicitud</a>
                </div>
            </div>
            <hr>
            <h5 class="fw-bold mt-4 mb-3">Equipos Prestados</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Número de Serie</th>
                            <th>Modelo</th>
                            <th>Marca</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prestamo->solicitud->equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->id }}</td>
                            <td>{{ $equipo->numero_serie }}</td>
                            <td>{{ $equipo->lote->modelo ?? 'N/A' }}</td>
                            <td>{{ $equipo->lote->marca->nombre ?? 'N/A' }}</td>
                            <td><span class="badge bg-secondary">{{ $equipo->estado_equipo->nombre ?? 'N/A' }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay equipos registrados en este préstamo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
