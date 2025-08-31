@extends('template')

@section('title','Solicitar Préstamo de Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/categorias.css') }}" rel="stylesheet" />
<style>
    .product-card {
        width: 260px;
        border-radius: 1rem;
        background-color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
    }
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        background: #f9f9f9;
    }
    .product-card-body {
        padding: 1rem 1.2rem;
    }
    .btn-request,
    .btn-not-available {
        width: 100%;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.5rem;
        transition: all 0.3s;
    }
    .btn-request {
        border: 2px solid #315cfd;
        background: white;
        color: #315cfd;
    }
    .btn-request:hover { background: #315cfd; color: white; }
    .btn-not-available {
        border: 2px solid #c50505;
        background: white;
        color: #c50505;
    }
    .btn-not-available:hover { background: #c50505; color: white; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="background-color: #f1f1f1ff;">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <h3 class="fw-semibold mb-3 mb-md-0">Solicitar Préstamo de Equipos</h3>
                <a href="{{ route('solicitud.cart') }}" class="btn btn-outline-primary position-relative">
                    <i class="fas fa-shopping-cart"></i> Ver Carrito
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count(session('equipment_cart', [])) }}
                    </span>
                </a>
            </div>

            @can('gestionar solicitudes')
            <form action="{{ route('solicitud.addToCart') }}" method="POST" class="mb-4">
                @csrf
                    <input type="hidden" name="user_id" value="default_user_id"> <!-- Placeholder for user_id -->
            </form>
            @endcan

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex flex-wrap gap-4 justify-content-center">
                @foreach($equiposAgrupados as $grupo)
                <div class="product-card">
                    <!-- Mostrar imagen del primer lote del grupo -->
                    @php $primerLote = $grupo['lotes'][0]; @endphp
                    <img src="{{ asset('img/equipos/' . $primerLote->img_path) }}" 
                         alt="{{ $grupo['modelo'] }}">
                    <div class="product-card-body">
                        <h5 class="text-truncate mb-1 fw-semibold">{{ $grupo['modelo'] }}</h5>
                        <p class="text-muted mb-1">Categoría: {{ $grupo['categoria'] }}</p>
                        <p class="text-muted mb-1">Marca: {{ $grupo['marca'] }}</p>
                        <p class="text-muted mb-3">Disponibles: {{ $grupo['cantidad_disponible'] }}</p>

                        @if($grupo['cantidad_disponible'] > 0)
                        <button type="button" class="btn btn-request open-modal"
                                data-bs-toggle="modal" data-bs-target="#cartModal"
                                data-lotes='@json($grupo['lotes'])'
                                data-title="{{ $grupo['modelo'] }}"
                                data-max="{{ $grupo['cantidad_disponible'] }}">
                            Solicitar
                        </button>
                        @else
                        <button type="button" class="btn btn-not-available" disabled>No disponible</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form action="{{ route('solicitud.addToCart') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold" id="cartModalLabel">Agregar al Carrito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">¿Deseas agregar <span id="modalProductTitle" class="fw-bold">este equipo</span> a tu solicitud?</p>

                    <div class="mb-3">
                        <label for="loteSelect" class="form-label fw-medium">Selecciona Lote</label>
                        <select class="form-select" id="loteSelect" name="lote_id" required>
                            <!-- Opciones generadas por JS -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantityInput" class="form-label fw-medium">Cantidad</label>
                        <input type="number" class="form-control" id="quantityInput" name="cantidad" min="1" value="1" required>
                        <small class="text-muted">Máximo disponible: <span id="maxQuantity">0</span></small>
                    </div>

                    <div class="mb-3">
                        <label for="dueDateInput" class="form-label fw-medium">Fecha de devolución</label>
                        <input type="date" class="form-control" id="dueDateInput" name="fecha_limite" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-5">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cartModal = document.getElementById('cartModal');
    cartModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const lotes = JSON.parse(button.getAttribute('data-lotes'));
        const title = button.getAttribute('data-title');
        let maxQuantity = 0;

        // Generar opciones de lotes en el select
        const loteSelect = document.getElementById('loteSelect');
        loteSelect.innerHTML = '';
        lotes.forEach(function(lote) {
            const option = document.createElement('option');
            option.value = lote.id;
            option.textContent = 'Lote #' + lote.id + ' - Disponibles: ' + lote.cantidad_disponible;
            option.setAttribute('data-max', lote.cantidad_disponible);
            loteSelect.appendChild(option);
            if (lote.cantidad_disponible > maxQuantity) maxQuantity = lote.cantidad_disponible;
        });

        document.getElementById('modalProductTitle').textContent = title;
        document.getElementById('quantityInput').max = lotes[0].cantidad_disponible;
        document.getElementById('maxQuantity').textContent = lotes[0].cantidad_disponible;

        // Actualizar max cantidad al cambiar lote
        loteSelect.addEventListener('change', function() {
            const selected = loteSelect.options[loteSelect.selectedIndex];
            const loteMax = selected.getAttribute('data-max');
            document.getElementById('quantityInput').max = loteMax;
            document.getElementById('maxQuantity').textContent = loteMax;
        });
        loteSelect.dispatchEvent(new Event('change'));

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dueDateInput').min = today;
    });
});
</script>
@endpush
