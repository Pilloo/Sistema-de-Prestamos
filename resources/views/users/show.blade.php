@extends('template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100 p-4">
    <div class="user-card mx-auto">
        <div class="d-flex align-items-center mb-4">
            <div class="avatar-bg me-4">
                @if($user->img_path)
                    <img src="{{ asset('img/users/' . $user->img_path) }}" alt="Foto de perfil" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <img src="{{ asset('img/template/default-user.png') }}" alt="Foto de perfil" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                @endif
            </div>
            <div>
                <p class="mb-1 fw-bold" style="font-size: 1.5rem;">{{ $user->name }}</p>
                <p class="mb-0 text-muted" style="font-size: 1rem;">{{ $user->email }}</p>
            </div>
        </div>
        <div class="mb-4">
            <p class="mb-2" style="font-size: 1rem;">Rol: <strong>{{ $user->getRoleNames()->first() ?? 'Sin rol' }}</strong></p>
            <p class="mb-2" style="font-size: 1rem;">Departamento: <strong>{{ $user->departamento?->caracteristica?->nombre ?? 'Sin departamento' }}</strong></p>
            <p class="mb-0" style="font-size: 1rem;">Sección: <strong>{{ $user->seccion?->caracteristica?->nombre ?? 'Sin sección' }}</strong></p>
        </div>
        <div class="d-flex justify-content-between gap-4">
            <a href="{{ url('/') }}" class="btn btn-custom">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-custom">
                <i class="fas fa-pen"></i> Editar
            </a>
        </div>
    </div>
</div>

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
<style>
    .user-card {
        border-radius: 1.5rem;
        border: 1px solid #c1c7d0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
        padding: 3rem 2.5rem;
        background-color: white;
    }
    .avatar-bg {
        background-color: #f0f0f0;
        border-radius: 50%;
        width: 130px;
        height: 130px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .btn-custom {
        padding: 0.6rem 1.4rem;
        border: 2px solid #315cfd;
        border-radius: 30px;
        background: white;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #315cfd;
        text-decoration: none;
    }
    .btn-custom:hover {
        background: #315cfd;
        color: white;
    }
</style>
@endpush
@endsection
