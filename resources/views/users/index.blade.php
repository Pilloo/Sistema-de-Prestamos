@extends('template')

@section('title','Lista de usuarios')

@push('css')
<style>
    .user-list-container {
    max-width: 1200px;
    margin: auto;
    background: white;
    border-radius: 1rem;
    border: 1px solid #c1c7d0;
    box-shadow: 0 0 20px rgb(0 0 0 / 0.05);
    overflow: hidden;
    padding: 2rem;
    }

    .user-list-header {
        font-size: 1.5rem;
        font-weight: 600;
        text-align: center;
        padding: 1.5rem 0;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .btn-custom {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s;
    }

    .table tbody {
        font-size: 1rem;
    }

    .table thead {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
        font-size: 1.1rem;
    }

    .table-responsive {
        max-height: 500px;
        overflow-y: auto;
    }

</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="user-list-container">
        <h2 class="user-list-header">Lista de Usuarios</h2>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-outline-primary btn-custom">
                <i class="fas fa-user-plus"></i> Crear nuevo usuario
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Departamento</th>
                        <th>Sección</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} {{ $user->primer_apellido }} {{ $user->segundo_apellido }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            {{ $user->departamento && $user->departamento->caracteristica ? $user->departamento->caracteristica->nombre : 'Sin departamento' }}
                        </td>
                        <td>
                            {{ $user->seccion && $user->seccion->caracteristica ? $user->seccion->caracteristica->nombre : 'Sin sección' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning btn-custom">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-custom" onclick="return confirm('¿Eliminar este usuario?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@endpush
