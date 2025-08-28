@extends('template')
@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Detalle de Solicitud de Préstamo #{{ $solicitud->id }}</h4>
                <a href="{{ route('solicitud.index') }}" class="btn btn-secondary btn-sm">Volver</a>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Solicitante</h6>
                    <p>{{ $solicitud->solicitante->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Estado</h6>
                    <span class="badge bg-{{ $solicitud->estadoSolicitud->color ?? 'secondary' }}">
                        {{ $solicitud->estadoSolicitud->nombre ?? 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-muted">Fecha Solicitud</h6>
                    <p>{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Fecha Límite</h6>
                    <p>{{ $solicitud->fecha_limite_solicitada->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="text-muted">Detalle</h6>
                <p>{{ $solicitud->detalle }}</p>
            </div>

            <h5 class="fw-bold mt-4 mb-3">Equipos Solicitados</h5>

            @if($solicitud->id_estado_solicitud == 1)
            <div class="mb-3">
                <form action="{{ route('solicitud.aceptar', $solicitud->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </form>
                <form action="{{ route('solicitud.rechazar', $solicitud->id) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-danger">Rechazar</button>
                </form>
            </div>
            @endif
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
                        @forelse($solicitud->equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->id }}</td>
                            <td>{{ $equipo->numero_serie }}</td>
                            <td>{{ $equipo->lote->modelo ?? 'N/A' }}</td>
                            <td>{{ $equipo->lote->marca->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $equipo->estado_equipo->nombre ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay equipos registrados en esta solicitud.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
