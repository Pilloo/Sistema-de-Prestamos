@extends('template')


@section('title','Lista de usuarios')


@push('css')
@endpush


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Lista de Usuarios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Usuarios</li>
    </ol>


    <div class="card">
        <div class="card-header">
            <a href="{{ route('users.create') }}" class="btn btn-primary">Crear nuevo usuario</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Departamento</th>
                        <th>Sección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} {{ $user->primer_apellido }} {{ $user->segundo_apellido }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->departamento->nombre ?? '-' }}</td>
                        <td>{{ $user->seccion->nombre ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('users.destroy', $user) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
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
@endpush
