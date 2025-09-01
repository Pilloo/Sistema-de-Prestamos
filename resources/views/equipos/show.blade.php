@extends('template')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/equipos.css') }}" rel="stylesheet" />
@endpush

@if(session('success'))
<script>
Swal.fire({
    toast: true,
    position: "top-end",
    icon: "success",
    title: "{{ session('success') }}",
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true
});
</script>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

@section('content')
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 p-4">
        <div class="card card-custom p-4">
            <div class="row align-items-center">
                <div class="col-md-5 d-flex justify-content-center mb-4 mb-md-0">
                    @if($equipo->lote->img_path)
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
</body>

@endsection
