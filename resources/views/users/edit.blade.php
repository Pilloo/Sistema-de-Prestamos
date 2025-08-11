@extends('template')


@section('title','Editar usuario')


@push('css')
@endpush


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
        <li class="breadcrumb-item active">Editar usuario</li>
    </ol>


    <div class="card">
        <div class="card-header">
            <p>Nota: Los campos marcados con * son obligatorios</p>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
                <!-- Primer Apellido -->
                <div class="row mb-4">
                    <label for="primer_apellido" class="col-md-auto col-form-label">Primer Apellido:</label>
                    <div class="col-md-4">
                        <input type="text" name="primer_apellido" id="primer_apellido" class="form-control" value="{{ old('primer_apellido', $user->primer_apellido) }}">
                    </div>
                    <div class="col-md-4">
                        @error('primer_apellido')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Segundo Apellido -->
                <div class="row mb-4">
                    <label for="segundo_apellido" class="col-md-auto col-form-label">Segundo Apellido:</label>
                    <div class="col-md-4">
                        <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control" value="{{ old('segundo_apellido', $user->segundo_apellido) }}">
                    </div>
                    <div class="col-md-4">
                        @error('segundo_apellido')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Departamento -->
                <div class="row mb-4">
                    <label for="departamento_id" class="col-md-auto col-form-label">Departamento:</label>
                    <div class="col-md-4">
                        <select name="departamento_id" id="departamento_id" class="form-select">
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($departamentos as $item)
                            <option value="{{$item->id}}" @selected(old('departamento_id', $user->departamento_id)==$item->id)>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        @error('departamento_id')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Sección -->
                <div class="row mb-4">
                    <label for="seccion_id" class="col-md-auto col-form-label">Sección:</label>
                    <div class="col-md-4">
                        <select name="seccion_id" id="seccion_id" class="form-select">
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($secciones as $item)
                            <option value="{{$item->id}}" @selected(old('seccion_id', $user->seccion_id)==$item->id)>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        @error('seccion_id')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Imagen -->
                <div class="row mb-4">
                    <label for="img_path" class="col-md-auto col-form-label">Imagen:</label>
                    <div class="col-md-4">
                        <input type="file" name="img_path" id="img_path" class="form-control">
                        @if($user->img_path)
                            <img src="{{ asset('img/users/'.$user->img_path) }}" alt="Imagen actual" width="80" class="mt-2">
                        @endif
                    </div>
                    <div class="col-md-4">
                        @error('img_path')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>
                @csrf
                @method('PUT')


                <!-- Nombre -->
                <div class="row mb-4">
                    <label for="name" class="col-md-auto col-form-label">Nombre:</label>
                    <div class="col-md-4">
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="col-md-4">
                        @error('name')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>


                <!-- Correo Electrónico -->
                <div class="row mb-4">
                    <label for="email" class="col-md-auto col-form-label">Correo Electrónico:</label>
                    <div class="col-md-4">
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="col-md-4">
                        @error('email')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>


                <!-- Contraseña -->
                <div class="row mb-4">
                    <label for="password" class="col-md-auto col-form-label">Contraseña:</label>
                    <div class="col-md-4">
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="col-md-4">
                        @error('password')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>


                <!-- Botones -->
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="reset" class="btn btn-secondary">Reiniciar</button>
                </div>


            </form>
        </div>
    </div>
</div>
@endsection


@push('js')
@endpush
