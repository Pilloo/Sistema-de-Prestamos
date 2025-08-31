@extends('template')

@section('title','Editar Usuario')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/scanner.css') }}">
<link rel="stylesheet" href="{{ asset('css/lotecreate.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 bg-white shadow-lg rounded-4 p-5" style="background-color: #f1f1f1ff;">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center fw-semibold text-dark">Editar Usuario</h4>
                    <p class="text-muted text-center mb-4">Nota: Los campos marcados con * son obligatorios</p>

                    <form action="{{ route('users.update', $user) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium">Nombre *</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name', $user->name) }}">
                                @error('name')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            <!-- Primer Apellido -->
                            <div class="col-md-6">
                                <label for="primer_apellido" class="form-label fw-medium">Primer Apellido *</label>
                                <input type="text" name="primer_apellido" id="primer_apellido" class="form-control" 
                                       value="{{ old('primer_apellido', $user->primer_apellido) }}">
                                @error('primer_apellido')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            <!-- Segundo Apellido -->
                            <div class="col-md-6">
                                <label for="segundo_apellido" class="form-label fw-medium">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control" 
                                       value="{{ old('segundo_apellido', $user->segundo_apellido) }}">
                                @error('segundo_apellido')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            <!-- Correo -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-medium">Correo Electrónico *</label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="{{ old('email', $user->email) }}">
                                @error('email')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            @can('editar perfil avanzado')
                            <!-- Departamento -->
                            <div class="col-md-6">
                                <label for="departamento_id" class="form-label fw-medium">Departamento</label>
                                <select name="departamento_id" id="departamento_id" class="form-select">
                                    <option value="" selected disabled>Seleccione:</option>
                                    @foreach ($departamentos as $item)
                                    <option value="{{$item->id}}" 
                                        @selected(old('departamento_id', $user->departamento_id)==$item->id)>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('departamento_id')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>
                            @endcan

                            <!-- Sección -->
                            <div class="col-md-6">
                                <label for="seccion_id" class="form-label fw-medium">Sección</label>
                                <select name="seccion_id" id="seccion_id" class="form-select">
                                    <option value="" selected disabled>Seleccione:</option>
                                    @foreach ($secciones as $item)
                                    <option value="{{$item->id}}" 
                                        @selected(old('seccion_id', $user->seccion_id)==$item->id)>
                                        {{$item->nombre}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('seccion_id')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            @can('editar perfil avanzado')
                            <!-- Rol -->
                            <div class="col-md-6">
                                <label for="role" class="form-label fw-medium">Rol</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="" disabled>Seleccione:</option>
                                    @foreach ($roles as $rol)
                                    <option value="{{ $rol->name }}" @selected($user->hasRole($rol->name))>
                                        {{ $rol->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>
                            @endcan

                            <!-- Imagen -->
                            <div class="col-md-6">
                                <label for="img_path" class="form-label fw-medium">Imagen</label>
                                <input type="file" name="img_path" id="img_path" class="form-control">
                                @if($user->img_path)
                                    <img src="{{ asset('img/users/'.$user->img_path) }}" 
                                         alt="Imagen actual" class="img-fluid border rounded mt-2" 
                                         style="max-width: 120px;">
                                @endif
                                @error('img_path')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-medium">Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                            </div>

                        </div>

                        <!-- Botones -->
                        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                            <a href="{{ route('users.index')}}" class="btn btn-outline-secondary px-4">Cancelar</a>
                            <button type="reset" class="btn btn-outline-dark px-4">Reiniciar</button>
                            <button type="submit" class="btn btn-primary px-5">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
