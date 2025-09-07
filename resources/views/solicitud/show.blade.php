@extends('template')

@section('title','Detalle de Solicitud de Préstamo')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>

    body {
        background-image: url("/img/template/fondoPrincipal.jpg");
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    .card-custom {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .section-title {
        font-weight: 600;
        color: #495057;
    }
    .badge-status {
        font-size: 0.85rem;
        padding: 0.5em 0.8em;
        border-radius: 30px;
    }
    .btn-back {
        border-radius: 30px;
        padding: 0.4rem 1rem;
    }
    .btn-accept, .btn-reject {
        border-radius: 30px;
        padding: 0.5rem 1.2rem;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .btn-accept { background: #28a745; color: white; border: none; }
    .btn-accept:hover { background: #218838; }
    .btn-reject { background: #dc3545; color: white; border: none; }
    .btn-reject:hover { background: #c82333; }
    table th {
        font-size: 0.9rem;
        color: #6c757d;
    }
    table td {
        font-size: 0.95rem;
    }
</style>
@endpush

@section('content')
<body>
    <div class="container py-5">
        <div class="card card-custom">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Solicitud #{{ $solicitud->id }}</h3>
                    @if(auth()->user()->can('gestionar solicitudes'))
                        <a href="{{ route('solicitud.index') }}" class="btn btn-secondary btn-back">Volver</a>
                    @else
                        <a href="{{ route('solicitud.misSolicitudes') }}" class="btn btn-secondary btn-back">Volver</a>
                    @endif
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="section-title">Solicitante</h6>
                        <p class="mb-0">{{ $solicitud->solicitante->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title">Estado</h6>
                        <span class="badge badge-status bg-{{ $solicitud->estadoSolicitud->color ?? 'secondary' }}">
                            {{ $solicitud->estadoSolicitud->nombre ?? 'N/A' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title">Aprobado por</h6>
                        <p class="mb-0">{{ $solicitud->tecnicoAprobador->name ?? 'Sin aprobar' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title">Fecha Solicitud</h6>
                        <p class="mb-0">{{ $solicitud->fecha_solicitud->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="section-title">Fecha Límite</h6>
                        <p class="mb-0">{{ $solicitud->fecha_limite_solicitada->format('d/m/Y') }}</p>
                    </div>
                </div>

                @if($solicitud->detalle)
                <div class="mb-4">
                    <h6 class="section-title">Detalle</h6>
                    <p class="mb-0">{{ $solicitud->detalle }}</p>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                    <h5 class="fw-bold">Equipos Solicitados</h5>
                    @can('gestionar solicitudes')
                        @if($solicitud->id_estado_solicitud == 1)
                        <div>
                            <form action="{{ route('solicitud.aceptar', $solicitud->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-accept">Aceptar</button>
                            </form>
                            <form action="{{ route('solicitud.rechazar', $solicitud->id) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                <button type="submit" class="btn btn-reject">Rechazar</button>
                            </form>
                        </div>
                        @elseif($solicitud->id_estado_solicitud == 2)
                        <div>
                            <form action="{{ route('solicitud.devolver', $solicitud->id) }}" method="POST" class="d-inline">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">Estado de devolución por equipo</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Modelo</th>
                                                    <th>Serial</th>
                                                    <th>Estado devolución</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($solicitud->equipos as $equipo)
                                                <tr>
                                                    <td>{{ $equipo->id }}</td>
                                                    <td>{{ $equipo->lote->modelo ?? 'N/A' }}</td>
                                                    <td>{{ $equipo->numero_serie ?? 'N/A' }}</td>
                                                    <td>
                                                        <select name="estado_equipo_id[{{ $equipo->id }}]" class="form-select" required>
                                                            @foreach(\App\Models\EstadoEquipo::all() as $estado)
                                                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning">Devolver préstamo</button>
                            </form>
                        </div>
                        @endif
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Número de Serie</th>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Categoría</th>
                                <th>Estado Equipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($solicitud->equipos as $equipo)
                            <tr>
                                <td>{{ $equipo->id }}</td>
                                <td>{{ $equipo->numero_serie }}</td>
                                <td>{{ $equipo->lote->modelo ?? 'N/A' }}</td>
                                <td>{{ $equipo->lote->marca->caracteristica->nombre ?? 'Sin marca' }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        @forelse ($equipo->lote->categorias as $listaCategorias)
                                            {{ $listaCategorias->caracteristica->nombre ?? 'Sin categoría' }}
                                        @empty
                                            Sin categoría
                                        @endforelse
                                    </span>
                                </td>
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
</body>
@endsection
