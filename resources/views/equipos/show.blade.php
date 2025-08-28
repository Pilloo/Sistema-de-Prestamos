@extends('template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100 p-4">
    <div class="card card-custom p-4">
        <div class="row align-items-center">
            <div class="col-md-5 d-flex justify-content-center mb-4 mb-md-0">
                @if($equipo->img_path)
                    <img src="{{ asset('img/equipos/' . $equipo->lote->img_path) }}" 
                         alt="Imagen del equipo" 
                         class="img-fluid rounded" 
                         style="max-height: 180px; width: auto; object-fit: cover;">
                @else
                    <img src="{{ asset('img/template/default-equipo.png') }}" 
                         alt="Imagen del equipo" 
                         class="img-fluid rounded" 
                         style="max-height: 180px; width: auto; object-fit: cover;">
                @endif
            </div>
            <div class="col-md-7">
                <h2 class="mb-3" style="font-weight: 500; font-size: 1.5rem; color: black;">
                    {{ $equipo->lote->modelo }}
                </h2>

                <p class="mb-1 text-muted-custom" style="font-size: 1rem;">
                    Número de Serie: <strong>{{ $equipo->numero_serie }}</strong>
                </p>

                <p class="mb-1 text-muted-custom" style="font-size: 1rem;">
                    Contenido Etiqueta: <strong>{{ $equipo->lote->contenido_etiqueta }}</strong>
                </p>

                <p class="mb-1 text-muted-custom" style="font-size: 1rem;">
                    Marca del Lote: 
                    <strong>{{ $equipo->lote->marca && $equipo->lote->marca->caracteristica ? $equipo->lote->marca->caracteristica->nombre : 'Sin marca' }}</strong>
                </p>

                <p class="mb-1 text-muted-custom" style="font-size: 1rem;">
                    Categorías del Lote:
                    <strong>
                        @forelse ($equipo->lote->categorias as $listaCategorias)
                            {{ $listaCategorias->caracteristica->nombre ?? 'Sin categoría' }}
                        @empty
                            Sin categoría
                        @endforelse
                    </strong>
                </p>

                <p class="mb-4 text-muted-custom" style="font-size: 1rem;">
                    Estado: <strong>{{ $equipo->estado_equipo?->nombre ?? 'Sin estado' }}</strong>
                </p>
            </div>
        </div>

        <div class="detalle-custom mt-4 p-3">
            <h5 class="mb-2" style="font-weight: 600;">Detalle:</h5>
            <p class="mb-0" style="font-size: 1rem; color: black;">
                {{ $equipo->lote->detalle }}
            </p>
        </div>

        <div class="card-footer-custom">
            <a href="{{ route('equipos.index') }}" class="btn btn-custom">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn btn-custom">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
</div>

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
<style>
    body {
        background: white;
    }
    .card-custom {
        border-radius: 1rem;
        border: 1px solid #cbd5e1;
        box-shadow: 0 0 20px rgb(0 0 0 / 0.1);
        max-width: 720px;
        width: 100%;
    }
    .detalle-custom {
        border-top: 1px solid #e2e8f0;
        font-size: 1rem;
    }
    .btn-custom {
        padding: 0.5rem 1.2rem;
        border: 2px solid #315cfd;
        border-radius: 30px;
        background: white;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s;
        color: #315cfd;
        text-decoration: none;
    }
    .btn-custom:hover {
        background: #315cfd;
        color: white;
    }
    .text-muted-custom {
        color: #5f5f5f !important;
    }
    .card-footer-custom {
        background-color: transparent;
        border-top: 1px solid #e2e8f0;
        padding-top: 1rem;
        margin-top: 1.5rem;
        display: flex;
        justify-content: space-between;
    }
</style>
@endpush
@endsection
