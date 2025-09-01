@extends('template')

@section('title','Lista de usuarios')

@push('css')
<link href="{{ asset('css/users.css') }}" rel="stylesheet" />
@endpush

@section('content')
<body>
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
</body>
@endsection

@push('js')
<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
@endpush
