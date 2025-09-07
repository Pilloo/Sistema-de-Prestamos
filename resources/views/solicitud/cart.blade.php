@extends('template')

@section('title','Solicitud de Préstamo')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/cart.css') }}" rel="stylesheet" />
@endpush

@section('content')
<body>
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="card card-custom">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Solicitud de Préstamo</h2>
                <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary btn-pill position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger badge-cart">
                        {{ count($cart) }}
                    </span>
                </a>
            </div>

            @if (empty($cart))
                <div class="text-center empty-cart">
                    <p class="fs-5 mb-3">El carrito está vacío</p>
                    <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary btn-pill">
                        <i class="fas fa-plus"></i> Añadir
                    </a>
                </div>
            @else
                <div class="table-responsive mb-4">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Artículo</th>
                                <th>Serial</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $index => $item)
                            <tr>
                                <td>{{ $item['modelo'] }} ({{ $item['marca'] }})</td>
                                <td>{{ $item['numero_serie'] ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('solicitud.removeFromCart', $index) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-pill"
                                                onclick="return confirm('¿Está seguro?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('solicitud.store') }}" method="POST">
                    @csrf
                    @can('gestionar solicitudes')
                    <div class="mb-3">
                        <label for="userSearch" class="form-label">Buscar usuario</label>
                        <input type="text" id="userSearch" class="form-control mb-2" placeholder="Nombre o correo...">
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
                        <label for="detalle" class="form-label fw-semibold">Detalles de la solicitud (opcional)</label>
                        <textarea class="form-control rounded-4" id="detalle" name="detalle" rows="2"
                                placeholder="Detalles adicionales de la solicitud"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label fw-semibold">Fecha de devolución</label>
                        <input type="date" class="form-control rounded-pill" id="fecha_limite" name="fecha_limite"
                            min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <a href="{{ route('solicitud.create') }}" class="btn btn-outline-primary btn-pill">
                            <i class="fas fa-plus"></i> Añadir más
                        </a>
                        <a href="{{ route('solicitud.clearCart') }}" class="btn btn-outline-danger btn-pill"
                        onclick="return confirm('¿Seguro que quiere limpiar el carrito?')">
                            <i class="fas fa-trash"></i> Limpiar carrito
                        </a>
                        <button type="submit" class="btn btn-outline-success btn-pill">
                            <i class="fas fa-paper-plane"></i> Enviar solicitud
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const userSearch = document.getElementById('userSearch');
        const userSelect = document.getElementById('user_id');
        if (userSearch && userSelect) {
            userSearch.addEventListener('input', function() {
                const search = userSearch.value.toLowerCase();
                Array.from(userSelect.options).forEach(function(option, idx) {
                    if (idx === 0) return; // No filtrar el placeholder
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(search) ? '' : 'none';
                });
            });
        }
    });
    </script>
</body>
@endsection
