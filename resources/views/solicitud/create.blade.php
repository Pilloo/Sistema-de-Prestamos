@extends('template')
@section('content')
<div class="container mt-4">
    <h2>Solicitar Préstamo de Equipos</h2>
    <div class="mb-3">
        <h5>Carrito de equipos</h5>
        <a href="{{ route('solicitud.cart') }}" class="btn btn-secondary">Ver Carrito</a>
    </div>
</div>
<!-- El flujo de agregar al carrito ahora es mediante el modal y el backend -->
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title>Agregar Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>

    <style>
        body { background-color: white; }

        .card-custom {
            width: 260px;
            height: 260px;
            border-radius: 1rem;
            background-color: #f9f9f9;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 0;
        }

        .card-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: white;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .product-card:hover img { opacity: 0; }

        .product-card-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 1rem;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;
        }

        .product-card:hover .product-card-content { opacity: 1; }

        .btn-custom {
            padding: 0.4rem 1rem;
            border: 2px solid #315cfd;
            border-radius: 30px;
            background: white;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            color: #315cfd;
            width: 100%;
            margin-top: auto;
        }

        .btn-custom:hover { background: #315cfd; color: white; }

        .btn-custom1 {
            padding: 0.4rem 1rem;
            border: 2px solid #315cfd;
            border-radius: 30px;
            background: white;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            color: #315cfd;
        }

        .btn-custom1:hover { background: #315cfd; color: white; }

        .btn-NoDisponible {
            padding: 0.4rem 1rem;
            border: 2px solid #c50505;
            border-radius: 30px;
            background: white;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            color: #c50505;
            width: 100%;
            margin-top: auto;
        }

        .btn-NoDisponible:hover { background: #c50505; color: white; }

        .card-title {
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-text {
            font-size: 0.85rem;
            color: #7a6f6f;
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 576px) {
            .card-custom {
                width: 100%;
                height: auto;
                margin: 0 auto;
            }
            .product-card-content { position: relative; opacity: 1 !important; }
            .product-card img { display: none; }
            .product-card:hover img { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Available Equipment</h1>
            <a href="{{ route('solicitud.cart') }}" class="btn btn-custom1 position-relative">
                <i class="fas fa-shopping-cart"></i> View Cart
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ count(session('equipment_cart', [])) }}
                </span>
            </a>
        </div>

        <div class="d-flex justify-content-center gap-4 flex-wrap" id="equipmentContainer">
            @foreach($equipos as $equipment)
            <div class="card-custom product-card">
                <img src="{{ $equipment->img_path ?? 'https://storage.googleapis.com/a1aa/image/c7d51ac1-0253-48bc-7020-40fff88aee8a.jpg' }}" alt="{{ $equipment->modelo }}">
                <div class="product-card-content">
                    <h5 class="card-title text-truncate">{{ $equipment->modelo }}</h5>
                    <p class="card-text">Category: {{ $equipment->categorias->first()->nombre ?? 'Uncategorized' }}</p>
                    <p class="card-text">Brand: {{ $equipment->marca->nombre }}</p>
                    <p class="card-text">In Stock: {{ $equipment->cantidad_disponible }} units</p>

                    @if($equipment->cantidad_disponible > 0)
                    <button type="button" class="btn btn-custom open-modal"
                            data-bs-toggle="modal" data-bs-target="#cartModal"
                            data-id="{{ $equipment->id }}"
                            data-title="{{ $equipment->modelo }}"
                            data-max="{{ $equipment->cantidad_disponible }}">
                        Request
                    </button>
                    @else
                    <button type="button" class="btn btn-NoDisponible">Not Available</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Add to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('solicitud.addToCart') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-black mb-3 fs-6">
                            Add <span id="modalProductTitle" class="fw-bold">this item</span> to your request?
                        </p>

                        <input type="hidden" name="lote_id" id="modalLoteId">

                        <div class="mb-3">
                            <label for="quantityInput" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantityInput" name="cantidad" min="1" value="1" required>
                            <small class="text-muted">Max available: <span id="maxQuantity">0</span></small>
                        </div>

                        <div class="mb-3">
                            <label for="dueDateInput" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="dueDateInput" name="fecha_limite" min="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

                // Set minimum date to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('dueDateInput').min = today;
            });
        });
    </script>
</body>
</html>
