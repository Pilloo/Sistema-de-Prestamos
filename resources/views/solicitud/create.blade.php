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
        background-color: #f9f9f9;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        background: #fff;
    }
    .product-card-body {
        padding: 1rem;
    }
    .btn-request {
        width: 100%;
        border: 2px solid #315cfd;
        border-radius: 30px;
        background: white;
        font-size: 0.9rem;
        font-weight: 500;
        color: #315cfd;
        transition: all 0.3s;
    }
    .btn-request:hover { background: #315cfd; color: white; }
    .btn-not-available {
        width: 100%;
        border: 2px solid #c50505;
        border-radius: 30px;
        background: white;
        font-size: 0.9rem;
        font-weight: 500;
        color: #c50505;
        transition: all 0.3s;
    }
    .btn-not-available:hover { background: #c50505; color: white; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Solicitar Préstamo de Equipos</h2>
            <a href="{{ route('solicitud.cart') }}" class="btn btn-outline-primary position-relative">
                <i class="fas fa-shopping-cart"></i> Ver Carrito
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ count(session('equipment_cart', [])) }}
                </span>
            </a>
        </div>

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
            @foreach($equipos as $equipment)
            <div class="product-card">
                <img src="{{ $equipment->img_path ?? 'https://storage.googleapis.com/a1aa/image/c7d51ac1-0253-48bc-7020-40fff88aee8a.jpg' }}" 
                     alt="{{ $equipment->modelo }}">
                <div class="product-card-body">
                    <h5 class="text-truncate mb-1">{{ $equipment->modelo }}</h5>
                    <p class="text-muted mb-1">Categoría: {{ $equipment->categorias->first()->nombre ?? 'Sin categoría' }}</p>
                    <p class="text-muted mb-1">Marca: {{ $equipment->marca->nombre }}</p>
                    <p class="text-muted mb-2">Disponibles: {{ $equipment->cantidad_disponible }}</p>

                    @if($equipment->cantidad_disponible > 0)
                    <button type="button" class="btn btn-request open-modal"
                            data-bs-toggle="modal" data-bs-target="#cartModal"
                            data-id="{{ $equipment->id }}"
                            data-title="{{ $equipment->modelo }}"
                            data-max="{{ $equipment->cantidad_disponible }}">
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

<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('solicitud.addToCart') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Agregar al Carrito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">¿Deseas agregar <span id="modalProductTitle" class="fw-bold">este equipo</span> a tu solicitud?</p>

                    <input type="hidden" name="lote_id" id="modalLoteId">

                    <div class="mb-3">
                        <label for="quantityInput" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="quantityInput" name="cantidad" min="1" value="1" required>
                        <small class="text-muted">Máximo disponible: <span id="maxQuantity">0</span></small>
                    </div>

                    <div class="mb-3">
                        <label for="dueDateInput" class="form-label">Fecha de devolución</label>
                        <input type="date" class="form-control" id="dueDateInput" name="fecha_limite" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
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
        const loteId = button.getAttribute('data-id');
        const title = button.getAttribute('data-title');
        const maxQuantity = button.getAttribute('data-max');

        document.getElementById('modalProductTitle').textContent = title;
        document.getElementById('modalLoteId').value = loteId;
        document.getElementById('quantityInput').max = maxQuantity;
        document.getElementById('maxQuantity').textContent = maxQuantity;

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dueDateInput').min = today;
    });
});
</script>
@endpush
