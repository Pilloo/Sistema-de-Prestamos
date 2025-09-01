@extends('template')
@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow rounded-4 p-4 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Solicitud de Prestamo</h2>
            <div class="position-relative">
                <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary rounded-pill">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count($cart) }}
                    </span>
                </a>
            </div>
        </div>

        @if (empty($cart))
            <div class="text-center py-5 text-muted">
                <p class="fs-5">El Carro Esta Vacio</p>
                <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary rounded-pill mt-3">
                    <i class="fas fa-plus"></i> Añadir
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Artículos</th>
                            <th>Serial</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $index => $item)
                        <tr>
                            <td>{{ $item['modelo'] }} ({{ $item['marca'] }})</td>
                            <td>{{ $item['numero_serie'] ?? 'N/A' }}</td>
                            <td>
                                <form action="{{ route('solicitud.updateCart', $index) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1"
                                           max="{{ $item['max_disponible'] }}" class="form-control form-control-sm rounded-pill">
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">Actualizar</button>
                                </form>
                            </td>
                            <td>
                                <!-- La fecha de devolución ahora se especifica para toda la solicitud abajo -->
                            </td>
                            <td>
                                <form action="{{ route('solicitud.removeFromCart', $index) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill"
                                            onclick="return confirm('Esta usted seguro?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <form action="{{ route('solicitud.store') }}" method="POST" class="mt-4">
                @csrf
                    @can('gestionar solicitudes')
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Seleccionar usuario solicitante</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="" disabled selected>Seleccione un usuario</option>
                            @foreach(App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    @endcan
                <div class="mb-3">
                    <label for="detalle" class="form-label fw-semibold">Detalles de Solicitud (Optional)</label>
                    <textarea class="form-control rounded-4" id="detalle" name="detalle" rows="2"
                              placeholder="Detalles adicionales de la solicitud"></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha_limite" class="form-label fw-semibold">Fecha de devolución</label>
                    <input type="date" class="form-control rounded-pill" id="fecha_limite" name="fecha_limite" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="d-flex justify-content-between gap-2 flex-wrap">
                    <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary rounded-pill">
                        <i class="fas fa-plus"></i> Añadir mas
                    </a>
                    <a href="{{ route('solicitud.clearCart') }}" class="btn btn-outline-danger rounded-pill"
                       onclick="return confirm('Seguro que quiere limpiar el carrito?')">
                        <i class="fas fa-trash"></i> Limpiar Carrito
                    </a>
                    <button type="submit" class="btn btn-outline-success rounded-pill">
                        <i class="fas fa-paper-plane"></i> Enviar Solicitud
                    </button>
                    
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
